<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Auth::user()->tutor->schedules()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('tutor.schedules.index', compact('schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'day_of_week.required' => 'Ngày trong tuần không được bỏ trống',
            'day_of_week.integer' => 'Ngày trong tuần phải là số nguyên',
            'day_of_week.between' => 'Ngày trong tuần phải có giá trị từ 0 đến 6',
            'start_time.required' => 'Thời gian bắt đầu không được bỏ trống',
            'start_time.date_format' => 'Thời gian bắt đầu phải có định dạng giờ:phút',
            'end_time.required' => 'Thời gian kết thúc không được bỏ trống',
            'end_time.date_format' => 'Thời gian kết thúc phải có định dạng giờ:phút',
            'end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu',
        ]);

        $tutor = Auth::user()->tutor;
        
        // Kiểm tra xem lịch rảnh có bị trùng không
        $existingSchedule = $tutor->schedules()
            ->where('day_of_week', $request->day_of_week)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })->first();

        if ($existingSchedule) {
            return back()->with('error', 'Lịch rảnh này bị trùng với lịch đã có.');
        }

        $tutor->schedules()->create([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return back()->with('success', 'Đã thêm lịch rảnh thành công.');
    }

    public function destroy(Schedule $schedule)
    {
        // Kiểm tra xem lịch rảnh có thuộc về gia sư hiện tại không
        if ($schedule->tutor_id !== Auth::user()->tutor->id) {
            return back()->with('error', 'Bạn không có quyền xóa lịch rảnh này.');
        }

        $schedule->delete();
        return back()->with('success', 'Đã xóa lịch rảnh thành công.');
    }
} 