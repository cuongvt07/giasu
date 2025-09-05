<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminJobController extends Controller
{
    public function index()
    {
        $statuses = ['pending', 'published', 'closed'];
        $jobsByStatus = [];

        foreach ($statuses as $status) {
            $jobs = DB::table('tutor_posts as tp')
                ->leftJoin('subjects as s', 'tp.subject_id', '=', 's.id')
                ->leftJoin('class_levels as cl', 'tp.class_level_id', '=', 'cl.id')
                ->leftJoin('users as u', 'tp.user_id', '=', 'u.id')
                ->where('tp.status', $status)
                ->select(
                    'tp.*',
                    's.name as subject_name',
                    'cl.name as class_level_name',
                    'u.name as poster_name',
                    'u.email as poster_email'
                )
                ->latest('tp.created_at')
                ->paginate(10, ['*'], $status);

            foreach ($jobs as $job) {
                $job->subject_grade = trim(($job->subject_name ?? '') . ' - ' . ($job->class_level_name ?? ''));
                $job->applications = DB::table('tutor_applications as ta')
                    ->join('tutors as t', 'ta.tutor_id', '=', 't.id')
                    ->join('users as u', 't.user_id', '=', 'u.id')
                    ->where('ta.tutor_post_id', $job->id)
                    ->select('ta.*', 'u.name as applicant_name', 'u.email as applicant_email')
                    ->get();
            }

            $jobsByStatus[$status] = $jobs;
        }


        return view('admin.manageJobs.index', [
            'pendingJobs' => $jobsByStatus['pending'],
            'publishedJobs' => $jobsByStatus['published'],
            'closedJobs' => $jobsByStatus['closed'],
        ]);
    }

    public function acceptAndComplete(Request $request)
    {
        $validated = $request->validate([
            'job_id' => 'required|integer|exists:tutor_posts,id',
            'application_id' => 'required|integer|exists:tutor_applications,id',
            'tutor_id' => 'required|integer|exists:tutors,id',
        ]);

        DB::transaction(function () use ($validated) {
            DB::table('tutor_applications')
                ->where('id', $validated['application_id'])
                ->update(['status' => 'accepted', 'updated_at' => now()]);

            DB::table('tutor_applications')
                ->where('tutor_post_id', $validated['job_id'])
                ->where('id', '!=', $validated['application_id'])
                ->update(['status' => 'rejected', 'updated_at' => now()]);

            DB::table('tutor_posts')
                ->where('id', $validated['job_id'])
                ->update([
                    'status'            => 'closed',
                    'updated_at'        => now(),
                ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Đã xác nhận hợp tác và hoàn tất phân công.',
        ]);
    }
}
