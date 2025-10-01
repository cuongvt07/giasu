<?php

namespace App\Http\Controllers;

use App\Models\AIConversation;
use App\Models\AIMessage;
use App\Models\AiRecommendation;
use App\Models\Tutor;
use App\Models\Subject;
use App\Models\ClassLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AIAdvisorController extends Controller
{
    /**
     * Xử lý tin nhắn chat với AI
     */
    public function chat(Request $request)
    {
        try {
            // Lấy hoặc tạo conversation
            $conversation = null;
            if (!$request->session()->has('conversation_id')) {
                $conversation = AIConversation::create([
                    'user_id' => Auth::id(),
                ]);
                $request->session()->put('conversation_id', $conversation->id);
            } else {
                $conversation = AIConversation::find($request->session()->get('conversation_id'));
                if (!$conversation) {
                    $conversation = AIConversation::create([
                        'user_id' => Auth::id(),
                    ]);
                    $request->session()->put('conversation_id', $conversation->id);
                }
            }

            // Lưu message user
            if ($request->message) {
                $msg = new AIMessage([
                    'role' => 'user',
                    'content' => $request->message
                ]);
                $conversation->messages()->save($msg);
            }

            // Nếu là tổng kết thì chỉ lấy recommendations
            if ($request->type === 'summarize') {
                $recommendations = $this->getRecommendations($conversation);
                return response()->json([
                    'recommendations' => $recommendations
                ]);
            }

            // Gọi AI trả lời
            $recommendations = $this->getRecommendations($conversation);
            $aiMessage = null;
            if (!empty($recommendations) && isset($recommendations[0]['reason'])) {
                $aiMessage = $recommendations[0]['reason'];
            } else {
                $aiMessage = 'Xin lỗi, tôi chưa tìm được kết quả phù hợp.';
            }

            // Lưu message AI
            $msg = new AIMessage([
                'role' => 'assistant',
                'content' => $aiMessage
            ]);
            $conversation->messages()->save($msg);

            return response()->json([
                'message' => $aiMessage,
                'recommendations' => $recommendations
            ]);
        } catch (Exception $e) {
            Log::error('Error in chat', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'error' => true
            ], 500);
        }
    }

    /**
     * Hiển thị trang tư vấn AI
     */
    public function index()
    {
        $conversation = null;
        $recommendations = collect();
        $user = auth()->user();
        $tutorId = $user?->tutor?->user_id;
        
        return view('pages.ai-advisor.index', compact('conversation', 'recommendations', 'tutorId', 'user'));
    }

    private function getRecommendations($conversation)
    {
        try {
            // --- BẮT ĐẦU TOÀN BỘ LOGIC GỐC ---
            Log::info('Starting getRecommendations');
            $conversation = AIConversation::find($conversation->id);
            $userMessages = $conversation->messages()->where('role', 'user')->get();

            if ($userMessages->isEmpty()) return $this->getFallbackRecommendations();
            $combinedUserMessages = $userMessages->pluck('content')->join("\n");

            // 1. Intent detection
            $intent = 'tutor';
            
$intentPrompt = [
  'role' => 'system',
  'content' => 'Bạn là hệ thống phân loại ý định người dùng. 
  Nhiệm vụ: từ duy nhất "user message" (KHÔNG bao gồm bất kỳ văn bản nào do assistant đã trả lời), trả về một object JSON duy nhất: {"intent":"<tutor|job|support|academic_question|general_info|other>"}.

  QUY TẮC NGHIÊM NGẶT:
  1) Chỉ phân tích "user message" được cung cấp trong role user. BỎ QUA mọi nội dung do assistant hệ thống tạo ra.
  2) Không bao giờ chọn "job" trừ khi có dấu hiệu rõ ràng: từ như "tuyển", "tuyển dụng", "ứng tuyển", "có lớp", "lớp dạy", "đăng tin", "tìm việc", "việc làm".
  3) Nếu câu hỏi có dấu hiệu yêu cầu lời giải/bài tập (từ "giải","bài tập","đáp án","chứng minh", ký hiệu toán học, phương trình) → chọn "academic_question".
  4) Nếu là câu hỏi thao tác/hỗ trợ (có "cách","làm sao","đăng ký","đăng nhập","hướng dẫn","hỗ trợ","lỗi","hotline","zalo") → "support".
  5) Nếu có "gia sư","dạy kèm","tìm gia sư","cần người dạy" nhưng KHÔNG có từ tuyển dụng thì "tutor".
  6) Nếu là câu hỏi facts/số liệu/ai-là/bao-nhiêu/năm → "general_info".
  7) Nếu không chắc chắn → trả "other".
  8) OUTPUT: Chỉ in một dòng JSON, ví dụ: {"intent":"support"}. Không in giải thích nào khác.'
];



            $intentResponse = OpenAI::chat()->create([
                'model' => 'gpt-4-turbo-preview',
                'messages' => [
                    $intentPrompt,
                    ['role' => 'user', 'content' => $combinedUserMessages]
                ],
                'temperature' => 0.1,
                'response_format' => ['type' => 'json_object']
            ]);

            $intentJson = json_decode($intentResponse->choices[0]->message->content, true);
            if (isset($intentJson['intent'])) {
                $intent = $intentJson['intent'];
            }
            Log::info('AI intent detected', ['intent' => $intent]);

            // 2. Nếu intent là support → trả về thông tin hỗ trợ
            if ($intent === 'support') {
                return [
                    [
                        'type' => 'support',
                        'id' => 0,
                        'name' => 'Hỗ trợ hệ thống',
                        'avatar' => '/images/support.png',
                        'hourly_rate' => 0,
                        'matching_score' => 1,
                        'reason' => 'Bạn có thể liên hệ hotline: 0988 123 456, Zalo: 0988 123 456, hoặc Facebook: fb.com/giasu. Đội ngũ hỗ trợ luôn sẵn sàng giúp bạn!'
                    ]
                ];
            }

            // 3. Nếu intent là math_problem → trả lời tổng quát, không giải chi tiết
            if ($intent === 'academic_question') {
                // Prompt tổng quát để xử lý câu hỏi học thuật
                $academicPrompt = [
                    'role' => 'system',
                    'content' => 'Bạn là một trợ lý AI thông minh, có khả năng trả lời các câu hỏi học thuật từ nhiều môn học (Toán, Lý, Hóa, Văn, Anh, Lịch sử, v.v.) bao gồm bài tập, lý thuyết, trắc nghiệm, hoặc đề thi. Hãy phân tích câu hỏi và trả lời theo các bước sau:

                    1. Xác định môn học (Toán, Lý, Hóa, Văn, Anh, Lịch sử, hoặc "khác" nếu không rõ).
                    2. Xác định loại câu hỏi:
                    - "exercise": Bài tập cần giải (phương trình, bài toán, v.v.).
                    - "theory": Câu hỏi lý thuyết (định nghĩa, khái niệm, giải thích).
                    - "multiple_choice": Câu hỏi trắc nghiệm.
                    - "open": Câu hỏi mở hoặc không thuộc các loại trên.
                    3. Phân tích nội dung: Nêu thông tin đã cho và điều cần tìm.
                    4. Trả lời chi tiết:
                    - Nếu là bài tập, cung cấp các bước giải và kết quả cuối cùng.
                    - Nếu là lý thuyết, cung cấp định nghĩa, ví dụ, và giải thích.
                    - Nếu là trắc nghiệm, phân tích từng đáp án và chọn đáp án đúng.
                    - Nếu là câu hỏi mở, trả lời đầy đủ, rõ ràng.
                    5. Kiểm tra tính chính xác và hợp lý của câu trả lời.

                    Trả về định dạng JSON:
                    {
                        "subject": "<môn học>",
                        "question_type": "<exercise | theory | multiple_choice | open>",
                        "analysis": "<phân tích thông tin đã cho và điều cần tìm>",
                        "answer": "<câu trả lời chi tiết, bao gồm các bước nếu cần>",
                        "final_answer": "<kết quả cuối cùng hoặc tóm tắt câu trả lời>",
                        "explanation": "<giải thích thêm, nếu cần>"
                    }

                    Nếu câu hỏi không rõ ràng, trả về:
                    {
                        "error": "Vui lòng cung cấp câu hỏi chi tiết hơn."
                    }

                    LƯU Ý:
                    - Trả lời bằng tiếng Việt, trừ khi người dùng yêu cầu ngôn ngữ khác.
                    - Đảm bảo câu trả lời dễ hiểu, ngắn gọn nhưng đầy đủ.
                    - Sử dụng ký hiệu toán học (nếu có) phù hợp, ví dụ: phương trình \(x^2 + 2x = 0\).'
                ];

                // Gọi API OpenAI
                $academicResponse = OpenAI::chat()->create([
                    'model' => 'gpt-4o', // Hoặc 'gpt-4o-mini' để tối ưu chi phí
                    'messages' => [
                        $academicPrompt,
                        ['role' => 'user', 'content' => $combinedUserMessages]
                    ],
                    'temperature' => 0.3,
                    'response_format' => ['type' => 'json_object']
                ]);

                $academicSolution = json_decode($academicResponse->choices[0]->message->content, true);

                // Nếu câu hỏi không rõ, trả về thông báo lỗi
                if (isset($academicSolution['error'])) {
                    return [
                        [
                            'type' => 'academic_answer',
                            'id' => 0,
                            'name' => 'AI Trả Lời Học Thuật',
                            'avatar' => '/images/ai-academic.png',
                            'hourly_rate' => 0,
                            'matching_score' => 1,
                            'reason' => $academicSolution['error']
                        ]
                    ];
                }

                // Định dạng câu trả lời chi tiết
                $reason = "📚 **Môn học**: {$academicSolution['subject']}\n\n" .
                        "📝 **Loại câu hỏi**: {$academicSolution['question_type']}\n\n" .
                        "🎯 **Phân tích**: {$academicSolution['analysis']}\n\n" .
                        "✏️ **Câu trả lời**:\n{$academicSolution['answer']}\n\n" .
                        "✅ **Kết quả cuối cùng**: {$academicSolution['final_answer']}\n\n" .
                        (isset($academicSolution['explanation']) ? "💡 **Giải thích thêm**: {$academicSolution['explanation']}" : "");

                return [
                    [
                        'type' => 'academic_answer',
                        'id' => 0,
                        'name' => 'AI Trả Lời Học Thuật',
                        'avatar' => '/images/ai-academic.png',
                        'hourly_rate' => 0,
                        'matching_score' => 1,
                        'reason' => $reason
                    ]
                ];
            }

            // 4. Nếu intent là job → tìm tin đăng tuyển (tutor_posts)
            if ($intent === 'job') {
                // Phân tích nhu cầu tìm lớp
                $jobPrompt = [
                    'role' => 'system',
                    'content' => 'Trích xuất các tiêu chí tìm lớp/tin tuyển dụng từ nội dung sau, trả về JSON với cấu trúc:
                    {
                        "subjects": [],
                        "class_levels": [],
                        "mode": "online/offline/both",
                        "max_price": 0,
                        "min_price": 0,
                        "location": "",
                        "requirements": "",
                        "sort_by": "latest/budget"
                    }

                    LƯU Ý:
                    - Cố gắng phân tích ngân sách từ nội dung (ví dụ: "200k/giờ" -> max_price: 200000)
                    - Với location, chỉ lấy tên quận/huyện/thành phố
                    - Nếu không đề cập đến tiêu chí nào thì để giá trị mặc định
                    - Ưu tiên lấy thông tin về ngân sách, môn học và cấp học nếu có
                    - Chỉ trả về JSON.'
                ];

                $jobResponse = OpenAI::chat()->create([
                    'model' => 'gpt-4-turbo-preview',
                    'messages' => [
                        $jobPrompt,
                        ['role' => 'user', 'content' => $combinedUserMessages]
                    ],
                    'temperature' => 0.2,
                    'response_format' => ['type' => 'json_object']
                ]);

                $jobPrefs = json_decode($jobResponse->choices[0]->message->content, true);

                // Query tutor_posts giống postJob()
                $query = DB::table('tutor_posts')
                    ->join('subjects', 'tutor_posts.subject_id', '=', 'subjects.id')
                    ->leftJoin('class_levels', 'tutor_posts.class_level_id', '=', 'class_levels.id')
                    ->leftJoin('tutor_applications as applications', 'tutor_posts.id', '=', 'applications.tutor_post_id')
                    ->select(
                        'tutor_posts.*',
                        'subjects.name as subject_name',
                        'class_levels.name as class_level_name',
                        DB::raw('GROUP_CONCAT(applications.tutor_id) as applied_tutor_ids')
                    )
                    ->where('tutor_posts.status', '=', 'pending')
                    ->groupBy('tutor_posts.id', 'subjects.name', 'class_levels.name');

                if (!empty($jobPrefs['subjects'])) {
                    $query->whereIn('subjects.name', $jobPrefs['subjects']);
                }
                if (!empty($jobPrefs['class_levels'])) {
                    $query->whereIn('class_levels.name', $jobPrefs['class_levels']);
                }
                if (!empty($jobPrefs['min_price'])) {
                    $query->where('tutor_posts.budget_min', '>=', $jobPrefs['min_price']);
                }
                if (!empty($jobPrefs['max_price'])) {
                    $query->where('tutor_posts.budget_max', '<=', $jobPrefs['max_price']);
                }
                if (!empty($jobPrefs['mode']) && $jobPrefs['mode'] !== 'both') {
                    $query->where('tutor_posts.mode', $jobPrefs['mode']);
                }
                if (!empty($jobPrefs['location'])) {
                    $query->where('tutor_posts.address_line', 'like', '%' . $jobPrefs['location'] . '%');
                }

                // Sort
                if (!empty($jobPrefs['sort_by'])) {
                    if ($jobPrefs['sort_by'] === 'budget') {
                        $query->orderByDesc('tutor_posts.budget_max');
                    } else {
                        $query->orderByDesc('tutor_posts.published_at');
                    }
                } else {
                    $query->orderByDesc('tutor_posts.published_at');
                }

                $posts = $query->limit(10)->get();

                $results = [];
                foreach ($posts as $post) {
                    $appliedTutorIds = $post->applied_tutor_ids
                        ? array_map('intval', explode(',', $post->applied_tutor_ids))
                        : [];

                    $results[] = [
                        'type' => 'job_post',
                        'id' => $post->id,
                        'title' => 'Tin đăng tuyển gia sư',
                        'avatar' => '/images/job-post.png',
                        'subject' => $post->subject_name,
                        'class_level' => $post->class_level_name,
                        'mode' => $post->mode,
                        'location' => $post->address_line,
                        'sessions_per_week' => $post->sessions_per_week,
                        'session_length_min' => $post->session_length_min,
                        'budget_min' => $post->budget_min,
                        'budget_max' => $post->budget_max,
                        'budget_unit' => $post->budget_unit ?? 'giờ',
                        'deadline_at' => $post->deadline_at,
                        'goal' => $post->goal,
                        'requirements' => $post->special_notes,
                        'applied_tutors' => $appliedTutorIds,
                        'published_at' => $post->published_at,
                        'status' => $post->status,
                        'matching_score' => 1,
                        'reason' => sprintf(
                            'Tin tuyển gia sư môn %s cấp %s tại %s. Ngân sách: %s - %s VND/%s. %s',
                            $post->subject_name,
                            $post->class_level_name,
                            $post->address_line ?? 'không rõ',
                            number_format($post->budget_min),
                            number_format($post->budget_max),
                            $post->budget_unit ?? 'giờ',
                            $post->special_notes ? "Yêu cầu: " . $post->special_notes : ""
                        )
                    ];
                }

                if (empty($results)) {
                    $results[] = [
                        'type' => 'job_post',
                        'id' => 0,
                        'name' => 'Không tìm thấy tin tuyển dụng phù hợp',
                        'avatar' => '/images/job-post.png',
                        'matching_score' => 0,
                        'reason' => "Hiện tại không có tin đăng tuyển gia sư nào phù hợp với tiêu chí của bạn.\nBạn có thể thử:\n- Giảm bớt tiêu chí\n- Mở rộng ngân sách\n- Thử lại sau"
                    ];
                }

                return $results;
            }


            // 5. Mặc định: tìm gia sư như cũ
            $systemMessage = [
                'role' => 'system',
                'content' => 'QUAN TRỌNG NHẤT: Xác định chính xác môn học mà người dùng cần. Ưu tiên môn học đầu tiên họ đề cập. Hướng dẫn chi tiết: 1. MÔN HỌC LÀ TIÊU CHÍ QUAN TRỌNG NHẤT - Bất kỳ từ nào liên quan đến môn học (Toán, Lý, Hóa, Văn, Anh, Sinh...) phải được ưu tiên cao nhất 2. Nếu người dùng chỉ đề cập một môn học như "tìm gia sư Toán", subjects CHỈ NÊN CÓ ["Toán"] không thêm môn khác 3. Nếu người dùng đề cập nhiều môn, giữ đúng thứ tự ưu tiên mà họ nhắc đến 4. Không thêm môn học nào mà người dùng không đề cập đến 5. Nếu không đề cập môn cụ thể, để trống mảng subjects. Trả về JSON: {"subjects":[], "class_levels":[], "teaching_method":"", "max_price":0, "location":"", "requirements":""}'
            ];
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    $intentPrompt,
                    [
                        'role' => 'user',
                        'content' => $combinedUserMessages,
                    ],
                ],
                'response_format' => ['type' => 'json_object'],
            ]);

            $intent = json_decode($response['choices'][0]['message']['content'], true)['intent'] ?? null;
            $preferences = json_decode($response->choices[0]->message->content, true);
            Log::info('Parsed preferences', ['preferences' => $preferences]);
            
            if (empty($preferences) || json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse JSON from AI response', [
                    'json_error' => json_last_error_msg()
                ]);
                return $this->getFallbackRecommendations();
            }

            $query = Tutor::with(['user', 'subjects', 'classLevels', 'reviews'])
                ->where('status', 'active')
                ->where('is_verified', true);
            
            if (!empty($preferences['subjects'])) {
                $query->whereHas('subjects', function ($q) use ($preferences) {
                    $q->whereIn('name', $preferences['subjects']);
                });
            }
            if (!empty($preferences['class_levels'])) {
                $query->whereHas('classLevels', function ($q) use ($preferences) {
                    $q->whereIn('name', $preferences['class_levels']);
                });
            }
            if (!empty($preferences['max_price'])) {
                $query->where('hourly_rate', '<=', $preferences['max_price']);
            }
            if (!empty($preferences['teaching_method'])) {
                $query->where(function($q) use ($preferences) {
                    $q->where('teaching_method', $preferences['teaching_method'])
                      ->orWhere('teaching_method', 'both');
                });
            }
            if (!empty($preferences['location'])) {
                $query->where('teaching_location', 'like', '%' . $preferences['location'] . '%');
            }
            
            $tutors = $query->get();
            
            if ($tutors->isEmpty()) {
                Log::warning('No tutors found with strict criteria, using relaxed criteria');
                $query = Tutor::with(['user', 'subjects', 'classLevels', 'reviews'])
                    ->where('status', 'active')
                    ->where('is_verified', true);
                
                if (!empty($preferences['subjects'])) {
                    $query->whereHas('subjects', function ($q) use ($preferences) {
                        $q->whereIn('name', $preferences['subjects']);
                    });
                }
                
                $tutors = $query->get();
                
                if ($tutors->isEmpty() && empty($preferences['subjects'])) {
                    return $this->getFallbackRecommendations();
                } elseif ($tutors->isEmpty()) {
                    $similarSubjects = Subject::whereIn('name', $preferences['subjects'])->pluck('name')->toArray();
                    if (!empty($similarSubjects)) {
                        return $this->getFallbackRecommendationsWithSubjects($similarSubjects);
                    }
                    return $this->getFallbackRecommendations();
                }
            }
            
            $recommendations = [];
            foreach ($tutors as $tutor) {
                $score = $this->calculateMatchingScore($tutor, $preferences);
                if ($score >= 0.1) {
                    $recommendations[] = [
                        'type' => 'tutor',
                        'id' => $tutor->id,
                        'name' => $tutor->user->name ?? 'Gia sư',
                        'avatar' => $tutor->avatar ?? '/images/default-avatar.png',
                        'subjects' => $tutor->subjects->pluck('name')->toArray(),
                        'class_levels' => $tutor->classLevels->pluck('name')->toArray(),
                        'hourly_rate' => $tutor->hourly_rate,
                        'rating' => number_format($tutor->reviews->avg('rating') ?? 5.0, 1),
                        'review_count' => $tutor->reviews->count(),
                        'experience_years' => $tutor->experience_years,
                        'teaching_method' => $tutor->teaching_method,
                        'matching_score' => $score,
                        'reason' => $this->generateRecommendationReason($tutor, $preferences)
                    ];
                }
            }
            
            if (empty($recommendations)) {
                Log::warning('No recommendations with matching score >= 0.1, using fallback');
                return $this->getFallbackRecommendations();
            }
            
            usort($recommendations, function($a, $b) {
                return $b['matching_score'] <=> $a['matching_score'];
            });
            
            return array_slice($recommendations, 0, 10);
            // --- KẾT THÚC LOGIC GỐC ---
        } catch (\Throwable $e) {
            Log::error('Error in getRecommendations (outer catch)', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->getFallbackRecommendations();
        }
    }

    private function getFallbackRecommendations()
    {
        // Lấy 10 gia sư active, verified có rating cao nhất
        $tutors = Tutor::with(['user', 'subjects', 'classLevels', 'reviews'])
            ->where('status', 'active')
            ->where('is_verified', true)
            ->get()
            ->sortByDesc(function($tutor) {
                return $tutor->reviews->avg('rating') ?? 5.0;
            })
            ->take(10);
            
        $recommendations = [];
        foreach ($tutors as $tutor) {
            $recommendations[] = [
                'type' => 'tutor',
                'id' => $tutor->id,
                'name' => $tutor->user->name,
                'avatar' => $tutor->avatar ? url(Storage::url($tutor->avatar)) : null,
                'subjects' => $tutor->subjects->pluck('name')->toArray(),
                'class_levels' => $tutor->classLevels->pluck('name')->toArray(),
                'hourly_rate' => $tutor->hourly_rate,
                'rating' => number_format($tutor->reviews->avg('rating') ?? 5.0, 1),
                'review_count' => $tutor->reviews->count(),
                'experience_years' => $tutor->experience_years,
                'teaching_method' => $tutor->teaching_method,
                'matching_score' => 1.0,
                'reason' => 'Gia sư này có đánh giá tốt từ học sinh trước đây'
            ];
        }
        
        return $recommendations;
    }

    private function calculateMatchingScore($tutor, $preferences)
    {
        $score = 0;
        $weights = [
            'subjects' => 0.5,
            'class_levels' => 0.15,
            'teaching_method' => 0.1,
            'price' => 0.1,
            'location' => 0.05,
            'experience' => 0.1
        ];

        if (isset($preferences['subjects']) && !empty($preferences['subjects'])) {
            $tutorSubjects = $tutor->subjects->pluck('name')->toArray();
            $matchingSubjects = array_intersect($tutorSubjects, $preferences['subjects']);
            $totalSubjects = count($preferences['subjects']);
            
            if ($totalSubjects > 0) {
                $subjectScore = count($matchingSubjects) / $totalSubjects;
                if (count($matchingSubjects) == 0) {
                    $subjectScore = 0;
                }
                $score += $weights['subjects'] * $subjectScore;
            } else {
                $score += $weights['subjects'];
            }
        } else {
            $score += $weights['subjects'];
        }

        if (isset($preferences['subjects']) && !empty($preferences['subjects']) && 
            $score <= 0.01) {
            return 0.01;
        }

        if (isset($preferences['class_levels']) && !empty($preferences['class_levels'])) {
            $matchingLevels = $tutor->classLevels->whereIn('name', $preferences['class_levels'])->count();
            $totalLevels = count($preferences['class_levels']);
            if ($totalLevels > 0) {
                $score += $weights['class_levels'] * ($matchingLevels / $totalLevels);
            } else {
                $score += $weights['class_levels'];
            }
        } else {
            $score += $weights['class_levels'];
        }

        if (isset($preferences['teaching_method']) && !empty($preferences['teaching_method'])) {
            if ($tutor->teaching_method === $preferences['teaching_method'] || 
                $tutor->teaching_method === 'both') {
                $score += $weights['teaching_method'];
            }
        } else {
            $score += $weights['teaching_method'];
        }

        if (isset($preferences['max_price']) && $preferences['max_price'] > 0) {
            $priceScore = 1 - ($tutor->hourly_rate / $preferences['max_price']);
            $score += $weights['price'] * max(0, $priceScore);
        } else {
            $score += $weights['price'];
        }

        if (isset($preferences['location']) && !empty($preferences['location'])) {
            if (str_contains(strtolower($tutor->teaching_location), strtolower($preferences['location']))) {
                $score += $weights['location'];
            }
        } else {
            $score += $weights['location'];
        }

        $score += $weights['experience'] * min(1, ($tutor->experience_years ?? 0) / 5);

        return round($score, 2);
    }

    private function generateRecommendationReason($tutor, $preferences)
    {
        $reasons = [];
        
        $matchingSubjects = $tutor->subjects->whereIn('name', $preferences['subjects'] ?? [])->pluck('name')->toArray();
        if (!empty($matchingSubjects)) {
            $reasons[] = "Chuyên dạy các môn " . implode(', ', $matchingSubjects);
        }

        $matchingLevels = $tutor->classLevels->whereIn('name', $preferences['class_levels'] ?? [])->pluck('name')->toArray();
        if (!empty($matchingLevels)) {
            $reasons[] = "Có kinh nghiệm giảng dạy " . implode(', ', $matchingLevels);
        }

        if ($tutor->experience_years > 0) {
            $reasons[] = "Có " . $tutor->experience_years . " năm kinh nghiệm giảng dạy";
        }

        if (isset($preferences['max_price']) && $tutor->hourly_rate <= $preferences['max_price']) {
            $reasons[] = "Mức học phí phù hợp với ngân sách";
        }

        if ($tutor->reviews->count() > 0) {
            $rating = number_format($tutor->reviews->avg('rating'), 1);
            $reasons[] = "Được đánh giá " . $rating . "/5.0 từ " . $tutor->reviews->count() . " học viên";
        }

        return implode(". ", $reasons);
    }

    private function getFallbackRecommendationsWithSubjects($subjects)
    {
        $subjectIds = Subject::whereIn('name', $subjects)
            ->orWhere(function($q) use ($subjects) {
                foreach($subjects as $subject) {
                    $q->orWhere('name', 'like', '%' . $subject . '%');
                }
            })
            ->pluck('id')
            ->toArray();
            
        Log::info('Fallback with subjects', [
            'requested_subjects' => $subjects,
            'found_subject_ids' => $subjectIds
        ]);
        
        if (empty($subjectIds)) {
            return $this->getFallbackRecommendations();
        }
        
        $tutors = Tutor::with(['user', 'subjects', 'classLevels', 'reviews'])
            ->where('status', 'active')
            ->where('is_verified', true)
            ->whereHas('subjects', function($q) use ($subjectIds) {
                $q->whereIn('id', $subjectIds);
            })
            ->get()
            ->sortByDesc(function($tutor) {
                return $tutor->reviews->avg('rating') ?? 5.0;
            })
            ->take(10);
            
        if ($tutors->isEmpty()) {
            return $this->getFallbackRecommendations();
        }
            
        $recommendations = [];
        foreach ($tutors as $tutor) {
            $matchingSubjects = $tutor->subjects->whereIn('id', $subjectIds)->pluck('name')->toArray();
            
            $recommendations[] = [
                'type' => 'tutor',
                'id' => $tutor->id,
                'name' => $tutor->user->name,
                'avatar' => $tutor->avatar ? url(Storage::url($tutor->avatar)) : null,
                'subjects' => $tutor->subjects->pluck('name')->toArray(),
                'class_levels' => $tutor->classLevels->pluck('name')->toArray(),
                'hourly_rate' => $tutor->hourly_rate,
                'rating' => number_format($tutor->reviews->avg('rating') ?? 5.0, 1),
                'review_count' => $tutor->reviews->count(),
                'experience_years' => $tutor->experience_years,
                'teaching_method' => $tutor->teaching_method,
                'matching_score' => 1.0,
                'reason' => 'Gia sư này dạy ' . implode(', ', $matchingSubjects) . ' và có đánh giá tốt từ học sinh'
            ];
        }
        
        return $recommendations;
    }

    public function resetConversation(Request $request)
    {
        try {
            Log::info('Resetting conversation', [
                'session_id' => $request->session()->getId(),
                'old_conversation_id' => $request->session()->get('conversation_id')
            ]);
            
            $request->session()->forget('conversation_id');
            
            Log::info('Conversation reset successfully');
            
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error('Error in resetConversation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}