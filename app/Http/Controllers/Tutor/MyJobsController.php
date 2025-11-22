<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class MyJobsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tutor = $user->tutor ?? null;
        $tutorId = $tutor?->id;

        // 1️⃣ Danh sách hợp đồng
        $contracts = DB::table('contracts as c')
            ->join('tutor_posts as tp', 'c.tutor_post_id', '=', 'tp.id')
            ->join('users as student', 'c.student_id', '=', 'student.id')
            ->join('tutors as tutor', 'c.tutor_id', '=', 'tutor.id')
            ->join('users as tutor_user', 'tutor.user_id', '=', 'tutor_user.id')
            ->leftJoin('subjects as s', 'tp.subject_id', '=', 's.id')
            ->leftJoin('class_levels as cl', 'tp.class_level_id', '=', 'cl.id')
            ->select(
                'c.*',
                'student.name as student_name',
                'tutor_user.name as tutor_name',
                's.name as subject_name',
                'cl.name as class_level_name',
                'tp.goal',
                'tp.budget_min',
                'tp.budget_max',
                'tp.budget_unit'
            )
            ->where(function ($q) use ($user, $tutorId) {
                $q->where('c.student_id', $user->id);
                if ($tutorId) {
                    $q->orWhere('c.tutor_id', $tutorId);
                }
            })
            ->orderByDesc('c.created_at')
            ->get();

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
            ->when($tutorId, function ($q) use ($tutorId) {
                $q->where('ta.tutor_id', $tutorId);
            }, function ($q) {
                $q->whereRaw('1 = 0');
            })
            ->whereIn('ta.status', ['pending', 'rejected'])
            ->orderByDesc('ta.created_at')
            ->get();

        return view('tutor.contracts.my', compact('contracts', 'applications'));
    }
} 