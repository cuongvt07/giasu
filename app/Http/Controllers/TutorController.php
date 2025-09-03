<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\Subject;
use App\Models\ClassLevel;
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
        
        // Lấy lịch rảnh của gia sư (cả lịch rảnh cụ thể và lịch lặp lại)
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
        return back()->with('success', 'Yêu cầu đặt lịch của bạn đã được gửi đến gia sư.');
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
}
