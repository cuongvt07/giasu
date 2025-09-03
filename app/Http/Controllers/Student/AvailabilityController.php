<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\StudentAvailability;
use App\Models\Booking;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    /**
     * Hiển thị danh sách lịch rảnh của học sinh
     */
    public function index()
    {
        $student = Auth::user()->student;
        $availabilities = $student->availabilities()->orderBy('day_of_week')->orderBy('start_time')->get();
        
        return view('student.availability.index', compact('availabilities'));
    }

    /**
     * Hiển thị form tạo lịch rảnh mới
     */
    public function create()
    {
        return view('student.availability.create');
    }

    /**
     * Lưu lịch rảnh mới vào database
     */
    public function store(Request $request)
    {
        // Ghi log thông tin input 
        Log::info('Thêm lịch rảnh mới cho học sinh: ', [
            'student_id' => Auth::user()->student->id,
            'input' => $request->all()
        ]);

        // Xác thực dữ liệu đầu vào
        $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'date' => 'nullable|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'is_recurring' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
        ]);

        try {
            $studentId = Auth::user()->student->id;
            
            // Định dạng thời gian
            $startTime = Carbon::parse($request->start_time);
            $endTime = Carbon::parse($request->end_time);
            
            // Kiểm tra xem có lịch trùng không
            $existingSlots = StudentAvailability::where('student_id', $studentId)
                ->where('day_of_week', $request->day_of_week)
                ->where(function($query) use ($request) {
                    // Nếu có ngày cụ thể, chỉ kiểm tra trùng với ngày đó
                    if ($request->filled('date')) {
                        $query->whereDate('date', $request->date);
                    } elseif ($request->boolean('is_recurring')) {
                        // Nếu là lịch lặp lại, kiểm tra các lịch lặp lại khác
                        $query->whereNull('date')->where('is_recurring', true);
                    }
                })
                ->where(function($query) use ($startTime, $endTime) {
                    $query->whereBetween('start_time', [$startTime, $endTime])
                        ->orWhereBetween('end_time', [$startTime, $endTime])
                        ->orWhere(function($q) use ($startTime, $endTime) {
                            $q->where('start_time', '<=', $startTime)
                              ->where('end_time', '>=', $endTime);
                        });
                })
                ->get();

            if ($existingSlots->count() > 0) {
                Log::warning('Phát hiện lịch rảnh trùng lặp:', [
                    'student_id' => $studentId,
                    'day' => $request->day_of_week,
                    'date' => $request->date,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'existing_slots' => $existingSlots
                ]);
                
                return redirect()->back()->with('error', 'Bạn đã có lịch rảnh trong khoảng thời gian này!');
            }

            // Tạo bản ghi mới
            $availability = new StudentAvailability();
            $availability->student_id = $studentId;
            $availability->day_of_week = $request->day_of_week;
            $availability->date = $request->filled('date') ? $request->date : null;
            $availability->start_time = $startTime;
            $availability->end_time = $endTime;
            $availability->is_recurring = $request->boolean('is_recurring');
            $availability->status = $request->input('status', 'active');
            
            if ($availability->save()) {
                Log::info('Đã thêm lịch rảnh thành công', [
                    'availability_id' => $availability->id, 
                    'student_id' => $studentId
                ]);
                return redirect()->route('student.availability.index')->with('success', 'Đã thêm lịch rảnh thành công!');
            } else {
                Log::error('Không thể lưu lịch rảnh', [
                    'student_id' => $studentId, 
                    'availability_data' => $availability->toArray()
                ]);
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi lưu lịch rảnh!');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm lịch rảnh:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị form sửa lịch rảnh
     */
    public function edit($id)
    {
        $studentId = Auth::user()->student->id;
        
        $availability = StudentAvailability::where('id', $id)
            ->where('student_id', $studentId)
            ->firstOrFail();
            
        return view('student.availability.edit', compact('availability'));
    }

    /**
     * Cập nhật lịch rảnh vào database
     */
    public function update(Request $request, $id)
    {
        // Ghi log thông tin input
        Log::info('Cập nhật lịch rảnh học sinh:', [
            'availability_id' => $id,
            'input' => $request->all()
        ]);

        // Xác thực dữ liệu đầu vào
        $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'date' => 'nullable|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'is_recurring' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
        ]);

        try {
            $studentId = Auth::user()->student->id;
            
            // Tìm lịch rảnh cần cập nhật
            $availability = StudentAvailability::where('id', $id)
                ->where('student_id', $studentId)
                ->first();
                
            if (!$availability) {
                Log::warning('Không tìm thấy lịch rảnh để cập nhật', [
                    'availability_id' => $id,
                    'student_id' => $studentId
                ]);
                return redirect()->back()->with('error', 'Không tìm thấy lịch rảnh');
            }
            
            // Định dạng thời gian
            $startTime = Carbon::parse($request->start_time);
            $endTime = Carbon::parse($request->end_time);
            
            // Kiểm tra xem có lịch trùng không (ngoại trừ chính lịch hiện tại)
            $existingSlots = StudentAvailability::where('student_id', $studentId)
                ->where('id', '!=', $id)
                ->where('day_of_week', $request->day_of_week)
                ->where(function($query) use ($request) {
                    // Nếu có ngày cụ thể, chỉ kiểm tra trùng với ngày đó
                    if ($request->filled('date')) {
                        $query->whereDate('date', $request->date);
                    } elseif ($request->boolean('is_recurring')) {
                        // Nếu là lịch lặp lại, kiểm tra các lịch lặp lại khác
                        $query->whereNull('date')->where('is_recurring', true);
                    }
                })
                ->where(function($query) use ($startTime, $endTime) {
                    $query->whereBetween('start_time', [$startTime, $endTime])
                        ->orWhereBetween('end_time', [$startTime, $endTime])
                        ->orWhere(function($q) use ($startTime, $endTime) {
                            $q->where('start_time', '<=', $startTime)
                              ->where('end_time', '>=', $endTime);
                        });
                })
                ->get();

            if ($existingSlots->count() > 0) {
                Log::warning('Phát hiện lịch rảnh trùng lặp khi cập nhật:', [
                    'availability_id' => $id,
                    'student_id' => $studentId,
                    'day' => $request->day_of_week,
                    'date' => $request->date,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'existing_slots' => $existingSlots
                ]);
                
                return redirect()->back()->with('error', 'Bạn đã có lịch rảnh trong khoảng thời gian này!');
            }

            // Cập nhật thông tin
            $availability->day_of_week = $request->day_of_week;
            $availability->date = $request->filled('date') ? $request->date : null;
            $availability->start_time = $startTime;
            $availability->end_time = $endTime;
            $availability->is_recurring = $request->boolean('is_recurring');
            $availability->status = $request->input('status', 'active');
            
            if ($availability->save()) {
                Log::info('Đã cập nhật lịch rảnh học sinh thành công', [
                    'availability_id' => $availability->id
                ]);
                return redirect()->route('student.availability.index')->with('success', 'Đã cập nhật lịch rảnh thành công!');
            } else {
                Log::error('Không thể cập nhật lịch rảnh học sinh', [
                    'availability_id' => $id,
                    'availability_data' => $availability->toArray()
                ]);
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật lịch rảnh!');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật lịch rảnh học sinh:', [
                'availability_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xóa lịch rảnh
     */
    public function destroy($id)
    {
        // Ghi log thông tin
        Log::info('Đang thực hiện xóa lịch rảnh học sinh', [
            'availability_id' => $id,
            'student_id' => Auth::user()->student->id
        ]);

        try {
            $studentId = Auth::user()->student->id;
            
            // Tìm lịch rảnh cần xóa
            $availability = StudentAvailability::where('id', $id)
                ->where('student_id', $studentId)
                ->first();
            
            if (!$availability) {
                Log::warning('Không tìm thấy lịch rảnh học sinh để xóa', [
                    'availability_id' => $id,
                    'student_id' => $studentId
                ]);
                return redirect()->back()->with('error', 'Không tìm thấy lịch rảnh');
            }
            
            // Kiểm tra xem lịch rảnh có đang được sử dụng không
            $hasBookings = Booking::where('student_id', $studentId)
                ->where('day_of_week', $availability->day_of_week)
                ->where('status', '!=', 'cancelled')
                ->where(function($query) use ($availability) {
                    $query->whereBetween('start_time', [$availability->start_time, $availability->end_time])
                        ->orWhereBetween('end_time', [$availability->start_time, $availability->end_time])
                        ->orWhere(function($q) use ($availability) {
                            $q->where('start_time', '<=', $availability->start_time)
                              ->where('end_time', '>=', $availability->end_time);
                        });
                });
                
            // Nếu có ngày cụ thể, chỉ kiểm tra lịch học cho ngày đó
            if ($availability->date) {
                $hasBookings->whereDate('start_time', $availability->date);
            }
            
            if ($hasBookings->exists()) {
                Log::warning('Không thể xóa lịch rảnh học sinh vì đang có lịch học', [
                    'availability_id' => $id,
                    'related_bookings' => $hasBookings->get()->pluck('id')
                ]);
                
                return redirect()->back()->with('error', 'Không thể xóa lịch rảnh đang được sử dụng cho buổi học');
            }
            
            // Thực hiện xóa
            if ($availability->delete()) {
                Log::info('Đã xóa lịch rảnh học sinh thành công', [
                    'availability_id' => $id
                ]);
                return redirect()->route('student.availability.index')->with('success', 'Đã xóa lịch rảnh thành công!');
            } else {
                Log::error('Không thể xóa lịch rảnh học sinh', [
                    'availability_id' => $id
                ]);
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa lịch rảnh!');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa lịch rảnh học sinh:', [
                'availability_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Thêm nhanh nhiều lịch rảnh cùng lúc
     */
    public function quickStore(Request $request)
    {
        // Ghi log thông tin
        Log::info('Thêm nhanh lịch rảnh mới cho học sinh:', [
            'student_id' => Auth::user()->student->id,
            'input' => $request->all()
        ]);

        // Validate input
        $request->validate([
            'days' => 'required|array',
            'days.*' => 'required|integer|between:0,6',
            'timeSlots' => 'required|array',
            'timeSlots.*' => 'required|string',
            'date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive',
            'is_recurring' => 'nullable|boolean',
        ], [
            'days.required' => 'Vui lòng chọn ít nhất một ngày',
            'days.array' => 'Danh sách ngày không hợp lệ',
            'days.*.required' => 'Ngày không được bỏ trống',
            'days.*.integer' => 'Ngày phải là số nguyên',
            'days.*.between' => 'Ngày phải có giá trị từ 0 đến 6',
            'timeSlots.required' => 'Vui lòng chọn ít nhất một khung giờ',
            'timeSlots.array' => 'Danh sách khung giờ không hợp lệ',
            'timeSlots.*.required' => 'Khung giờ không được bỏ trống',
            'date.date' => 'Ngày không hợp lệ',
            'status.in' => 'Trạng thái không hợp lệ',
        ]);

        try {
            $studentId = Auth::user()->student->id;
            $days = $request->days;
            $timeSlots = $request->timeSlots;
            $date = $request->filled('date') ? $request->date : null;
            $status = $request->input('status', 'active');
            $isRecurring = $request->boolean('is_recurring');
            
            // Xóa lịch trùng nếu có (tùy chọn)
            if ($request->boolean('replace_existing') && $request->filled('date')) {
                StudentAvailability::where('student_id', $studentId)
                    ->whereDate('date', $date)
                    ->delete();
                
                Log::info('Đã xóa lịch rảnh học sinh trùng lặp', [
                    'student_id' => $studentId,
                    'date' => $date
                ]);
            }

            $count = 0;
            $errors = [];

            foreach ($days as $day) {
                foreach ($timeSlots as $timeSlot) {
                    $times = explode('-', $timeSlot);
                    if (count($times) != 2) {
                        $errors[] = "Định dạng thời gian không hợp lệ: $timeSlot";
                        continue;
                    }

                    $startTime = trim($times[0]);
                    $endTime = trim($times[1]);

                    // Kiểm tra trùng lặp
                    $existingSlots = StudentAvailability::where('student_id', $studentId)
                        ->where('day_of_week', $day)
                        ->where(function($query) use ($date, $isRecurring) {
                            if ($date) {
                                $query->whereDate('date', $date);
                            } elseif ($isRecurring) {
                                $query->whereNull('date')->where('is_recurring', true);
                            }
                        })
                        ->where(function($query) use ($startTime, $endTime) {
                            $startDateTime = Carbon::parse($startTime);
                            $endDateTime = Carbon::parse($endTime);
                            
                            $query->whereBetween('start_time', [$startDateTime, $endDateTime])
                                ->orWhereBetween('end_time', [$startDateTime, $endDateTime])
                                ->orWhere(function($q) use ($startDateTime, $endDateTime) {
                                    $q->where('start_time', '<=', $startDateTime)
                                      ->where('end_time', '>=', $endDateTime);
                                });
                        })
                        ->get();

                    if ($existingSlots->count() > 0) {
                        Log::warning('Phát hiện lịch trùng lặp khi thêm nhanh cho học sinh:', [
                            'student_id' => $studentId,
                            'day' => $day,
                            'date' => $date,
                            'time_slot' => $timeSlot
                        ]);
                        continue; // Bỏ qua slot trùng lặp
                    }

                    // Tạo mới
                    $availability = new StudentAvailability();
                    $availability->student_id = $studentId;
                    $availability->day_of_week = $day;
                    $availability->date = $date;
                    $availability->start_time = Carbon::parse($startTime);
                    $availability->end_time = Carbon::parse($endTime);
                    $availability->is_recurring = $isRecurring;
                    $availability->status = $status;

                    if ($availability->save()) {
                        $count++;
                        Log::info('Đã thêm lịch rảnh học sinh thành công', [
                            'availability_id' => $availability->id, 
                            'day' => $day,
                            'time_slot' => $timeSlot
                        ]);
                    } else {
                        $errors[] = "Không thể lưu lịch cho {$day}, {$timeSlot}";
                        Log::error('Không thể lưu lịch rảnh học sinh', [
                            'day' => $day,
                            'time_slot' => $timeSlot
                        ]);
                    }
                }
            }

            if ($count > 0) {
                return redirect()->route('student.availability.index')
                    ->with('success', "Đã thêm $count lịch rảnh thành công!" . 
                        (count($errors) > 0 ? " Có " . count($errors) . " lỗi xảy ra." : ""));
            } else {
                return redirect()->back()
                    ->with('error', 'Không thể thêm lịch rảnh. ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm nhanh lịch rảnh học sinh:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Danh sách các ngày trong tuần
     */
    private function getDaysOfWeek()
    {
        return [
            0 => 'Chủ Nhật',
            1 => 'Thứ Hai',
            2 => 'Thứ Ba',
            3 => 'Thứ Tư',
            4 => 'Thứ Năm',
            5 => 'Thứ Sáu',
            6 => 'Thứ Bảy',
        ];
    }
} 