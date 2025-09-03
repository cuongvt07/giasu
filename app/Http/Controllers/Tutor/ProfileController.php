<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Subject;

class ProfileController extends Controller
{
    public function edit()
    {
        $tutor = Auth::user()->tutor->load('subjects');
        
        return view('tutor.profile.edit', [
            'tutor' => $tutor
        ]);
    }

    public function update(Request $request)
    {
        $tutor = Auth::user()->tutor;
        
        $validated = $request->validate([
            'education_level' => 'required|string|max:255',
            'university' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'teaching_experience' => 'required|string',
            'bio' => 'required|string',
            'avatar' => 'nullable|image|max:1024',
            'hourly_rate' => 'required|numeric|min:0',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
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
            'subject_prices.*.price.numeric' => 'Giá theo giờ cho môn học phải là một số',
            'subject_prices.*.price.min' => 'Giá theo giờ cho môn học phải lớn hơn hoặc bằng 0',
        ]);

        // Cập nhật thông tin cơ bản
        $tutor->update([
            'education_level' => $validated['education_level'],
            'university' => $validated['university'],
            'major' => $validated['major'],
            'teaching_experience' => $validated['teaching_experience'],
            'bio' => $validated['bio'],
            'hourly_rate' => $validated['hourly_rate'],
        ]);

        // Xử lý cập nhật avatar
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($tutor->avatar && Storage::disk('public')->exists($tutor->avatar)) {
                Storage::disk('public')->delete($tutor->avatar);
            }
            
            // Lưu avatar mới với tên duy nhất
            $avatarName = 'avatars/' . $tutor->id . '_' . time() . '.' . $request->avatar->extension();
            $request->avatar->storeAs('', $avatarName, 'public');
            
            // Cập nhật đường dẫn avatar trong database
            $tutor->avatar = $avatarName;
            $tutor->save();
        }

        // Cập nhật môn học và giá cho từng môn học
        if ($request->has('subjects')) {
            $syncData = [];
            
            // Lấy danh sách môn học đã chọn
            $subjectIds = $request->subjects;
            
            // Xử lý dữ liệu giá cho từng môn học
            foreach ($subjectIds as $subjectId) {
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
            
            // Đồng bộ hóa dữ liệu môn học
            $tutor->subjects()->sync($syncData);

            // Lưu dữ liệu và đánh dấu quan hệ đã được tải lại
            $tutor->load('subjects');
        }

        return back()->with('success', 'Thông tin gia sư đã được cập nhật thành công.');
    }
    
    /**
     * Cập nhật thông tin tài khoản từ trang profile gia sư
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
        ]);
        
        $user->name = $request->name;
        
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->email_verified_at = null; // Nếu email thay đổi, yêu cầu xác thực lại
        }
        
        $user->save();
        
        return redirect()->route('tutor.profile.edit')->with('success', 'Thông tin tài khoản đã được cập nhật thành công.');
    }
    
    /**
     * Cập nhật mật khẩu từ trang profile gia sư
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('tutor.profile.edit')->with('success', 'Mật khẩu đã được cập nhật thành công.');
    }
} 