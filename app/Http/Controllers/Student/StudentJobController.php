<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentJobController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'class_level_id' => 'nullable|exists:class_levels,id',
            'goal' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'budget_min' => 'nullable|numeric',
            'budget_max' => 'nullable|numeric',
            'budget_unit' => 'required|string|in:buoi,gio,thang,khoa',
            'sessions_per_week' => 'nullable|integer',
            'session_length_min' => 'nullable|integer',
            'schedule_notes' => 'nullable|string',
            'mode' => 'required|string|in:online,offline',
            'address_line' => 'nullable|string|max:255',
            'student_count' => 'required|integer|min:1',
            'student_age' => 'nullable|string|max:50',
            'special_notes' => 'nullable|string',
            'qualifications' => 'nullable|string',
            'min_experience_yr' => 'nullable|integer',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'nullable|email',
            'deadline_at' => 'nullable|date',
            'status' => 'required|string|in:published,draft',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        DB::table('tutor_posts')->insert($validated);

        return redirect()
            ->route('home')
            ->with('success', 'Tin đăng tìm gia sư đã được tạo thành công!');
    }

    public function jobsUser()
    {
        $userId = Auth::id();

        // Danh sách tin của user
        $jobs = DB::table('tutor_posts as tp')
            ->where('tp.user_id', $userId)
            ->leftJoin('subjects as s', 'tp.subject_id', '=', 's.id')
            ->leftJoin('class_levels as cl', 'tp.class_level_id', '=', 'cl.id')
            ->select(
                'tp.id',
                'tp.goal',
                'tp.description',
                'tp.budget_min',
                'tp.budget_max',
                'tp.budget_unit',
                'tp.sessions_per_week',
                'tp.mode',
                'tp.status',
                's.name as subject_name',
                'cl.name as class_level_name'
            )
            ->orderByDesc('tp.id')
            ->get();

        // Applications
        $applications = DB::table('tutor_applications as ta')
            ->join('tutors as t', 'ta.tutor_id', '=', 't.id')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->select(
                'ta.id as app_id',
                'ta.tutor_post_id',
                'ta.status as app_status',
                't.id as tutor_id',
                't.avatar',
                'u.name as tutor_name',
                't.university',
                't.major',
                't.teaching_experience',
                't.hourly_rate',
                't.rating'
            )
            ->get()
            ->groupBy('tutor_post_id');

        return view('student.manageJobs.index', compact('jobs', 'applications'));
    }

    // JobController.php
    public function acceptTutor(Request $request)
    {
        $jobId   = $request->input('job_id');
        $tutorId = $request->input('tutor_id');

        DB::table('tutor_applications')
            ->where('tutor_post_id', $jobId)
            ->where('tutor_id', $tutorId)
            ->update([
                'note' => 'Khách hàng đã chọn bạn',
                'status' => 'requested',
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Yêu cầu đã được gửi đến quản lý! Vui lòng chờ đợi liên hệ lại.',
        ]);
    }

}
