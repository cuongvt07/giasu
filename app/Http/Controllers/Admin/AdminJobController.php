<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminJobController extends Controller
{
    public function index()
    {
        $statuses = ['draft', 'pending', 'published', 'closed'];
        $jobsByStatus = [];

        foreach ($statuses as $status) {
                $jobs = DB::table('tutor_posts as tp')
                    ->leftJoin('subjects as s', 'tp.subject_id', '=', 's.id')
                    ->leftJoin('class_levels as cl', 'tp.class_level_id', '=', 'cl.id')
                    ->leftJoin('users as u', 'tp.user_id', '=', 'u.id')
                    ->leftJoin('contracts as c', function($join) {
                        $join->on('tp.id', '=', 'c.tutor_post_id');                    })
                    ->where('tp.status', $status)
                    ->select(
                        'tp.*',
                        's.name as subject_name',
                        'cl.name as class_level_name',
                        'u.name as poster_name',
                        'u.email as poster_email',
                        'c.signed_student_at',
                        'c.signed_tutor_at',
                        'c.system_verified_at',
                        'c.id as contract_id'
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

                $job->signed_student = $job->signed_student_at ?? false;
                $job->signed_tutor = $job->signed_tutor_at ?? false;
            }

            $jobsByStatus[$status] = $jobs;
        }

        return view('admin.manageJobs.index', [
            'draftJobs'     => $jobsByStatus['draft'],
            'pendingJobs'   => $jobsByStatus['pending'],
            'publishedJobs' => $jobsByStatus['published'],
            'closedJobs'    => $jobsByStatus['closed'],
        ]);
    }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'status'         => 'nullable|string|in:draft,pending,published,closed',
            'application_id' => 'nullable|integer|exists:tutor_applications,id',
            'tutor_id'       => 'nullable|integer|exists:tutors,id',
            'job_id'         => 'required|integer|exists:tutor_posts,id',
            'confirm'        => 'nullable|boolean',
        ]);

        $id = $validated['job_id'];

        try {
            DB::transaction(function () use ($validated, $id) {
                $status = $validated['status'] ?? null;

                if ($status === 'published') {
                    if (empty($validated['application_id']) || empty($validated['tutor_id'])) {
                        throw new \Exception('Thiếu thông tin để chọn gia sư');
                    }

                    DB::table('tutor_applications')
                        ->where('id', $validated['application_id'])
                        ->update(['status' => 'accepted', 'updated_at' => now()]);

                    DB::table('tutor_applications')
                        ->where('tutor_post_id', $id)
                        ->where('id', '!=', $validated['application_id'])
                        ->update(['status' => 'rejected', 'updated_at' => now()]);

                    DB::table('tutor_posts')
                        ->where('id', $id)
                        ->update([
                            'status'       => 'published',
                            'published_at' => now(),
                            'updated_at'   => now(),
                        ]);
                    
                    $job = DB::table('tutor_posts')->where('id', $id)->first();
                    if ($job) {
                        $exists = DB::table('contracts')->where('tutor_post_id', $id)->exists();
                        if (!$exists) {
                            DB::table('contracts')->insert([
                                'tutor_post_id' => $id,
                                'student_id'    => $job->user_id,
                                'tutor_id'      => $validated['tutor_id'],
                                'status'        => 'pending',
                                'created_at'    => now(),
                                'updated_at'    => now(),
                            ]);
                        }
                    }
                } elseif ($status) {
                    DB::table('tutor_posts')
                        ->where('id', $id)
                        ->update([
                            'status'     => $status,
                            'updated_at' => now(),
                        ]);
                }

                if (!empty($validated['confirm'])) {
                    $contract = DB::table('contracts')->where('tutor_post_id', $id)->first();
                    if ($contract) {
                        DB::table('contracts')
                            ->where('id', $contract->id)
                            ->update([
                                'system_verified_at' => now(),
                                'updated_at' => now(),
                            ]);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
