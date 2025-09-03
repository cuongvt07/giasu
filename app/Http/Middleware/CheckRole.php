<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Lấy route hiện tại
        $route = $request->route()->getName();
        // Lấy path của URL hiện tại
        $path = $request->path();
        
        // Danh sách các route công khai, không yêu cầu đăng nhập
        $publicRoutes = [
            'home', 'login', 'register', 'password.request', 'password.email', 
            'password.reset', 'password.update', 'tutors.index', 'tutors.show',
            'subjects.index', 'subjects.show', 'privacy-policy', 'about-us',
            'contact', 'faq', 'guide', 'terms', 'tutors.register', 'tutors.create',
            'payment.callback'
        ];
        
        // Kiểm tra nếu đường dẫn là trang chủ
        if ($path === '/' || in_array($route, $publicRoutes)) {
            return $next($request);
        }
        
        // Kiểm tra nếu người dùng chưa đăng nhập
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Nếu là admin, cho phép truy cập tất cả
        if ($user->is_admin) {
            return $next($request);
        }
        
        // Nếu là gia sư, chỉ cho phép truy cập URL có đường dẫn /tutor
        if ($user->isTutor() && $user->tutor && $user->tutor->status === 'active') {
            // Kiểm tra các route public mà gia sư được phép truy cập
            if (in_array($route, ['logout', 'profile.edit', 'profile.update', 'profile.destroy', 'profile.password.update'])) {
                return $next($request);
            }
            
            // Nếu không phải URL bắt đầu bằng 'tutor/' và không phải public route, chuyển hướng về dashboard tutor
            if (!str_starts_with($path, 'tutor/') && !str_starts_with($path, 'tutor')) {
                return redirect()->route('tutor.dashboard')
                    ->with('error', 'Bạn chỉ có quyền truy cập trang dành cho gia sư.');
            }
        }
        
        // Kiểm tra nếu là route của học sinh
        if (str_starts_with($route, 'student.')) {
            if (!$user->isStudent()) {
                if ($user->isTutor()) {
                    return redirect()->route('tutor.dashboard')
                        ->with('error', 'Bạn đang đăng nhập với tư cách gia sư. Không thể truy cập trang dành cho học sinh.');
                }
                return redirect()->route('home')
                    ->with('error', 'Bạn không có quyền truy cập trang dành cho học sinh.');
            }
        }
        
        // Kiểm tra nếu là route của gia sư
        if (str_starts_with($route, 'tutor.')) {
            if (!$user->isTutor()) {
                if ($user->isStudent()) {
                    return redirect()->route('student.bookings.index')
                        ->with('error', 'Bạn đang đăng nhập với tư cách học sinh. Không thể truy cập trang dành cho gia sư.');
                }
                return redirect()->route('home')
                    ->with('error', 'Bạn không có quyền truy cập trang dành cho gia sư.');
            } else if ($user->tutor->status !== 'active') {
                return redirect()->route('tutors.pending', $user->tutor)
                    ->with('error', 'Tài khoản gia sư của bạn chưa được kích hoạt.');
            }
        }
        
        // Kiểm tra nếu là route của admin
        if (str_starts_with($route, 'admin.')) {
            if (!$user->is_admin) {
                if ($user->isTutor()) {
                    return redirect()->route('tutor.dashboard')
                        ->with('error', 'Bạn không có quyền truy cập trang quản trị.');
                } else if ($user->isStudent()) {
                    return redirect()->route('student.bookings.index')
                        ->with('error', 'Bạn không có quyền truy cập trang quản trị.');
                }
                return redirect()->route('home')
                    ->with('error', 'Bạn không có quyền truy cập trang quản trị.');
            }
        }
        
        return $next($request);
    }
} 