<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingRescheduleRequest;
use App\Models\BookingRescheduleOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RescheduleAccepted;
use App\Notifications\RescheduleRejected;

class RescheduleController extends Controller
{
    /**
     * Hiển thị danh sách yêu cầu đổi lịch
     */
    public function index()
    {
        $pendingRequests = BookingRescheduleRequest::whereHas('booking', function ($query) {
                $query->where('student_id', Auth::id());
            })
            ->where('status', 'pending')
            ->with(['booking.tutor.user', 'booking.subject', 'options'])
            ->latest()
            ->get();
            
        return view('student.bookings.reschedules', compact('pendingRequests'));
    }
    
    /**
     * Hiển thị chi tiết yêu cầu đổi lịch
     */
    public function show(BookingRescheduleRequest $rescheduleRequest)
    {
        $booking = $rescheduleRequest->booking;
        
        if ($booking->student_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập yêu cầu đổi lịch này.');
        }
        
        return view('student.bookings.reschedule-show', [
            'rescheduleRequest' => $rescheduleRequest->load(['options', 'booking.tutor.user', 'booking.subject']),
        ]);
    }
    
    /**
     * Xử lý phản hồi yêu cầu đổi lịch
     */
    public function respond(Request $request, BookingRescheduleRequest $rescheduleRequest)
    {
        $booking = $rescheduleRequest->booking;
        
        if ($booking->student_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập yêu cầu đổi lịch này.');
        }
        
        // Kiểm tra trạng thái yêu cầu
        if (!$rescheduleRequest->isPending()) {
            return back()->with('error', 'Yêu cầu đổi lịch này đã được xử lý.');
        }
        
        $validated = $request->validate([
            'response' => 'required|in:accept,reject',
            'option_id' => 'required_if:response,accept|nullable|exists:booking_reschedule_options,id',
            'response_note' => 'nullable|string|max:500',
        ], [
            'response.required' => 'Vui lòng chọn phản hồi',
            'response.in' => 'Phản hồi không hợp lệ',
            'option_id.required_if' => 'Vui lòng chọn một tùy chọn thời gian',
            'option_id.exists' => 'Tùy chọn thời gian không hợp lệ',
            'response_note.max' => 'Ghi chú phản hồi không được vượt quá 500 ký tự',
        ]);
        
        if ($validated['response'] === 'accept') {
            // Nếu chấp nhận, phải chọn một tùy chọn thời gian
            if (empty($validated['option_id'])) {
                return back()->with('error', 'Vui lòng chọn một tùy chọn thời gian.');
            }
            
            $option = BookingRescheduleOption::findOrFail($validated['option_id']);
            
            // Kiểm tra xem option có thuộc về rescheduleRequest không
            if ($option->booking_reschedule_request_id !== $rescheduleRequest->id) {
                return back()->with('error', 'Tùy chọn thời gian không hợp lệ.');
            }
            
            // Cập nhật trạng thái yêu cầu đổi lịch
            $rescheduleRequest->update([
                'status' => 'accepted',
                'response_note' => $validated['response_note'],
            ]);
            
            // Cập nhật option đã chọn
            $option->update(['is_selected' => true]);
            
            // Cập nhật booking với thời gian mới
            $booking->update([
                'start_time' => $option->start_time,
                'end_time' => $option->end_time,
                'rescheduled_at' => now(),
                'rescheduled_reason' => $rescheduleRequest->reason,
                'reschedule_requested' => false,
            ]);
            
            // Thông báo cho gia sư
            $notificationData = [
                'booking_id' => $booking->id,
                'student_name' => Auth::user()->name,
                'subject' => $booking->subject->name,
                'new_time' => $option->start_time->format('d/m/Y H:i'),
            ];
            Notification::send($booking->tutor->user, new RescheduleAccepted($notificationData));
            
            $message = 'Bạn đã chấp nhận đổi lịch buổi học. Thời gian mới đã được cập nhật.';
        } else {
            // Cập nhật trạng thái yêu cầu đổi lịch
            $rescheduleRequest->update([
                'status' => 'rejected',
                'response_note' => $validated['response_note'],
            ]);
            
            // Cập nhật booking
            $booking->update([
                'reschedule_requested' => false,
            ]);
            
            // Thông báo cho gia sư
            $notificationData = [
                'booking_id' => $booking->id,
                'student_name' => Auth::user()->name,
                'subject' => $booking->subject->name,
                'response_note' => $validated['response_note'] ?? 'Không có lý do cụ thể',
            ];
            Notification::send($booking->tutor->user, new RescheduleRejected($notificationData));
            
            $message = 'Bạn đã từ chối yêu cầu đổi lịch. Buổi học sẽ vẫn diễn ra theo lịch ban đầu.';
        }
        
        return redirect()->route('student.bookings.show', $booking)
            ->with('success', $message);
    }
}
