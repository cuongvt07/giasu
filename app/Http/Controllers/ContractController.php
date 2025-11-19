<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function myContracts()
    {
        $user = auth()->user();
        $tutor = $user->tutor ?? null;
        $tutorId = $tutor?->id;

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
            ->orderBy('c.created_at', 'desc')
            ->get();

        return view('pages.contracts.my', compact('contracts'));
    }

    public function show($id)
    {
        $user = auth()->user();

        $contract = DB::table('contracts as c')
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
                'tp.goal',
                'tp.budget_min',
                'tp.budget_max',
                'tp.budget_unit',
                'tp.address_line',
                's.name as subject_name',
                'cl.name as class_level_name'
            )
            ->where('c.id', $id)
            ->first();

        if (!$contract) {
            abort(404);
        }

        $tutor = $user->tutor ?? null;
        $isTutorForContract = $tutor && $tutor->id === $contract->tutor_id;

        if ($user->id !== $contract->student_id && !$isTutorForContract && !$user->is_admin) {
            abort(403, 'Bạn không có quyền xem hợp đồng này');
        }

        return view('pages.contracts.record_example', compact('contract'));
    }

    public function accept($id)
    {
        $contract = DB::table('contracts')->where('id', $id)->firstOrFail();
        $user = auth()->user();
        $now = now();

        $updateData = [
            'updated_at' => $now,
        ];

        // Cập nhật thời gian ký dựa theo user
        $tutor = $user->tutor ?? null;
        if ($user->id == $contract->student_id) {
            $updateData['signed_student_at'] = $now;
        } elseif ($tutor && $tutor->id == $contract->tutor_id) {
            $updateData['signed_tutor_at'] = $now;
        } else {
            abort(403, 'Bạn không có quyền ký hợp đồng này');
        }

        // Kiểm tra trạng thái mới
        $studentSigned = $contract->signed_student_at || ($user->id == $contract->student_id);
        $tutorSigned   = $contract->signed_tutor_at || ($tutor && $tutor->id == $contract->tutor_id);
        $systemSigned  = $contract->system_verified_at;

        if ($studentSigned && $tutorSigned && $systemSigned) {
            $updateData['status'] = 'completed';
        } elseif ($studentSigned && $tutorSigned) {
            $updateData['status'] = 'both_accepted';
        } elseif ($studentSigned) {
            $updateData['status'] = 'student_accepted';
        } elseif ($tutorSigned) {
            $updateData['status'] = 'tutor_accepted';
        }

        DB::table('contracts')
            ->where('id', $id)
            ->update($updateData);

        return back()->with('success', 'Đã xác nhận hợp đồng.');
    }
}