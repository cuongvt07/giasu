<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $tutor = Auth::user()->tutor;
        
        if (!$tutor) {
            return redirect()->route('tutors.create')
                ->with('error', 'Bạn cần đăng ký làm gia sư để truy cập trang này.');
        }

        if ($tutor->status !== 'active') {
            return redirect()->route('tutors.pending', $tutor)
                ->with('error', 'Tài khoản gia sư của bạn chưa được kích hoạt. Vui lòng chờ quản trị viên xác nhận.');
        }

        return $next($request);
    }
} 