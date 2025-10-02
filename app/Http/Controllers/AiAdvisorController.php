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
        $user = auth()->user();
        $tutorId = $user?->tutor?->user_id;
        
        return view('pages.ai-advisor.index', compact('conversation', 'recommendations', 'tutorId', 'user'));
    }

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
                    'status' => 'active'
                ]);
                $request->session()->put('conversation_id', $conversation->id);
            } else {
                $conversation = AIConversation::find($request->session()->get('conversation_id'));
                if (!$conversation) {
                    $conversation = AIConversation::create([
                        'user_id' => Auth::id(),
                        'status' => 'active'
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

            // ===== PHÁT HIỆN INTENT ĐỂ TRẢ LỜI PHÙ HỢP =====
            $userMessages = $conversation->messages()->where('role', 'user')->get();
            $combinedUserMessages = $userMessages->pluck('content')->join("\n");

            // Detect intent
            $intentPrompt = [
                'role' => 'system',
                'content' => 'Phân tích ý định người dùng từ tin nhắn. Trả về JSON: {"intent":"<tutor|job|support|academic_question|general_info|other>"}.
                
                QUY TẮC:
                1) "academic_question" → Hỏi bài tập/học thuật (có từ "giải", "bài tập", "đáp án", "chứng minh", ký hiệu toán học, phương trình, công thức)
                2) "tutor" → Tìm gia sư (có từ "gia sư", "tìm người dạy", "dạy kèm", "học thêm")
                3) "job" → Tìm việc/lớp dạy (có từ "tuyển", "ứng tuyển", "có lớp", "lớp dạy", "tìm việc", "việc làm gia sư")
                4) "support" → Hỗ trợ (có từ "hotline", "liên hệ", "hướng dẫn", "hỗ trợ", "zalo", "facebook")
                5) "general_info" → Hỏi thông tin chung (ai là, bao nhiêu, khi nào, ở đâu)
                6) "other" → Chào hỏi, cảm ơn, hoặc không xác định
                
                OUTPUT: Chỉ JSON, không giải thích.'
            ];

            try {
                $intentResponse = OpenAI::chat()->create([
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        $intentPrompt,
                        ['role' => 'user', 'content' => $combinedUserMessages]
                    ],
                    'temperature' => 0.1,
                    'response_format' => ['type' => 'json_object']
                ]);

                $intentJson = json_decode($intentResponse->choices[0]->message->content, true);
                $intent = $intentJson['intent'] ?? 'other';
                Log::info('Detected intent in chat', ['intent' => $intent]);
            } catch (Exception $e) {
                Log::error('Failed to detect intent', ['error' => $e->getMessage()]);
                $intent = 'other';
            }

            // ===== TẠO PROMPT TRẢ LỜI NGẮN GỌN DỰA TRÊN INTENT =====
            $systemMessage = '';
            
            if ($intent === 'academic_question') {
                // Người dùng hỏi bài tập → Trả lời ngắn, gợi ý xem lời giải ở phần Kết Quả
                $systemMessage = [
                    'role' => 'system',
                    'content' => 'Bạn là trợ lý AI hỗ trợ học tập. Khi người dùng hỏi bài tập:
                    
                    ✅ PHẢI LÀM:
                    - Xác nhận đã nhận được câu hỏi một cách thân thiện
                    - Nhận diện môn học và dạng bài (VD: "Đây là phương trình bậc nhất môn Toán")
                    - Nói rằng bạn đã chuẩn bị lời giải chi tiết
                    - Gợi ý: "Bấm nút Tổng kết bên phải để xem lời giải từng bước và danh sách gia sư dạy [MÔN HỌC] giỏi nhé!"
                    
                    ❌ TUYỆT ĐỐI KHÔNG ĐƯỢC:
                    - Không giải chi tiết trong chat
                    - Không viết các bước giải
                    - Không đưa ra đáp án cụ thể
                    - Không liệt kê gia sư trong chat
                    
                    Ví dụ phản hồi tốt:
                    "Tôi thấy đây là phương trình bậc nhất một ẩn môn Toán. Tôi đã chuẩn bị lời giải chi tiết từng bước và gợi ý một số gia sư dạy Toán xuất sắc cho bạn. Vui lòng chờ tổng kết bên phải để xem nhé!"
                    
                    Trả lời tối đa 2-3 câu, ngắn gọn, thân thiện.'
                ];
                
            } else if ($intent === 'tutor') {
                // Người dùng tìm gia sư → Hỏi thêm thông tin, gợi ý xem danh sách
                $systemMessage = [
                    'role' => 'system',
                    'content' => 'Bạn là trợ lý tìm gia sư trong hệ thống. Khi người dùng muốn tìm gia sư:
                    
                    ✅ PHẢI LÀM:
                    - Xác nhận rõ ràng môn học họ cần (VD: "Bạn đang tìm gia sư dạy môn Toán, đúng không?")
                    - Nếu thiếu thông tin (môn học, cấp học, mức giá), hỏi thêm một cách tự nhiên
                    - Nếu đủ thông tin, nói rằng đã tìm thấy gia sư phù hợp
                    - Gợi ý: "Bấm nút Tổng kết để xem danh sách gia sư được đề xuất"
                    
                    ❌ TUYỆT ĐỐI KHÔNG ĐƯỢC:
                    - Không liệt kê danh sách gia sư trong chat
                    - Không đưa ra thông tin chi tiết về từng gia sư
                    - Không đề cập giá cụ thể của gia sư
                    - Không giới thiệu nền tảng khác
                    
                    Ví dụ phản hồi tốt:
                    User: "Tìm gia sư Toán lớp 10"
                    AI: "Bạn đang tìm gia sư dạy môn Toán cho lớp 10, đúng không? Tôi đã tìm thấy một số gia sư phù hợp trong hệ thống. Bạn có thể bấm nút Tổng kết để xem danh sách chi tiết nhé!"
                    
                    Trả lời tối đa 2-3 câu, ngắn gọn, thân thiện.'
                ];
                
            } else if ($intent === 'job') {
                // Người dùng tìm lớp dạy/việc làm → Gợi ý xem tin tuyển dụng
                $systemMessage = [
                    'role' => 'system',
                    'content' => 'Bạn là trợ lý hỗ trợ gia sư tìm lớp dạy. Khi người dùng tìm việc/lớp dạy:
                    
                    ✅ PHẢI LÀM:
                    - Xác nhận đã hiểu yêu cầu (môn học, khu vực, mức lương)
                    - Nói rằng đã tìm thấy các tin tuyển dụng phù hợp
                    - Gợi ý: "Bấm nút Tổng kết để xem các tin đăng chi tiết"
                    
                    ❌ TUYỆT ĐỐI KHÔNG ĐƯỢC:
                    - Không liệt kê tin tuyển dụng trong chat
                    - Không đưa thông tin chi tiết về lớp dạy
                    
                    Ví dụ phản hồi tốt:
                    "Tôi đã tìm thấy một số tin tuyển gia sư dạy Toán tại Hà Nội. Bấm nút Tổng kết để xem chi tiết về mức lương, số buổi/tuần và yêu cầu của phụ huynh nhé!"
                    
                    Trả lời tối đa 2 câu, ngắn gọn.'
                ];
                
            } else if ($intent === 'support') {
                // Người dùng cần hỗ trợ → Đưa thông tin liên hệ ngắn gọn
                $systemMessage = [
                    'role' => 'system',
                    'content' => 'Bạn là trợ lý hỗ trợ khách hàng. Khi người dùng cần hỗ trợ:
                    
                    ✅ PHẢI LÀM:
                    - Trả lời ngắn gọn câu hỏi hỗ trợ
                    - Đưa thông tin liên hệ: Hotline: 0988 123 456, Zalo: 0988 123 456
                    - Nếu cần thêm thông tin chi tiết, gợi ý bấm "Tổng kết"
                    
                    Ví dụ phản hồi tốt:
                    User: "Làm sao đăng ký gia sư?"
                    AI: "Bạn cần đăng nhập, vào trang Hồ sơ và điền thông tin gia sư. Nếu cần hỗ trợ thêm, liên hệ Hotline: 0988 123 456 hoặc Zalo: 0988 123 456 nhé!"
                    
                    Trả lời tối đa 2-3 câu.'
                ];
                
            } else {
                // Các trường hợp khác: chào hỏi, hỏi chung chung
                $systemMessage = [
                    'role' => 'system',
                    'content' => 'Bạn là trợ lý AI thân thiện của hệ thống tìm gia sư. 
                    
                    Khi người dùng:
                    - Chào hỏi → Chào lại thân thiện, giới thiệu bạn có thể giúp tìm gia sư, tìm việc, giải bài tập
                    - Cảm ơn → Trả lời lịch sự, hỏi còn cần gì khác không
                    - Hỏi chung chung → Trả lời ngắn gọn, gợi ý các dịch vụ của hệ thống
                    
                    Trả lời tối đa 2-3 câu, thân thiện, chuyên nghiệp.'
                ];
            }

            // ===== GỌI AI ĐỂ TRẢ LỜI NGẮN GỌN =====
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
                'temperature' => 0.7,
                'max_tokens' => 200  // Giới hạn độ dài để trả lời ngắn
            ]);

            $aiResponseContent = $response->choices[0]->message->content;
            
            // Lưu message AI
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

    private function getRecommendations($conversation)
    {
        try {
            Log::info('Starting getRecommendations');
            $conversation = AIConversation::find($conversation->id);
            $userMessages = $conversation->messages()->where('role', 'user')->get();

            if ($userMessages->isEmpty()) {
                return $this->getFallbackRecommendations();
            }
            
            $combinedUserMessages = $userMessages->pluck('content')->join("\n");

            // 1. Intent detection
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
            $intent = $intentJson['intent'] ?? 'tutor';
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

            // 3. Nếu intent là academic_question → trả lời học thuật + gợi ý gia sư
            if ($intent === 'academic_question') {
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
                    - Sử dụng ký hiệu toán học (nếu có) phù hợp.'
                ];

                $academicResponse = OpenAI::chat()->create([
                    'model' => 'gpt-4o',
                    'messages' => [
                        $academicPrompt,
                        ['role' => 'user', 'content' => $combinedUserMessages]
                    ],
                    'temperature' => 0.3,
                    'response_format' => ['type' => 'json_object']
                ]);

                $academicSolution = json_decode($academicResponse->choices[0]->message->content, true);

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

                // Để xuống dòng đúng trong HTML, bạn cần chuyển đổi ký tự "\n" thành thẻ <br>.
                // Sử dụng nl2br() để chuyển đổi, đồng thời dùng e() để escape HTML nếu cần bảo mật.
                // Nếu bạn trả về JSON cho frontend render HTML, hãy dùng nl2br() như sau:

                $reason = "📚 <b>Môn học</b>: {$academicSolution['subject']}\n\n" .
                          "📝 <b>Loại câu hỏi</b>: {$academicSolution['question_type']}\n\n" .
                          "🎯 <b>Phân tích</b>: {$academicSolution['analysis']}\n\n" .
                          "✏️ <b>Câu trả lời</b>:\n{$academicSolution['answer']}\n\n" .
                          "✅ <b>Kết quả cuối cùng</b>: {$academicSolution['final_answer']}\n\n" .
                          (isset($academicSolution['explanation']) ? "💡 <b>Giải thích thêm</b>: {$academicSolution['explanation']}" : "");

                // Chuyển \n thành <br>
                $reason = nl2br($reason);

                $results = [
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

                // ===== THÊM PHẦN NÀY: TÌM GIA SƯ DẠY MÔN HỌC ĐÓ =====
                $subject = $academicSolution['subject'] ?? null;
                
                if ($subject && $subject !== 'khác') {
                    Log::info('Finding tutors for subject', ['subject' => $subject]);
                    
                    // Tìm gia sư dạy môn học này
                    $tutors = Tutor::with(['user', 'subjects', 'classLevels', 'reviews'])
                        ->where('status', 'active')
                        ->where('is_verified', true)
                        ->whereHas('subjects', function ($q) use ($subject) {
                            $q->where('name', $subject);
                        })
                        ->get()
                        ->sortByDesc(function($tutor) {
                            return $tutor->reviews->avg('rating') ?? 5.0;
                        })
                        ->take(5); // Chỉ lấy top 5 gia sư
                    
                    if ($tutors->isNotEmpty()) {
                        Log::info('Found tutors', ['count' => $tutors->count()]);
                        
                        foreach ($tutors as $tutor) {
                            $results[] = [
                                'type' => 'tutor',
                                'id' => $tutor->id,
                                'name' => $tutor->user->name ?? 'Gia sư',
                                'avatar' => $tutor->avatar ? url(Storage::url($tutor->avatar)) : null,
                                'subjects' => $tutor->subjects->pluck('name')->toArray(),
                                'class_levels' => $tutor->classLevels->pluck('name')->toArray(),
                                'hourly_rate' => $tutor->hourly_rate,
                                'rating' => number_format($tutor->reviews->avg('rating') ?? 5.0, 1),
                                'review_count' => $tutor->reviews->count(),
                                'experience_years' => $tutor->experience_years,
                                'teaching_method' => $tutor->teaching_method,
                                'matching_score' => 0.95, // Điểm cao vì match đúng môn học
                                'reason' => sprintf(
                                    "Chuyên dạy môn %s. %s. Được đánh giá %s/5.0 từ %d học viên.",
                                    $subject,
                                    $tutor->experience_years > 0 ? "Có {$tutor->experience_years} năm kinh nghiệm" : "Gia sư nhiệt tình",
                                    number_format($tutor->reviews->avg('rating') ?? 5.0, 1),
                                    $tutor->reviews->count()
                                )
                            ];
                        }
                    } else {
                        Log::warning('No tutors found for subject', ['subject' => $subject]);
                    }
                }

                return $results;
            }

            // 4. Nếu intent là job → tìm tin đăng tuyển
            if ($intent === 'job') {
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

            // 5. Mặc định: tìm gia sư
            $tutorPrompt = [
                'role' => 'system',
                'content' => 'QUAN TRỌNG NHẤT: Xác định chính xác môn học mà người dùng cần. Ưu tiên môn học đầu tiên họ đề cập. 
                
                Hướng dẫn chi tiết: 
                1. MÔN HỌC LÀ TIÊU CHÍ QUAN TRỌNG NHẤT - Bất kỳ từ nào liên quan đến môn học (Toán, Lý, Hóa, Văn, Anh, Sinh...) phải được ưu tiên cao nhất 
                2. Nếu người dùng chỉ đề cập một môn học như "tìm gia sư Toán", subjects CHỈ NÊN CÓ ["Toán"] không thêm môn khác 
                3. Nếu người dùng đề cập nhiều môn, giữ đúng thứ tự ưu tiên mà họ nhắc đến 
                4. Không thêm môn học nào mà người dùng không đề cập đến 
                5. Nếu không đề cập môn cụ thể, để trống mảng subjects. 
                
                Trả về JSON: 
                {
                    "subjects": [], 
                    "class_levels": [], 
                    "teaching_method": "", 
                    "max_price": 0, 
                    "location": "", 
                    "requirements": ""
                }'
            ];

            $tutorResponse = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    $tutorPrompt,
                    ['role' => 'user', 'content' => $combinedUserMessages]
                ],
                'temperature' => 0.2,
                'response_format' => ['type' => 'json_object']
            ]);

            $preferences = json_decode($tutorResponse->choices[0]->message->content, true);
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

        } catch (\Throwable $e) {
            Log::error('Error in getRecommendations', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->getFallbackRecommendations();
        }
    }

    private function getFallbackRecommendations()
    {
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