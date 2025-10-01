<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $tutor = Auth::user()->tutor;
        $upcomingBookings = Booking::with(['student', 'subject', 'reasons'])
            ->where('tutor_id', $tutor->id)
            ->where('status', Booking::STATUS_CONFIRMED)
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->paginate(5, ['*'], 'upcoming');

        $pastBookings = Booking::with(['student', 'subject', 'reasons'])
            ->where('tutor_id', $tutor->id)
            ->whereIn('status', [Booking::STATUS_COMPLETED, Booking::STATUS_CANCELLED])
            ->orderBy('start_time', 'desc')
            ->paginate(5, ['*'], 'past');

        $pendingCompletionBookings = Booking::with(['student', 'subject', 'reasons'])
            ->where('tutor_id', $tutor->id)
            ->where('status', Booking::STATUS_CONFIRMED)
            ->where('end_time', '<', now())
            ->orderBy('end_time', 'desc')
            ->paginate(5, ['*'], 'pending_completion');

        return view('tutor.bookings.index', compact('upcomingBookings', 'pastBookings', 'pendingCompletionBookings'));
    }

    public function show(Booking $booking)
    {
        $tutor = Auth::user()->tutor;
        
        if ($booking->tutor_id !== $tutor->id) {
            abort(403, 'Bạn không có quyền xem buổi học này');
        }
        
        $booking->load(['student', 'subject', 'payments', 'tutorEarning']);

        return view('tutor.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $tutor = Auth::user()->tutor;
        
        if ($booking->tutor_id !== $tutor->id) {
            abort(403, 'Bạn không có quyền cập nhật buổi học này');
        }

        $validated = $request->validate([
            'status' => 'required|in:confirmed,pending',
            'notes' => 'nullable|string',
        ], [
            'status.required' => 'Trạng thái không được bỏ trống',
            'status.in' => 'Trạng thái không hợp lệ',
        ]);

        $booking->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('tutor.bookings.show', $booking)
            ->with('success', 'Đã cập nhật trạng thái buổi học thành công');
    }
    
    /**
     * Báo cáo vấn đề với buổi học
     */
    public function reportIssue(Request $request, Booking $booking)
    {
        $tutor = Auth::user()->tutor;
        
        if ($booking->tutor_id !== $tutor->id) {
            abort(403, 'Bạn không có quyền báo cáo về buổi học này');
        }
        
        $validated = $request->validate([
            'issue_type' => 'required|in:student_absent,technical_issue,other',
            'issue_description' => 'required|string|max:1000',
        ], [
            'issue_type.required' => 'Loại vấn đề không được bỏ trống',
            'issue_type.in' => 'Loại vấn đề không hợp lệ',
            'issue_description.required' => 'Mô tả vấn đề không được bỏ trống',
            'issue_description.string' => 'Mô tả vấn đề phải là chuỗi ký tự',
            'issue_description.max' => 'Mô tả vấn đề không được vượt quá 1000 ký tự',
        ]);
        
        // Lưu vấn đề vào booking notes
        $issueNote = 'Vấn đề: ' . $validated['issue_type'] . "\n" . $validated['issue_description'];
        
        $booking->update([
            'notes' => $booking->notes . "\n\n" . $issueNote
        ]);
        
        // Gửi thông báo cho admin về vấn đề
        // TODO: Thêm notification sau
        
        return redirect()->route('tutor.bookings.show', $booking)
            ->with('success', 'Báo cáo vấn đề đã được ghi nhận. Quản trị viên sẽ liên hệ với bạn sớm');
    }

    /**
     * Xác nhận hoàn thành buổi học (từ phía gia sư)
     */
    public function confirmCompletion(Request $request, Booking $booking)
    {
        $tutor = Auth::user()->tutor;
        
        if ($booking->tutor_id !== $tutor->id) {
            abort(403, 'Bạn không có quyền xác nhận buổi học này');
        }
        
        // Kiểm tra nếu buổi học đã kết thúc
        if (!$booking->hasEnded()) {
            return back()->with('error', 'Buổi học chưa kết thúc, không thể xác nhận hoàn thành');
        }
        
        // Chỉ cho phép xác nhận nếu trạng thái là confirmed
        if ($booking->status !== Booking::STATUS_CONFIRMED) {
            return back()->with('error', 'Không thể xác nhận buổi học ở trạng thái hiện tại');
        }
        
        $validated = $request->validate([
            'completion_notes' => 'nullable|string|max:500',
        ], [
            'completion_notes.string' => 'Ghi chú phải là chuỗi ký tự',
            'completion_notes.max' => 'Ghi chú không được vượt quá 500 ký tự',
        ]);
        
        // Đơn giản hóa: Đánh dấu hoàn thành ngay lập tức
        $booking->update([
            'status' => Booking::STATUS_COMPLETED,
            'completed_at' => now(),
            'completion_notes' => $validated['completion_notes'] ?? null
        ]);
            
        // Tạo bản ghi thu nhập cho gia sư
        app(\App\Services\TutorEarningService::class)->createEarningRecord($booking);
        
        return redirect()->route('tutor.bookings.show', $booking)
            ->with('success', 'Cảm ơn bạn đã xác nhận hoàn thành buổi học');
    }
    
    /**
     * Trả về danh sách Lịch sử thay đổi của một booking (JSON).
     */
    public function reasons($bookingId)
    {
        $tutor = Auth::user()->tutor;
        $booking = \App\Models\Booking::with(['reasons', 'reasons.requester'])->where('tutor_id', $tutor->id)->findOrFail($bookingId);
        $reasons = $booking->reasons->sortByDesc('created_at')->values()->map(function($item) {
            // Việt hóa status
            $statusMap = [
            'pending' => 'Chờ duyệt',
            'accepted' => 'Đã chấp nhận',
            'rejected' => 'Từ chối'
            ];
            return [
            'reason' => $item->reason,
            'created_at' => $item->created_at->format('d/m/Y H:i'),
            'created_at_iso' => $item->created_at->toIso8601String(),
            'requester' => $item->requester ? $item->requester->name : 'Hệ thống',
            'status' => $item->status,
            'status_vi' => $statusMap[$item->status] ?? $item->status,
            'notes' => $item->notes,
            'response_note' => $item->response_note,
            ];
        });
        return response()->json($reasons);
    }
}