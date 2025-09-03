<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        // Lưu URL chuyển hướng vào session nếu có
        if ($request->has('redirect')) {
            $request->session()->put('redirect', $request->input('redirect'));
        }
        
        // Lưu URL chuyển hướng từ session flash nếu có
        if (session()->has('redirect')) {
            session()->reflash(); // Giữ lại flash data cho request tiếp theo
        }
        
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Kiểm tra xem có URL chuyển hướng trong session không
        if ($request->session()->has('redirect')) {
            $redirectUrl = $request->session()->get('redirect');
            $request->session()->forget('redirect');
            return redirect()->intended($redirectUrl);
        }

        // Chuyển hướng dựa vào vai trò của người dùng
        $user = Auth::user();
        
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isTutor() && $user->tutor->status === 'active') {
            return redirect()->route('tutor.dashboard');
        } elseif ($user->isTutor() && $user->tutor->status !== 'active') {
            return redirect()->route('tutors.pending', $user->tutor);
        } elseif ($user->isStudent()) {
            return redirect()->route('student.bookings.index');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
} 