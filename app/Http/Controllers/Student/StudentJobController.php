<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\Booking;
use App\Models\Subject;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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

        if (empty($validated['status'])) {
            $validated['status'] = 'draft';
        }
        
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        $job = DB::table('tutor_posts')->insert($validated);

        return redirect()
            ->route('home')
            ->with('success', 'Tin đăng tìm gia sư đã được tạo thành công! Tin của bạn sẽ sớm được quản lý duyệt.');
    }
}