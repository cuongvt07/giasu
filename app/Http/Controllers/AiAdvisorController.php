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
     * Hiển thị trang tư vấn AI
     */
    public function index()
    {
        $conversation = null;
        $recommendations = collect();
        
        return view('pages.ai-advisor.index', compact('conversation', 'recommendations'));
    }

    /**
     * Xử lý tin nhắn chat với AI
     */
    public function chat(Request $request)
    {
        try {
            Log::info('OpenAI API Key:', [
                'key_exists' => !empty(config('openai.api_key')),
                'key_length' => strlen(config('openai.api_key')),
                'key_value' => substr(config('openai.api_key'), 0, 10) . '...'
            ]);

            if (empty(config('openai.api_key'))) {
                return response()->json([
                    'message' => 'Lỗi: Chưa cấu hình OpenAI API key',
                    'error' => true
                ], 500);
            }

            // Tạo conversation mới nếu type = new hoặc không có conversation_id
            if ($request->type === 'new' || !$request->session()->has('conversation_id')) {
                $request->session()->forget('conversation_id');
                $conversation = AIConversation::create([
                    'user_id' => Auth::id() ?: null,  // Cho phép user_id null nếu chưa đăng nhập
                    'status' => 'active'
                ]);
                $request->session()->put('conversation_id', $conversation->id);
            } else {
                $conversation = AIConversation::firstOrCreate(
                    ['id' => $request->session()->get('conversation_id')],
                    [
                        'user_id' => Auth::id() ?: null,  // Cho phép user_id null nếu chưa đăng nhập
                        'status' => 'active'
                    ]
                );
            }

            $request->session()->put('conversation_id', $conversation->id);

            if ($request->message) {
                $userMessage = new AIMessage([
                    'role' => 'user',
                    'content' => $request->message
                ]);
                $conversation->messages()->save($userMessage);
            }

            if ($request->type === 'summarize') {
                $summary = $this->generateSummary($conversation);
                $recommendations = $this->getRecommendations($conversation);

                return response()->json([
                    'summary' => $summary,
                    'recommendations' => $recommendations,
                    'debug' => [
                        'conversation_id' => $conversation->id,
                        'message_count' => $conversation->messages()->count(),
                        'recommendation_count' => count($recommendations)
                    ]
                ]);
            }

            $systemMessage = [
                'role' => 'system',
                'content' => 'Bạn là trợ lý AI tư vấn tìm gia sư TRONG HỆ THỐNG CỦA CHÚNG TA. 
                TUYỆT ĐỐI KHÔNG ĐƯỢC GIỚI THIỆU CÁC NỀN TẢNG KHÁC.
                
                Khi học sinh đưa ra yêu cầu, xử lý NGAY LẬP TỨC:
                1. Chào hỏi thân thiện
                2. XÁC NHẬN RÕ RÀNG thông tin đã hiểu từ yêu cầu, ĐẶC BIỆT LÀ MÔN HỌC
                3. Nếu họ tìm gia sư Toán, xác nhận lại "Bạn đang tìm gia sư dạy môn Toán, đúng không?"
                4. Nếu họ chưa đề cập môn học, HÃY HỎI NGAY "Bạn đang tìm gia sư dạy môn học nào?"
                5. Nếu họ đề cập nhiều môn, xác nhận lại "Bạn đang tìm gia sư dạy các môn X, Y, Z phải không?"
                6. Thông báo "Tôi đã tìm thấy một số gia sư dạy [MÔN HỌC] phù hợp trong hệ thống của chúng tôi"
                7. Gợi ý "Bạn có thể bấm nút Tổng kết ngay để xem danh sách gia sư được đề xuất"
                
                ƯU TIÊN XÁC NHẬN MÔN HỌC, sau đó mới là cấp học, mức giá...
                
                TUYỆT ĐỐI KHÔNG:
                - Không giới thiệu các nền tảng khác
                - Không trả lời dài dòng
                
                Tất cả các đề xuất phải là về việc sử dụng hệ thống của chúng ta để tìm gia sư phù hợp.
                
                Nếu phản hồi của bạn chứa bất kỳ đề xuất nào về việc sử dụng nền tảng bên ngoài hoặc tìm kiếm gia sư bên ngoài, hãy sửa lại phản hồi của bạn.'
            ];

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4-turbo-preview',
                'messages' => array_merge(
                    [$systemMessage],
                    $conversation->messages()
                        ->orderBy('created_at', 'asc')
                        ->get()
                        ->map(function ($message) {
                            return [
                                'role' => $message->role,
                                'content' => $message->content
                            ];
                        })
                        ->toArray()
                ),
                'temperature' => 0.7
            ]);

            $aiResponseContent = $response->choices[0]->message->content;
            
            // Kiểm tra và lọc nội dung có đề cập đến các nền tảng bên ngoài
            $forbiddenPhrases = [
                'nền tảng khác', 'nền tảng gia sư', 'tìm kiếm trên', 'tìm kiếm online',
                'các nền tảng', 'nền tảng phổ biến', 'website khác', 'dịch vụ khác'
            ];
            
            $containsForbiddenPhrase = false;
            foreach ($forbiddenPhrases as $phrase) {
                if (stripos($aiResponseContent, $phrase) !== false) {
                    $containsForbiddenPhrase = true;
                    break;
                }
            }
            
            if ($containsForbiddenPhrase) {
                // Thay thế bằng phản hồi an toàn
                $aiResponseContent = "Xin chào! Tôi là trợ lý tìm gia sư của hệ thống. Tôi có thể giúp bạn tìm gia sư phù hợp với nhu cầu của bạn trong hệ thống của chúng tôi. Vui lòng cho tôi biết:
                
                1. Môn học bạn cần học
                2. Cấp học/lớp của bạn
                3. Mức học phí mong muốn
                
                Sau khi có đủ thông tin, tôi sẽ giúp bạn tìm gia sư phù hợp. Bạn có thể bấm nút \"Tổng kết\" để xem danh sách gia sư được đề xuất.";
                
                Log::warning('AI response contained forbidden phrases. Using safe response instead.', [
                    'original_response' => $response->choices[0]->message->content
                ]);
            }

            $aiMessage = new AIMessage([
                'role' => 'assistant',
                'content' => $aiResponseContent
            ]);
            $conversation->messages()->save($aiMessage);

            return response()->json([
                'message' => $aiMessage->content
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

    private function generateSummary($conversation)
    {
        try {
            $messages = $conversation->messages()
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($message) {
                    return [
                        'role' => $message->role,
                        'content' => $message->content
                    ];
                })
                ->toArray();

            $systemMessage = [
                'role' => 'system',
                'content' => 'Hãy phân tích cuộc hội thoại và tóm tắt các thông tin quan trọng về nhu cầu học tập của học sinh, bao gồm: môn học cần hỗ trợ, cấp học, mục tiêu học tập, và các yêu cầu đặc biệt nếu có.'
            ];

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4-turbo-preview',
                'messages' => array_merge([$systemMessage], $messages),
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);

            return $response->choices[0]->message->content;

        } catch (Exception $e) {
            Log::error('Error in generateSummary', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 'Có lỗi khi tạo tóm tắt: ' . $e->getMessage();
        }
    }

    private function getRecommendations($conversation)
    {
        try {
            Log::info('Starting getRecommendations');
            
            $messages = $conversation->messages()
                ->orderBy('created_at', 'asc')
                ->get();

            // Lấy tin nhắn của user
            $userMessages = $messages->where('role', 'user');
            
            if ($userMessages->isEmpty()) {
                Log::warning('No user messages found');
                return $this->getFallbackRecommendations();
            }
            
            // Kết hợp tất cả tin nhắn user để phân tích
            $combinedUserMessages = $userMessages->pluck('content')->join("\n");

            $systemMessage = [
                'role' => 'system',
                'content' => 'Phân tích yêu cầu tìm gia sư và trả về JSON. 

QUAN TRỌNG NHẤT: Xác định chính xác môn học mà người dùng cần. Ưu tiên môn học đầu tiên họ đề cập.

Hướng dẫn chi tiết:
1. MÔN HỌC LÀ TIÊU CHÍ QUAN TRỌNG NHẤT - Bất kỳ từ nào liên quan đến môn học (Toán, Lý, Hóa, Văn, Anh, Sinh...) phải được ưu tiên cao nhất
2. Nếu người dùng chỉ đề cập một môn học như "tìm gia sư Toán", subjects CHỈ NÊN CÓ ["Toán"] không thêm môn khác
3. Nếu người dùng đề cập nhiều môn, giữ đúng thứ tự ưu tiên mà họ nhắc đến
4. Không thêm môn học nào mà người dùng không đề cập đến
5. Nếu không đề cập môn cụ thể, để trống mảng subjects

{
    "subjects": ["Toán"], // CHỈ liệt kê môn học người dùng đề cập, không thêm môn khác
    "class_levels": ["Lớp 10", "Lớp 11", ...],
    "teaching_method": "online/offline/both",
    "max_price": 500000,
    "location": "Hà Nội",
    "requirements": "Yêu cầu thêm"
}'
            ];

            try {
                $response = OpenAI::chat()->create([
                    'model' => 'gpt-4-turbo-preview',
                    'messages' => [
                        $systemMessage,
                        ['role' => 'user', 'content' => $combinedUserMessages]
                    ],
                    'temperature' => 0.3,
                    'response_format' => ['type' => 'json_object']
                ]);

                $preferences = json_decode($response->choices[0]->message->content, true);
                Log::info('Parsed preferences', ['preferences' => $preferences]);
                
                if (empty($preferences) || json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Failed to parse JSON from AI response', [
                        'response' => $response->choices[0]->message->content,
                        'json_error' => json_last_error_msg()
                    ]);
                    return $this->getFallbackRecommendations();
                }
                
            } catch (Exception $e) {
                Log::error('Failed to get AI analysis', ['error' => $e->getMessage()]);
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
                // Nới lỏng tiêu chí tìm kiếm: chỉ giữ các tiêu chí cơ bản
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
                    // Đây là trường hợp có subjects nhưng không tìm thấy gia sư
                    // Tìm kiếm gần đúng với môn học
                    $similarSubjects = Subject::whereIn('name', $preferences['subjects'])
                        ->orWhere(function($q) use ($preferences) {
                            foreach($preferences['subjects'] as $subject) {
                                $q->orWhere('name', 'like', '%' . $subject . '%');
                            }
                        })
                        ->pluck('name')
                        ->toArray();
                    
                    if (!empty($similarSubjects)) {
                        $query = Tutor::with(['user', 'subjects', 'classLevels', 'reviews'])
                            ->where('status', 'active')
                            ->where('is_verified', true)
                            ->whereHas('subjects', function ($q) use ($similarSubjects) {
                                $q->whereIn('name', $similarSubjects);
                            });
                        
                        $tutors = $query->get();
                    }
                    
                    if ($tutors->isEmpty()) {
                        return $this->getFallbackRecommendationsWithSubjects($preferences['subjects']);
                    }
                }
            }
            
            $recommendations = [];
            foreach ($tutors as $tutor) {
                $score = $this->calculateMatchingScore($tutor, $preferences);
                // Giảm ngưỡng matching score xuống 0.1 để có nhiều kết quả hơn
                if ($score >= 0.1) {
                    $recommendations[] = [
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

        } catch (Exception $e) {
            Log::error('Error in getRecommendations', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
            'subjects' => 0.5, // Tăng trọng số cho môn học
            'class_levels' => 0.15,
            'teaching_method' => 0.1,
            'price' => 0.1,
            'location' => 0.05,
            'experience' => 0.1
        ];

        // Môn học - ưu tiên khớp chính xác môn học
        if (isset($preferences['subjects']) && !empty($preferences['subjects'])) {
            $tutorSubjects = $tutor->subjects->pluck('name')->toArray();
            $matchingSubjects = array_intersect($tutorSubjects, $preferences['subjects']);
            $totalSubjects = count($preferences['subjects']);
            
            if ($totalSubjects > 0) {
                // Nếu gia sư dạy tất cả môn học yêu cầu, điểm tối đa
                // Nếu không, điểm dựa trên tỷ lệ khớp
                $subjectScore = count($matchingSubjects) / $totalSubjects;
                
                // Nếu không có môn nào khớp, điểm = 0
                if (count($matchingSubjects) == 0) {
                    $subjectScore = 0;
                }
                
                $score += $weights['subjects'] * $subjectScore;
            } else {
                $score += $weights['subjects']; // Nếu không có yêu cầu môn học cụ thể
            }
        } else {
            $score += $weights['subjects']; // Nếu không có yêu cầu môn học
        }

        // Nếu điểm môn học bằng 0, trả về điểm rất thấp để loại bỏ gia sư này
        if (isset($preferences['subjects']) && !empty($preferences['subjects']) && 
            $score <= 0.01) {
            return 0.01; // Điểm rất thấp, gần như loại bỏ
        }

        // Cấp học
        if (isset($preferences['class_levels']) && !empty($preferences['class_levels'])) {
            $matchingLevels = $tutor->classLevels->whereIn('name', $preferences['class_levels'])->count();
            $totalLevels = count($preferences['class_levels']);
            if ($totalLevels > 0) {
                $score += $weights['class_levels'] * ($matchingLevels / $totalLevels);
            } else {
                $score += $weights['class_levels']; // Nếu không có yêu cầu cấp học cụ thể
            }
        } else {
            $score += $weights['class_levels']; // Nếu không có yêu cầu cấp học
        }

        // Phương pháp giảng dạy
        if (isset($preferences['teaching_method']) && !empty($preferences['teaching_method'])) {
            if ($tutor->teaching_method === $preferences['teaching_method'] || 
                $tutor->teaching_method === 'both') {
                $score += $weights['teaching_method'];
            }
        } else {
            $score += $weights['teaching_method']; // Nếu không có yêu cầu phương pháp giảng dạy
        }

        // Học phí
        if (isset($preferences['max_price']) && $preferences['max_price'] > 0) {
            $priceScore = 1 - ($tutor->hourly_rate / $preferences['max_price']);
            $score += $weights['price'] * max(0, $priceScore);
        } else {
            $score += $weights['price']; // Nếu không có yêu cầu học phí
        }

        // Địa điểm
        if (isset($preferences['location']) && !empty($preferences['location'])) {
            if (str_contains(strtolower($tutor->teaching_location), strtolower($preferences['location']))) {
                $score += $weights['location'];
            }
        } else {
            $score += $weights['location']; // Nếu không có yêu cầu địa điểm
        }

        // Kinh nghiệm
        $score += $weights['experience'] * min(1, ($tutor->experience_years ?? 0) / 5);

        return round($score, 2); // Làm tròn đến 2 chữ số thập phân
    }

    private function generateRecommendationReason($tutor, $preferences)
    {
        $reasons = [];
        
        // Môn học phù hợp
        $matchingSubjects = $tutor->subjects->whereIn('name', $preferences['subjects'] ?? [])->pluck('name')->toArray();
        if (!empty($matchingSubjects)) {
            $reasons[] = "Chuyên dạy các môn " . implode(', ', $matchingSubjects);
        }

        // Cấp học
        $matchingLevels = $tutor->classLevels->whereIn('name', $preferences['class_levels'] ?? [])->pluck('name')->toArray();
        if (!empty($matchingLevels)) {
            $reasons[] = "Có kinh nghiệm giảng dạy " . implode(', ', $matchingLevels);
        }

        // Kinh nghiệm
        if ($tutor->experience_years > 0) {
            $reasons[] = "Có " . $tutor->experience_years . " năm kinh nghiệm giảng dạy";
        }

        // Học phí
        if (isset($preferences['max_price']) && $tutor->hourly_rate <= $preferences['max_price']) {
            $reasons[] = "Mức học phí phù hợp với ngân sách";
        }

        // Đánh giá
        if ($tutor->reviews->count() > 0) {
            $rating = number_format($tutor->reviews->avg('rating'), 1);
            $reasons[] = "Được đánh giá " . $rating . "/5.0 từ " . $tutor->reviews->count() . " học viên";
        }

        return implode(". ", $reasons);
    }

    private function getFallbackRecommendationsWithSubjects($subjects)
    {
        // Import Subject model nếu chưa có
        if (!class_exists('App\Models\Subject')) {
            class_alias('App\Models\Subject', 'Subject');
        }
        
        // Tìm IDs của các môn học
        $subjectIds = Subject::whereIn('name', $subjects)
            ->orWhere(function($q) use ($subjects) {
                foreach($subjects as $subject) {
                    $q->orWhere('name', 'like', '%' . $subject . '%');
                }
            })
            ->pluck('id')
            ->toArray();
            
        // Log để debug
        Log::info('Fallback with subjects', [
            'requested_subjects' => $subjects,
            'found_subject_ids' => $subjectIds
        ]);
        
        // Nếu không tìm thấy môn học nào phù hợp
        if (empty($subjectIds)) {
            return $this->getFallbackRecommendations();
        }
        
        // Tìm gia sư dạy các môn học này
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
            
        // Nếu vẫn không tìm thấy, sử dụng fallback thông thường
        if ($tutors->isEmpty()) {
            return $this->getFallbackRecommendations();
        }
            
        $recommendations = [];
        foreach ($tutors as $tutor) {
            $matchingSubjects = $tutor->subjects->whereIn('id', $subjectIds)->pluck('name')->toArray();
            
            $recommendations[] = [
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

