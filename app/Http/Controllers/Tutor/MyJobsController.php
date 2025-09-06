<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class MyJobsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1️⃣ Danh sách hợp đồng
        $contracts = DB::table('contracts as c')
            ->join('tutor_posts as tp', 'c.tutor_post_id', '=', 'tp.id')
            ->join('users as student', 'c.student_id', '=', 'student.id')
            ->join('users as tutor', 'c.tutor_id', '=', 'tutor.id')
            ->leftJoin('subjects as s', 'tp.subject_id', '=', 's.id')
            ->leftJoin('class_levels as cl', 'tp.class_level_id', '=', 'cl.id')
            ->select(
                'c.*',
                'student.name as student_name',
                'tutor.name as tutor_name',
                's.name as subject_name',
                'cl.name as class_level_name',
                'tp.goal',
                'tp.budget_min',
                'tp.budget_max',
                'tp.budget_unit'
            )
            ->where(function ($q) use ($user) {
                $q->where('c.student_id', $user->id)
                ->orWhere('c.tutor_id', $user->id);
            })
            ->orderByDesc('c.created_at')
            ->get();

        // 2️⃣ Các tin đã apply nhưng chưa được chọn hoặc bị từ chối (status != requested)
        $applications = DB::table('tutor_applications as ta')
            ->join('tutor_posts as tp', 'ta.tutor_post_id', '=', 'tp.id')
            ->join('users as student', 'tp.user_id', '=', 'student.id')
            ->leftJoin('subjects as s', 'tp.subject_id', '=', 's.id')
            ->leftJoin('class_levels as cl', 'tp.class_level_id', '=', 'cl.id')
            ->select(
                'ta.*',
                'student.name as student_name',
                's.name as subject_name',
                'cl.name as class_level_name',
                'tp.goal',
                'tp.budget_min',
                'tp.budget_max',
                'tp.budget_unit'
            )
            ->where('ta.tutor_id', $user->id)
            ->whereIn('ta.status', ['pending', 'rejected']) // trạng thái khác requested
            ->orderByDesc('ta.created_at')
            ->get();

        return view('tutor.contracts.my', compact('contracts', 'applications'));
    }
} 