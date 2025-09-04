<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminJobController extends Controller
{
    public function index()
    {
        // Lấy danh sách tin tuyển gia sư và map tên môn + lớp, người đăng tin, người apply
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

    public function show($id)
    {
        $job = DB::table('tutor_posts')->where('id', $id)->first();

        if (!$job) {
            abort(404);
        }

        // Lấy danh sách gia sư ứng tuyển
        $applications = DB::table('tutor_applications')
            ->join('tutors', 'tutor_applications.tutor_id', '=', 'tutors.id')
            ->join('users', 'tutors.user_id', '=', 'users.id')
            ->select('tutor_applications.*', 'users.name as tutor_name', 'users.avatar')
            ->where('tutor_post_id', $id)
            ->get();

        return view('admin.manageJobs.show', compact('job', 'applications'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,published,closed,rejected'],
        ], [
            'status.required' => 'Trạng thái không được bỏ trống',
            'status.in' => 'Trạng thái không hợp lệ',
        ]);

        $job = DB::table('tutor_posts')->where('id', $id)->first();

        if (!$job) {
            return back()->with('error', 'Không tìm thấy tin tuyển này');
        }

        DB::table('tutor_posts')
            ->where('id', $id)
            ->update(['status' => $validated['status']]);

        return redirect()->route('admin.manageJobs.show', $id)
            ->with('success', 'Cập nhật trạng thái job thành công.');
    }

    public function destroy($id)
    {
        $job = DB::table('tutor_posts')->where('id', $id)->first();

        if (!$job) {
            return back()->with('error', 'Không tìm thấy job để xóa');
        }

        // Admin có thể xóa bất kỳ job nào
        DB::table('tutor_posts')->where('id', $id)->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Đã xóa job thành công.');
    }
}
