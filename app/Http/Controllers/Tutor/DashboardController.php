<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $tutor = Auth::user()->tutor;
        
        // Lấy số lịch dạy hôm nay
        $todayBookings = $tutor->bookings()
            ->whereDate('start_time', Carbon::today())
            ->count();

        // Lấy tổng số học sinh đã dạy
        $totalStudents = $tutor->bookings()
            ->where('status', 'completed')
            ->distinct('student_id')
            ->count();

        // Tính tổng số giờ dạy
        $totalTeachingHours = $tutor->bookings()
            ->where('status', 'completed')
            ->get()
            ->sum(function ($booking) {
                return Carbon::parse($booking->start_time)
                    ->diffInHours(Carbon::parse($booking->end_time));
            });

        // Tính thu nhập tháng này
        $monthlyEarnings = $tutor->bookings()
            ->where('status', 'completed')
            ->whereMonth('completed_at', Carbon::now()->month)
            ->whereYear('completed_at', Carbon::now()->year)
            ->sum('total_amount');

        // Lấy các lịch dạy sắp tới
        $upcomingBookings = $tutor->bookings()
            ->with(['student', 'subject'])
            ->where('start_time', '>', Carbon::now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        // Lấy các đánh giá gần đây
        $recentReviews = $tutor->reviews()
            ->with(['student', 'booking.subject'])
            ->latest()
            ->take(3)
            ->get();

        return view('tutor.dashboard', compact(
            'todayBookings',
            'totalStudents',
            'totalTeachingHours',
            'monthlyEarnings',
            'upcomingBookings',
            'recentReviews'
        ));
    }
} 