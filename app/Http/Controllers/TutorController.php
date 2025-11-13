<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\Subject;
use App\Models\ClassLevel;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TutorController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        // Truy vấn ban đầu
        $query = Tutor::query();
        
        // Thêm debug
        Log::info('TutorController@index - Số lượng tutor trước khi lọc: ' . $query->count());
        
        // Lọc theo môn học
        if ($request->subject_id) {
            $query->whereHas('subjects', function ($q) use ($request) {
                $q->where('subjects.id', $request->subject_id);
            });
            Log::info('TutorController@index - Lọc theo subject_id: ' . $request->subject_id);
            Log::info('TutorController@index - Số lượng sau khi lọc theo môn học: ' . $query->count());
        }
        
        // Lọc theo cấp học
        if ($request->class_level_id) {
            $query->whereHas('classLevels', function ($q) use ($request) {
                $q->where('class_levels.id', $request->class_level_id);
            });
            Log::info('TutorController@index - Lọc theo class_level_id: ' . $request->class_level_id);
            Log::info('TutorController@index - Số lượng sau khi lọc theo cấp học: ' . $query->count());
        }
        
        // Lọc theo khoảng giá
        if ($request->min_price) {
            $query->where('hourly_rate', '>=', $request->min_price);
            Log::info('TutorController@index - Lọc theo min_price: ' . $request->min_price);
            Log::info('TutorController@index - Số lượng sau khi lọc theo giá tối thiểu: ' . $query->count());
        }
        
        if ($request->max_price) {
            $query->where('hourly_rate', '<=', $request->max_price);
            Log::info('TutorController@index - Lọc theo max_price: ' . $request->max_price);
            Log::info('TutorController@index - Số lượng sau khi lọc theo giá tối đa: ' . $query->count());
        }
        
        // Lọc theo đánh giá
        if ($request->rating) {
            $query->where('rating', '>=', $request->rating);
            Log::info('TutorController@index - Lọc theo rating: ' . $request->rating);
            Log::info('TutorController@index - Số lượng sau khi lọc theo đánh giá: ' . $query->count());
        }
        
        // Chỉ lấy gia sư đã được kích hoạt và xác minh
        $query->where('status', '=', 'active')
              ->where('is_verified', true);
        
        Log::info('TutorController@index - Số lượng sau khi lọc theo trạng thái và xác minh: ' . $query->count());
        Log::info('TutorController@index - Active tutors count: ' . Tutor::where('status', 'active')->count());
        Log::info('TutorController@index - Verified tutors count: ' . Tutor::where('is_verified', true)->count());
        
        // Sắp xếp
        if ($request->sort_by) {
            // Nếu có tùy chọn sắp xếp, áp dụng sắp xếp theo tùy chọn
            $query->orderBy($request->sort_by, $request->sort_order ?? 'desc');
        } else {
            // Nếu không có tùy chọn sắp xếp, sử dụng sắp xếp mặc định, ở đây là theo ID
            $query->orderBy('id', 'desc');
        }
        
        // Phân trang
        $tutors = $query->with(['user', 'subjects'])->paginate(12);
        
        Log::info('TutorController@index - Số lượng tutors trả về: ' . $tutors->total());
        
        return view('pages.tutors.index', [
            'tutors' => $tutors,
            'subjects' => Subject::where('is_active', true)->get(),
            'classLevels' => ClassLevel::where('is_active', true)->get()
        ]);
    }

    public function show(Tutor $tutor)
    {
        $reviews = $tutor->reviews()->with(['student', 'booking.subject'])->latest()->paginate(5);
        $reviewsCount = $tutor->reviews()->count();
        
        // Lấy lịch dạy của gia sư
        $schedules = $tutor->schedules()->get();
        
        // Lấy ca dạy của gia sư (cả ca dạy cụ thể và lịch lặp lại)
        $availabilities = $tutor->availabilities()
            ->where(function ($query) {
                $query->where('start_time', '>', now())
                    ->orWhere('is_recurring', true);
            })
            ->where('status', 'active')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
        
        return view('pages.tutors.show', [
            'tutor' => $tutor->load(['subjects', 'classLevels']),
            'reviews' => $reviews,
            'reviews_count' => $reviewsCount,
            'schedules' => $schedules,
            'availabilities' => $availabilities
        ]);
    }

    public function create()
    {
        // Nếu người dùng chưa đăng nhập, chuyển hướng đến trang đăng ký
        if (!Auth::check()) {
            return redirect()->route('register');
        }
        
        // Nếu người dùng đã đăng nhập và đã là gia sư, chuyển hướng đến dashboard
        if (Auth::user()->tutor) {
            return redirect()->route('tutor.dashboard');
        }
        
        return view('pages.tutors.create', [
            'subjects' => Subject::where('is_active', true)->get(),
            'classLevels' => ClassLevel::where('is_active', true)->get()
        ]);
    }

    public function store(Request $request)
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để đăng ký làm gia sư.');
        }

        // Kiểm tra nếu đã là gia sư
        if (Auth::user()->tutor) {
            return redirect()->route('tutor.dashboard')
                ->with('error', 'Bạn đã đăng ký làm gia sư.');
        }

        $validated = $request->validate([
            'education_level' => 'required|string|max:255',
            'university' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'teaching_experience' => 'required|string',
            'bio' => 'required|string',
            'avatar' => 'nullable|image|max:1024',
            'certification_files' => 'nullable|array',
            'certification_files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'hourly_rate' => 'required|numeric|min:0',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'class_levels' => 'required|array|min:1',
            'class_levels.*' => 'exists:class_levels,id',
            'subject_prices' => 'nullable|array',
            'subject_prices.*' => 'nullable|array',
            'subject_prices.*.price' => 'nullable|numeric|min:0',
            'subject_prices.*.experience' => 'nullable|string',
        ], [
            'education_level.required' => 'Trình độ học vấn không được bỏ trống',
            'education_level.max' => 'Trình độ học vấn không được vượt quá 255 ký tự',
            'teaching_experience.required' => 'Kinh nghiệm giảng dạy không được bỏ trống',
            'bio.required' => 'Giới thiệu bản thân không được bỏ trống',
            'avatar.image' => 'Ảnh đại diện phải là một hình ảnh',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 1MB',
            'hourly_rate.required' => 'Giá theo giờ không được bỏ trống',
            'hourly_rate.numeric' => 'Giá theo giờ phải là một số',
            'hourly_rate.min' => 'Giá theo giờ phải lớn hơn hoặc bằng 0',
            'subjects.required' => 'Bạn phải chọn ít nhất một môn học',
            'subjects.min' => 'Bạn phải chọn ít nhất một môn học',
            'subjects.*.exists' => 'Môn học đã chọn không hợp lệ',
            'class_levels.required' => 'Bạn phải chọn ít nhất một cấp học',
            'class_levels.min' => 'Bạn phải chọn ít nhất một cấp học',
            'class_levels.*.exists' => 'Cấp học đã chọn không hợp lệ',
            'subject_prices.*.price.numeric' => 'Giá theo giờ cho môn học phải là một số',
            'subject_prices.*.price.min' => 'Giá theo giờ cho môn học phải lớn hơn hoặc bằng 0',
        ]);

        try {
            $tutor = new Tutor($validated);
            $tutor->user_id = Auth::id();
            $tutor->status = 'pending';

            if ($request->hasFile('avatar')) {
                $tutor->avatar = $request->file('avatar')->store('avatars', 'public');
            }

            if ($request->hasFile('certification_files')) {
                $paths = [];
                foreach ($request->file('certification_files') as $file) {
                    $paths[] = $file->store('certifications', 'public');
                }
                $tutor->certification_files = $paths;
            }

            $tutor->save();

            // Xử lý dữ liệu môn học và giá cho từng môn
            if ($request->has('subjects')) {
                $syncData = [];
                
                // Xử lý dữ liệu giá cho từng môn học
                foreach ($request->subjects as $subjectId) {
                    $pricePerHour = $tutor->hourly_rate; // Giá mặc định
                    $experienceDetails = null;
                    
                    // Nếu có thông tin giá cho môn học này
                    if (isset($request->subject_prices[$subjectId])) {
                        // Nếu có thiết lập giá cụ thể, sử dụng giá đó
                        if (isset($request->subject_prices[$subjectId]['price']) && 
                            is_numeric($request->subject_prices[$subjectId]['price']) && 
                            $request->subject_prices[$subjectId]['price'] > 0) {
                            $pricePerHour = $request->subject_prices[$subjectId]['price'];
                        }
                        
                        // Lưu chi tiết kinh nghiệm
                        if (isset($request->subject_prices[$subjectId]['experience'])) {
                            $experienceDetails = $request->subject_prices[$subjectId]['experience'];
                        }
                    }
                    
                    $syncData[$subjectId] = [
                        'price_per_hour' => $pricePerHour,
                        'experience_details' => $experienceDetails
                    ];
                }
                
                $tutor->subjects()->attach($syncData);
            }

            $tutor->classLevels()->attach($request->class_levels);

            return redirect()->route('tutors.pending', $tutor)
                ->with('success', 'Hồ sơ gia sư của bạn đã được tạo và đang chờ xét duyệt.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.');
        }
    }

    public function edit(Tutor $tutor)
    {
        $this->authorize('update', $tutor);

        return view('pages.tutors.edit', [
            'tutor' => $tutor->load(['subjects', 'classLevels']),
            'subjects' => Subject::where('is_active', true)->get(),
            'classLevels' => ClassLevel::where('is_active', true)->get()
        ]);
    }

    public function update(Request $request, Tutor $tutor)
    {
        $this->authorize('update', $tutor);

        $validated = $request->validate([
            'education_level' => 'required|string|max:255',
            'university' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'teaching_experience' => 'required|string',
            'bio' => 'required|string',
            'avatar' => 'nullable|image|max:1024',
            'certification_files' => 'nullable|array',
            'certification_files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'hourly_rate' => 'required|numeric|min:0',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'class_levels' => 'required|array|min:1',
            'class_levels.*' => 'exists:class_levels,id',
            'subject_prices' => 'nullable|array',
            'subject_prices.*' => 'nullable|array',
            'subject_prices.*.price' => 'nullable|numeric|min:0',
            'subject_prices.*.experience' => 'nullable|string',
        ], [
            'education_level.required' => 'Trình độ học vấn không được bỏ trống',
            'education_level.max' => 'Trình độ học vấn không được vượt quá 255 ký tự',
            'teaching_experience.required' => 'Kinh nghiệm giảng dạy không được bỏ trống',
            'bio.required' => 'Giới thiệu bản thân không được bỏ trống',
            'avatar.image' => 'Ảnh đại diện phải là một hình ảnh',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 1MB',
            'hourly_rate.required' => 'Giá theo giờ không được bỏ trống',
            'hourly_rate.numeric' => 'Giá theo giờ phải là một số',
            'hourly_rate.min' => 'Giá theo giờ phải lớn hơn hoặc bằng 0',
            'subjects.required' => 'Bạn phải chọn ít nhất một môn học',
            'subjects.min' => 'Bạn phải chọn ít nhất một môn học',
            'subjects.*.exists' => 'Môn học đã chọn không hợp lệ',
            'class_levels.required' => 'Bạn phải chọn ít nhất một cấp học',
            'class_levels.min' => 'Bạn phải chọn ít nhất một cấp học',
            'class_levels.*.exists' => 'Cấp học đã chọn không hợp lệ',
            'subject_prices.*.price.numeric' => 'Giá theo giờ cho môn học phải là một số',
            'subject_prices.*.price.min' => 'Giá theo giờ cho môn học phải lớn hơn hoặc bằng 0',
        ]);

        if ($request->hasFile('avatar')) {
            if ($tutor->avatar) {
                Storage::disk('public')->delete($tutor->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->hasFile('certification_files')) {
            if ($tutor->certification_files) {
                foreach ($tutor->certification_files as $file) {
                    Storage::disk('public')->delete($file);
                }
            }
            $paths = [];
            foreach ($request->file('certification_files') as $file) {
                $paths[] = $file->store('certifications', 'public');
            }
            $validated['certification_files'] = $paths;
        }

        $tutor->update($validated);

        // Xử lý dữ liệu môn học và giá cho từng môn
        if ($request->has('subjects')) {
            $syncData = [];
            
            // Xử lý dữ liệu giá cho từng môn học
            foreach ($request->subjects as $subjectId) {
                $pricePerHour = $tutor->hourly_rate; // Giá mặc định
                $experienceDetails = null;
                
                // Nếu có thông tin giá cho môn học này
                if (isset($request->subject_prices[$subjectId])) {
                    // Nếu có thiết lập giá cụ thể, sử dụng giá đó
                    if (isset($request->subject_prices[$subjectId]['price']) && 
                        is_numeric($request->subject_prices[$subjectId]['price']) && 
                        $request->subject_prices[$subjectId]['price'] > 0) {
                        $pricePerHour = $request->subject_prices[$subjectId]['price'];
                    }
                    
                    // Lưu chi tiết kinh nghiệm
                    if (isset($request->subject_prices[$subjectId]['experience'])) {
                        $experienceDetails = $request->subject_prices[$subjectId]['experience'];
                    }
                }
                
                $syncData[$subjectId] = [
                    'price_per_hour' => $pricePerHour,
                    'experience_details' => $experienceDetails
                ];
            }
            
            $tutor->subjects()->sync($syncData);
        }

        $tutor->classLevels()->sync($request->class_levels);

        return redirect()->route('tutors.show', $tutor)
            ->with('success', 'Hồ sơ gia sư đã được cập nhật thành công.');
    }

    public function book(Request $request, Tutor $tutor)
    {
        // TODO: Implement booking logic
        return back()->with('success', 'Yêu cầu Đặt Lịch Ca Dạy GS của bạn đã được gửi đến gia sư.');
    }

    public function bookings()
    {
        // TODO: Implement bookings list
        return view('pages.tutors.bookings');
    }

    /**
     * Hiển thị trang đang chờ xét duyệt
     */
    public function pending(Tutor $tutor)
    {
        $this->authorize('update', $tutor);
        
        if ($tutor->status !== 'pending') {
            return redirect()->route('tutors.show', $tutor);
        }
        
        return view('pages.tutors.pending', compact('tutor'));
    }

    /**
     * Hiển thị trang giới thiệu về việc đăng ký làm gia sư
     */
    public function register()
    {
        return view('pages.tutors.register');
    }

    public function postJob(Request $request)
    {
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
            ->groupBy(
                'tutor_posts.id',
                'tutor_posts.user_id',
                'tutor_posts.subject_id',
                'tutor_posts.class_level_id',
                'tutor_posts.goal',
                'tutor_posts.description',
                'tutor_posts.budget_min',
                'tutor_posts.budget_max',
                'tutor_posts.budget_unit',
                'tutor_posts.sessions_per_week',
                'tutor_posts.session_length_min',
                'tutor_posts.schedule_notes',
                'tutor_posts.mode',
                'tutor_posts.address_line',
                'tutor_posts.student_count',
                'tutor_posts.student_age',
                'tutor_posts.special_notes',
                'tutor_posts.qualifications',
                'tutor_posts.min_experience_yr',
                'tutor_posts.contact_phone',
                'tutor_posts.contact_email',
                'tutor_posts.deadline_at',
                'tutor_posts.status',
                'tutor_posts.published_at',
                'tutor_posts.created_at',
                'tutor_posts.updated_at',
                'subjects.name',
                'class_levels.name'
            );

        // Filter môn học
        if ($request->filled('subject')) {
            $query->where('subjects.name', $request->subject); 
        }

        // Filter hình thức học
        if ($request->filled('mode')) {
            $query->where('tutor_posts.mode', $request->mode);
        }

        // Filter ngân sách
        if ($request->filled('budget_min')) {
            $query->where('tutor_posts.budget_min', '>=', $request->budget_min);
        }
        if ($request->filled('budget_max')) {
            $query->where('tutor_posts.budget_max', '<=', $request->budget_max);
        }

        // Sort dữ liệu
        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'latest':
                    $query->orderByDesc('tutor_posts.published_at');
                    break;
                case 'budget':
                    $query->orderByDesc('tutor_posts.budget_max');
                    break;
                case 'deadline':
                    $query->orderBy('tutor_posts.deadline_at', 'asc');
                    break;
            }
        } else {
            $query->orderByDesc('tutor_posts.published_at');
        }

        $dataJobs = $query->paginate(10)->withQueryString();

        // Convert chuỗi "1,2,3" thành array [1,2,3]
        $dataJobs->getCollection()->transform(function ($post) {
            $post->applied_tutor_ids = $post->applied_tutor_ids
                ? array_map('intval', explode(',', $post->applied_tutor_ids))
                : [];
            return $post;
        });

        return view('pages.jobs-tutor.index', [
            'dataJobs'    => $dataJobs,
            'subjects'    => Subject::where('is_active', true)->get(),
            'classLevels' => ClassLevel::where('is_active', true)->get(),
        ]);
    }

    public function applyJob(Request $request)
    {
        try {
            // Kiểm tra đăng nhập
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để ứng tuyển.'], 401);
            }

            // Kiểm tra có phải gia sư không
            $tutor = Auth::user()->tutor;
            if (!$tutor) {
                return response()->json(['success' => false, 'message' => 'Chỉ tài khoản gia sư mới có thể ứng tuyển.'], 403);
            }

            // Validate
            $validator = \Validator::make($request->all(), [
                'job_id' => 'required|exists:tutor_posts,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $job = DB::table('tutor_posts')->where('id', $request->job_id)->first();
            if (!$job) {
                return response()->json(['success' => false, 'message' => 'Tin tuyển gia sư không tồn tại.'], 404);
            }

            // Kiểm tra đã ứng tuyển chưa
            $existing = DB::table('tutor_applications')
                ->where('tutor_post_id', $request->job_id)
                ->where('tutor_id', Auth::id())
                ->first();

            if ($existing) {
                return response()->json(['success' => false, 'message' => 'Bạn đã ứng tuyển bài này rồi!'], 409);
            }

            // Tạo đơn ứng tuyển
            DB::table('tutor_applications')->insert([
                'tutor_post_id' => $request->job_id,
                'tutor_id'      => Auth::id(),
                'status'        => 'pending',
                'note'          => $request->note ?? null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ứng tuyển thành công! Vui lòng chờ thông báo từ quản lý.'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error in applyJob', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'job_id' => $request->job_id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi ứng tuyển. Vui lòng thử lại sau.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
