<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tutor;
use App\Models\Subject;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
    // Thống kê cơ bản
    $totalUsers = User::count();
    $totalTutors = Tutor::count();
    $totalSubjects = Subject::count();
    $totalBookings = Booking::count();

    // Thống kê tin đăng phụ huynh đã duyệt
    $totalApprovedPosts = \App\Models\TutorPost::count();

    // Thống kê tổng số hợp đồng
    $totalContracts = \App\Models\Contract::count();

        // Thống kê hiệu quả kết nối
        // Lượt ứng tuyển (số lượng apply trên tất cả tin đăng)
        $totalApplications = \App\Models\TutorPost::withCount('applications')->get()->sum('applications_count');
        // Lượt hợp đồng đã chốt (có system_verified_at)
        $totalClosedContracts = \App\Models\Contract::count();

        // Tính tỉ lệ
        $applicationPerPost = $totalApprovedPosts > 0 ? round($totalApplications / $totalApprovedPosts, 2) : 0;
        $closedContractPerPost = $totalApprovedPosts > 0 ? round($totalClosedContracts / $totalApprovedPosts, 2) : 0;

        // Lấy danh sách Đặt Lịch Ca Dạy GS hôm nay
        $todayBookings = Booking::with(['student', 'tutor.user', 'subject'])
            ->whereDate('start_time', Carbon::today())
            ->get();
            
        // Thống kê doanh thu và thu nhập
        $platformStats = [
            'total_platform_fee' => \App\Models\TutorEarning::whereIn('status', ['completed', 'processing'])->sum('platform_fee'),
            'pending_payments' => \App\Models\TutorEarning::where('status', 'pending')->sum('amount'),
            'completed_payments' => \App\Models\TutorEarning::where('status', 'completed')->sum('amount'),
            'total_earnings' => \App\Models\TutorEarning::sum('total_amount'),
        ];

        // Thống kê đăng ký gia sư theo thời gian (7 ngày gần nhất)
        $tutorRegistrationData = Tutor::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $tutorRegistrationChart = [
            'labels' => $tutorRegistrationData->pluck('date')->map(function($date) {
                return Carbon::parse($date)->format('d/m');
            }),
            'data' => $tutorRegistrationData->pluck('total'),
        ];

        // Thống kê Đặt Lịch Ca Dạy GS theo môn học
        $bookingsBySubjectData = Booking::select('subjects.name', DB::raw('count(*) as total'))
            ->join('subjects', 'bookings.subject_id', '=', 'subjects.id')
            ->groupBy('subjects.id', 'subjects.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $bookingsBySubjectChart = [
            'labels' => $bookingsBySubjectData->pluck('name'),
            'data' => $bookingsBySubjectData->pluck('total'),
        ];
        
        // Lấy các khoản thanh toán gia sư chờ xử lý
        $pendingEarnings = \App\Models\TutorEarning::with(['tutor.user', 'booking.subject'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $contracts_signed_student = \App\Models\Contract::whereNotNull('signed_student_at')->whereNull('signed_tutor_at')->whereNull('system_verified_at')->with(['tutorPost', 'student', 'tutor'])->latest()->take(20)->get();
        $contracts_signed_tutor = \App\Models\Contract::whereNotNull('signed_tutor_at')->whereNull('signed_student_at')->whereNull('system_verified_at')->with(['tutorPost', 'student', 'tutor'])->latest()->take(20)->get();
        $contracts_both_signed = \App\Models\Contract::whereNotNull('signed_student_at')->whereNotNull('signed_tutor_at')->whereNull('system_verified_at')->with(['tutorPost', 'student', 'tutor'])->latest()->take(20)->get();
        $contracts_admin_signed = \App\Models\Contract::whereNotNull('system_verified_at')->with(['tutorPost', 'student', 'tutor'])->latest()->take(20)->get();


        // Đếm số lượng hợp đồng cho từng tab
        $count_signed_student = \App\Models\Contract::whereNotNull('signed_student_at')->whereNull('signed_tutor_at')->whereNull('system_verified_at')->count();
        $count_signed_tutor = \App\Models\Contract::whereNotNull('signed_tutor_at')->whereNull('signed_student_at')->whereNull('system_verified_at')->count();
        $count_both_signed = \App\Models\Contract::whereNotNull('signed_student_at')->whereNotNull('signed_tutor_at')->whereNull('system_verified_at')->count();
        $count_admin_signed = \App\Models\Contract::whereNotNull('system_verified_at')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTutors',
            'totalSubjects',
            'totalBookings',
            'totalApprovedPosts',
            'totalContracts',
            'todayBookings',
            'tutorRegistrationChart',
            'bookingsBySubjectChart',
            'platformStats',
            'pendingEarnings',
            'contracts_signed_student',
            'contracts_signed_tutor',
            'contracts_both_signed',
            'contracts_admin_signed',
            'count_signed_student',
            'count_signed_tutor',
            'count_both_signed',
            'count_admin_signed',
            'totalApplications',
            'totalClosedContracts',
            'applicationPerPost',
            'closedContractPerPost'
    ));
    }
} 