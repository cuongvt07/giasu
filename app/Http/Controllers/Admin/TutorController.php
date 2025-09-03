<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\Subject;
use App\Models\ClassLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TutorController extends Controller
{
    public function index(Request $request)
    {
        $query = Tutor::with(['user', 'subjects', 'classLevels']);
        
        // Tìm kiếm theo tên gia sư
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }
        
        $tutors = $query->latest()->paginate(10);
        return view('admin.tutors.index', compact('tutors'));
    }

    /**
     * Hiển thị form tạo mới gia sư
     */
    public function create()
    {
        $users = User::whereDoesntHave('tutor')->get();
        $subjects = Subject::all();
        $classLevels = ClassLevel::all();
        return view('admin.tutors.create', compact('users', 'subjects', 'classLevels'));
    }
    
    /**
     * Lưu gia sư mới vào database
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id', 'unique:tutors,user_id'],
            'education_level' => ['required', 'string', 'max:255'],
            'university' => ['required', 'string', 'max:255'],
            'major' => ['required', 'string', 'max:255'],
            'teaching_experience' => ['required', 'numeric', 'min:0'],
            'bio' => ['required', 'string'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*' => ['exists:subjects,id'],
            'class_levels' => ['required', 'array', 'min:1'],
            'class_levels.*' => ['exists:class_levels,id'],
            'status' => ['required', 'in:pending,active,inactive'],
        ], [
            'user_id.required' => 'ID người dùng không được bỏ trống',
            'user_id.exists' => 'Người dùng không tồn tại',
            'user_id.unique' => 'Người dùng này đã là gia sư',
            'education_level.required' => 'Trình độ học vấn không được bỏ trống',
            'education_level.max' => 'Trình độ học vấn không được vượt quá 255 ký tự',
            'university.required' => 'Trường đại học không được bỏ trống',
            'university.max' => 'Tên trường đại học không được vượt quá 255 ký tự',
            'major.required' => 'Chuyên ngành không được bỏ trống',
            'major.max' => 'Chuyên ngành không được vượt quá 255 ký tự',
            'teaching_experience.required' => 'Kinh nghiệm giảng dạy không được bỏ trống',
            'teaching_experience.numeric' => 'Kinh nghiệm giảng dạy phải là một số',
            'teaching_experience.min' => 'Kinh nghiệm giảng dạy phải lớn hơn hoặc bằng 0',
            'bio.required' => 'Giới thiệu bản thân không được bỏ trống',
            'hourly_rate.required' => 'Giá theo giờ không được bỏ trống',
            'hourly_rate.numeric' => 'Giá theo giờ phải là một số',
            'hourly_rate.min' => 'Giá theo giờ phải lớn hơn hoặc bằng 0',
            'subjects.required' => 'Bạn phải chọn ít nhất một môn học',
            'subjects.min' => 'Bạn phải chọn ít nhất một môn học',
            'subjects.*.exists' => 'Môn học đã chọn không hợp lệ',
            'class_levels.required' => 'Bạn phải chọn ít nhất một cấp học',
            'class_levels.min' => 'Bạn phải chọn ít nhất một cấp học',
            'class_levels.*.exists' => 'Cấp học đã chọn không hợp lệ',
            'status.required' => 'Trạng thái không được bỏ trống',
            'status.in' => 'Trạng thái không hợp lệ',
        ]);

        try {
            // Chuyển đổi teaching_experience thành text nếu cần
            $teachingExperience = $request->teaching_experience . ' năm kinh nghiệm';
            
            $tutor = Tutor::create([
                'user_id' => $request->user_id,
                'education_level' => $request->education_level,
                'university' => $request->university,
                'major' => $request->major,
                'teaching_experience' => $teachingExperience,
                'bio' => $request->bio,
                'hourly_rate' => $request->hourly_rate,
                'status' => $request->status,
                'is_verified' => $request->status === 'active',
            ]);

            // Cập nhật môn học
            $tutor->subjects()->attach($request->subjects);

            // Cập nhật cấp học
            $tutor->classLevels()->attach($request->class_levels);

            return redirect()->route('admin.tutors.index')
                ->with('success', 'Gia sư mới đã được tạo thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo gia sư mới:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Tutor $tutor)
    {
        $tutor->load(['user', 'subjects', 'classLevels', 'bookings']);
        return view('admin.tutors.show', compact('tutor'));
    }

    public function edit(Tutor $tutor)
    {
        $subjects = Subject::all();
        $classLevels = ClassLevel::all();
        return view('admin.tutors.edit', compact('tutor', 'subjects', 'classLevels'));
    }

    public function update(Request $request, Tutor $tutor)
    {
        // Debug dữ liệu gửi đến
        Log::info('Admin tutor update request data:', [
            'tutor_id' => $tutor->id,
            'all_data' => $request->all(),
            'subjects' => $request->subjects,
            'class_levels' => $request->class_levels
        ]);

        $request->validate([
            'education_level' => ['required', 'string', 'max:255'],
            'university' => ['required', 'string', 'max:255'],
            'major' => ['required', 'string', 'max:255'],
            'teaching_experience' => ['required', 'string'],
            'bio' => ['required', 'string'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*' => ['exists:subjects,id'],
            'class_levels' => ['required', 'array', 'min:1'],
            'class_levels.*' => ['exists:class_levels,id'],
            'status' => ['required', 'in:pending,active,inactive'],
            'is_verified' => ['boolean'],
        ], [
            'education_level.required' => 'Trình độ học vấn không được bỏ trống',
            'education_level.max' => 'Trình độ học vấn không được vượt quá 255 ký tự',
            'university.required' => 'Trường đại học không được bỏ trống',
            'university.max' => 'Tên trường đại học không được vượt quá 255 ký tự',
            'major.required' => 'Chuyên ngành không được bỏ trống',
            'major.max' => 'Chuyên ngành không được vượt quá 255 ký tự',
            'teaching_experience.required' => 'Kinh nghiệm giảng dạy không được bỏ trống',
            'bio.required' => 'Giới thiệu bản thân không được bỏ trống',
            'hourly_rate.required' => 'Giá theo giờ không được bỏ trống',
            'hourly_rate.numeric' => 'Giá theo giờ phải là một số',
            'hourly_rate.min' => 'Giá theo giờ phải lớn hơn hoặc bằng 0',
            'subjects.required' => 'Bạn phải chọn ít nhất một môn học',
            'subjects.min' => 'Bạn phải chọn ít nhất một môn học',
            'subjects.*.exists' => 'Môn học đã chọn không hợp lệ',
            'class_levels.required' => 'Bạn phải chọn ít nhất một cấp học',
            'class_levels.min' => 'Bạn phải chọn ít nhất một cấp học',
            'class_levels.*.exists' => 'Cấp học đã chọn không hợp lệ',
            'status.required' => 'Trạng thái không được bỏ trống',
            'status.in' => 'Trạng thái không hợp lệ',
            'is_verified.boolean' => 'Trạng thái xác minh không hợp lệ',
        ]);

        try {
            $tutor->update([
                'education_level' => $request->education_level,
                'university' => $request->university,
                'major' => $request->major,
                'teaching_experience' => $request->teaching_experience,
                'bio' => $request->bio,
                'hourly_rate' => $request->hourly_rate,
                'status' => $request->status,
                'is_verified' => $request->is_verified ?? $tutor->is_verified,
            ]);

            // Cập nhật môn học
            $tutor->subjects()->sync($request->subjects);

            // Cập nhật cấp học
            $tutor->classLevels()->sync($request->class_levels);

            return redirect()->route('admin.tutors.index')
                ->with('success', 'Thông tin gia sư đã được cập nhật.');
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật gia sư:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy(Tutor $tutor)
    {
        // Kiểm tra xem gia sư có đang có lịch dạy không
        if ($tutor->bookings()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return back()->with('error', 'Không thể xóa gia sư đang có lịch dạy.');
        }

        // Xóa các liên kết với môn học và cấp học
        $tutor->subjects()->detach();
        $tutor->classLevels()->detach();

        // Xóa gia sư
        $tutor->delete();

        return redirect()->route('admin.tutors.index')
            ->with('success', 'Gia sư đã được xóa thành công.');
    }

    /**
     * Phê duyệt gia sư
     */
    public function approve(Tutor $tutor)
    {
        try {
            if ($tutor->status !== 'pending') {
                return back()->with('error', 'Gia sư này không ở trạng thái chờ duyệt.');
            }

            // Cập nhật trạng thái thành active và xác minh
            $tutor->update([
                'status' => 'active',
                'is_verified' => true
            ]);

            // Gửi thông báo cho gia sư (có thể thêm logic gửi email ở đây)

            return redirect()->route('admin.tutors.index')
                ->with('success', 'Gia sư đã được phê duyệt thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi khi phê duyệt gia sư:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Có lỗi xảy ra khi phê duyệt: ' . $e->getMessage());
        }
    }
} 