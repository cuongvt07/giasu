<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EarningController extends Controller
{
    public function index()
    {
        $tutor = Auth::user()->tutor;
        
        // Tính tổng thu nhập
        $totalEarnings = $tutor->bookings()
            ->where('status', 'completed')
            ->sum('total_amount');

        // Thu nhập theo tháng
        $monthlyEarnings = $tutor->bookings()
            ->where('status', 'completed')
            ->select(
                DB::raw('YEAR(completed_at) as year'),
                DB::raw('MONTH(completed_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        // Các buổi học đã hoàn thành gần đây
        $recentCompletedBookings = $tutor->bookings()
            ->with(['student', 'subject'])
            ->where('status', 'completed')
            ->latest('completed_at')
            ->take(5)
            ->get();

        return view('tutor.earnings.index', compact(
            'totalEarnings',
            'monthlyEarnings',
            'recentCompletedBookings'
        ));
    }
} 