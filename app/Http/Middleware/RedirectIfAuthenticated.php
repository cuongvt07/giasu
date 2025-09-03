<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): mixed
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Cho phép truy cập các route liên quan đến gia sư
                if (str_starts_with($request->route()->getName(), 'tutors.') || 
                    str_starts_with($request->route()->getName(), 'student.') ||
                    str_starts_with($request->route()->getName(), 'tutor.')) {
                    return $next($request);
                }

                // Cho phép truy cập các route liên quan đến profile
                if (str_starts_with($request->route()->getName(), 'profile.')) {
                    return $next($request);
                }

                // Cho phép truy cập các route liên quan đến payment
                if (str_starts_with($request->route()->getName(), 'payment.')) {
                    return $next($request);
                }

                // Chuyển hướng về dashboard cho các route khác
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
} 