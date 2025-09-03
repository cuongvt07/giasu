<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingRescheduleRequest;
use App\Models\BookingRescheduleOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Notifications\BookingRescheduleRequested;
use Illuminate\Support\Facades\Notification;

class RescheduleController extends Controller
{
    /**
     * Hiển thị form yêu cầu đổi lịch
     */
    public function requestForm(Booking $booking)
    {
        // Thêm log để debug
        \Illuminate\Support\Facades\Log::info('Accessing reschedule form', [
            'booking_id' => $booking->id,
            'tutor_id' => Auth::user()->tutor->id ?? 'null',
            'booking_tutor_id' => $booking->tutor_id,
            'booking_status' => $booking->status,
            'hours_until_booking' => now()->diffInHours($booking->start_time, false),
            'has_pending_request' => $booking->hasPendingRescheduleRequest(),
        ]);

        if ($booking->tutor_id !== Auth::user()->tutor->id) {
            abort(403, 'Bạn không có quyền truy cập buổi học này.');
        }
        
        // Kiểm tra xem buổi học có thể đổi lịch không
        if (!in_array($booking->status, ['confirmed', 'scheduled'])) {
            return back()->with('error', 'Không thể đổi lịch buổi học này. Chỉ có thể đổi lịch các buổi học đã được xác nhận.');
        }
        
        // Kiểm tra thời gian
        if (now()->diffInHours($booking->start_time, false) < 24) {
            return back()->with('error', 'Không thể đổi lịch buổi học khi đã gần đến thời gian học (dưới 24 giờ).');
        }
        
        // Kiểm tra xem đã có yêu cầu đổi lịch chưa
        if ($booking->hasPendingRescheduleRequest()) {
            return back()->with('error', 'Buổi học này đã có yêu cầu đổi lịch đang chờ xử lý.');
        }
        
        return view('tutor.bookings.reschedule-form', compact('booking'));
    }
    
    /**
     * Xử lý yêu cầu đổi lịch
     */
    public function store(Request $request, Booking $booking)
    {
        // Thêm log để debug
        \Illuminate\Support\Facades\Log::info('Processing reschedule request', [
            'booking_id' => $booking->id,
            'tutor_id' => Auth::user()->tutor->id ?? 'null',
            'booking_tutor_id' => $booking->tutor_id,
            'request_data' => $request->all(),
        ]);

        if ($booking->tutor_id !== Auth::user()->tutor->id) {
            abort(403, 'Bạn không có quyền truy cập buổi học này.');
        }
        
        // Kiểm tra các điều kiện đổi lịch
        if (!in_array($booking->status, ['confirmed', 'scheduled'])) {
            return back()->with('error', 'Không thể đổi lịch buổi học này.');
        }
        
        if (now()->diffInHours($booking->start_time, false) < 24) {
            return back()->with('error', 'Không thể đổi lịch buổi học khi đã gần đến thời gian học (dưới 24 giờ).');
        }
        
        if ($booking->hasPendingRescheduleRequest()) {
            return back()->with('error', 'Buổi học này đã có yêu cầu đổi lịch đang chờ xử lý.');
        }
        
        // Validate dữ liệu đầu vào
        try {
            $validated = $request->validate([
                'reason' => 'required|string|max:500',
                'reschedule_options' => 'required|array|min:1|max:5',
                'reschedule_options.*.date' => 'required|date|after:today',
                'reschedule_options.*.start_time' => 'required|date_format:H:i',
                'reschedule_options.*.end_time' => 'required|date_format:H:i|after:reschedule_options.*.start_time',
                'notes' => 'nullable|string|max:500',
            ], [
                'reason.required' => 'Lý do đổi lịch không được bỏ trống',
                'reason.max' => 'Lý do đổi lịch không được vượt quá 500 ký tự',
                'reschedule_options.required' => 'Phải có ít nhất một tùy chọn thời gian mới',
                'reschedule_options.min' => 'Phải có ít nhất một tùy chọn thời gian mới',
                'reschedule_options.max' => 'Không được vượt quá 5 tùy chọn thời gian',
                'reschedule_options.*.date.required' => 'Ngày học không được bỏ trống',
                'reschedule_options.*.date.date' => 'Ngày học không hợp lệ',
                'reschedule_options.*.date.after' => 'Ngày học phải sau ngày hôm nay',
                'reschedule_options.*.start_time.required' => 'Thời gian bắt đầu không được bỏ trống',
                'reschedule_options.*.start_time.date_format' => 'Thời gian bắt đầu phải có định dạng giờ:phút',
                'reschedule_options.*.end_time.required' => 'Thời gian kết thúc không được bỏ trống',
                'reschedule_options.*.end_time.date_format' => 'Thời gian kết thúc phải có định dạng giờ:phút',
                'reschedule_options.*.end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu',
                'notes.max' => 'Ghi chú không được vượt quá 500 ký tự',
            ]);
        } catch (ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validation error in reschedule request', [
                'booking_id' => $booking->id,
                'errors' => $e->errors(),
            ]);
            return back()->withErrors($e->errors())->withInput();
        }
        
        // Tạo yêu cầu đổi lịch
        $rescheduleRequest = new BookingRescheduleRequest([
            'booking_id' => $booking->id,
            'reason' => $validated['reason'],
            'requested_by' => Auth::user()->tutor->id,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);
        
        $rescheduleRequest->save();
        
        // Tạo các tùy chọn thời gian
        foreach ($validated['reschedule_options'] as $option) {
            $date = $option['date'];
            $startTime = Carbon::parse($date . ' ' . $option['start_time']);
            $endTime = Carbon::parse($date . ' ' . $option['end_time']);
            
            // Đảm bảo thời gian kết thúc không sớm hơn thời gian bắt đầu (trường hợp qua ngày mới)
            if ($endTime->lt($startTime)) {
                $endTime->addDay();
            }
            
            $rescheduleRequest->options()->create([
                'start_time' => $startTime,
                'end_time' => $endTime,
                'is_selected' => false,
            ]);
        }
        
        // Cập nhật trạng thái
        $booking->update([
            'reschedule_requested' => true,
        ]);

        \Illuminate\Support\Facades\Log::info('Reschedule request created successfully', [
            'booking_id' => $booking->id,
            'reschedule_request_id' => $rescheduleRequest->id,
        ]);
        
        // Thông báo cho học sinh
        $notificationData = [
            'booking_id' => $booking->id,
            'tutor_name' => Auth::user()->name,
            'subject' => $booking->subject->name,
            'original_time' => $booking->start_time->format('d/m/Y H:i'),
            'reason' => $validated['reason'],
            'reschedule_request_id' => $rescheduleRequest->id,
        ];
        Notification::send($booking->student, new BookingRescheduleRequested($notificationData));
        
        return redirect()->route('tutor.bookings.show', $booking)
            ->with('success', 'Yêu cầu đổi lịch đã được gửi. Bạn sẽ nhận được thông báo khi học sinh phản hồi.');
    }
    
    /**
     * Xem chi tiết yêu cầu đổi lịch
     */
    public function show(BookingRescheduleRequest $rescheduleRequest)
    {
        $booking = $rescheduleRequest->booking;
        
        if ($booking->tutor_id !== Auth::user()->tutor->id) {
            abort(403, 'Bạn không có quyền truy cập yêu cầu đổi lịch này.');
        }
        
        return view('tutor.bookings.reschedule-show', [
            'rescheduleRequest' => $rescheduleRequest->load(['options', 'booking.student'])
        ]);
    }
    
    /**
     * Hủy yêu cầu đổi lịch (nếu chưa được phản hồi)
     */
    public function cancel(BookingRescheduleRequest $rescheduleRequest)
    {
        $booking = $rescheduleRequest->booking;
        
        if ($booking->tutor_id !== Auth::user()->tutor->id) {
            abort(403, 'Bạn không có quyền truy cập yêu cầu đổi lịch này.');
        }
        
        if (!$rescheduleRequest->isPending()) {
            return back()->with('error', 'Không thể hủy yêu cầu đổi lịch đã được phản hồi.');
        }
        
        // Cập nhật trạng thái yêu cầu
        $rescheduleRequest->update([
            'status' => 'rejected',
            'response_note' => 'Hủy bởi gia sư',
        ]);
        
        // Cập nhật booking
        $booking->update([
            'reschedule_requested' => false,
        ]);
        
        return redirect()->route('tutor.bookings.show', $booking)
            ->with('success', 'Yêu cầu đổi lịch đã được hủy.');
    }
}
