<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\TutorEarningService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected $earningService;

    public function __construct(TutorEarningService $earningService)
    {
        $this->earningService = $earningService;
    }

    public function index()
    {
        $pendingBookings = Booking::with(['student', 'tutor.user', 'subject'])
            ->where('status', Booking::STATUS_PENDING)
            ->latest()
            ->paginate(5, ['*'], 'pending');
            
        $confirmedBookings = Booking::with(['student', 'tutor.user', 'subject'])
            ->where('status', Booking::STATUS_CONFIRMED)
            ->latest('start_time')
            ->paginate(5, ['*'], 'confirmed');
            
        $pendingCompletionBookings = Booking::with(['student', 'tutor.user', 'subject'])
            ->where('status', Booking::STATUS_CONFIRMED)
            ->where('end_time', '<', now())
            ->latest('end_time')
            ->paginate(5, ['*'], 'pending_completion');
            
        $completedBookings = Booking::with(['student', 'tutor.user', 'subject', 'tutorEarning'])
            ->where('status', Booking::STATUS_COMPLETED)
            ->latest('completed_at')
            ->paginate(5, ['*'], 'completed');
            
        return view('admin.bookings.index', compact(
            'pendingBookings', 
            'confirmedBookings', 
            'pendingCompletionBookings', 
            'completedBookings'
        ));
    }

    public function show(Booking $booking)
    {
        $booking->load(['student', 'tutor.user', 'subject', 'payments', 'tutorEarning']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $booking->load(['student', 'tutor.user', 'subject', 'tutorEarning']);
        return view('admin.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ], [
            'status.required' => 'Trạng thái không được bỏ trống',
            'status.in' => 'Trạng thái không hợp lệ',
        ]);

        $oldStatus = $booking->status;
        $newStatus = $validated['status'];
        
        // Nếu admin đang thay đổi trạng thái sang completed
        if ($oldStatus != $newStatus && $newStatus == Booking::STATUS_COMPLETED) {
            $validated['completed_at'] = now();
            
            // Nếu học sinh hoặc gia sư chưa xác nhận, hãy đánh dấu xác nhận thay họ
            if (!$booking->student_confirmed) {
                $booking->student_confirmed = true;
            }
            
            if (!$booking->tutor_confirmed) {
                $booking->tutor_confirmed = true;
            }
        }

        $booking->update($validated);

        // Nếu buổi học được đánh dấu là hoàn thành, tạo bản ghi thu nhập cho gia sư
        if ($newStatus == Booking::STATUS_COMPLETED && $booking->payment_status == Booking::PAYMENT_STATUS_PAID) {
            $this->earningService->createEarningRecord($booking);
        }

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Đã cập nhật trạng thái đặt lịch thành công.');
    }
    
    /**
     * Xác nhận thanh toán thủ công cho một buổi học
     */
    public function confirmPayment(Request $request, Booking $booking)
    {
        if ($booking->payment_status == Booking::PAYMENT_STATUS_PAID) {
            return back()->with('info', 'Buổi học này đã được thanh toán trước đó');
        }
        
        $booking->update([
            'payment_status' => Booking::PAYMENT_STATUS_PAID
        ]);
        
        // Tạo bản ghi thanh toán mới
        \App\Models\Payment::create([
            'booking_id' => $booking->id,
            'vnp_txn_ref' => 'ADMIN-' . time(),
            'amount' => $booking->total_amount,
            'status' => 'completed',
            'payment_method' => 'admin',
            'paid_at' => now(),
            'notes' => 'Thanh toán xác nhận bởi Admin'
        ]);
        
        // Nếu buổi học đang ở trạng thái pending, chuyển sang confirmed
        if ($booking->status == Booking::STATUS_PENDING) {
            $booking->update([
                'status' => Booking::STATUS_CONFIRMED
            ]);
        }
        
        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Đã xác nhận thanh toán cho buổi học này');
    }
    
    /**
     * Xử lý hoàn tiền cho học sinh
     */
    public function processRefund(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'refund_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'refund_reason' => ['required', 'string'],
        ], [
            'refund_percentage.required' => 'Phần trăm hoàn tiền không được bỏ trống',
            'refund_percentage.integer' => 'Phần trăm hoàn tiền phải là số nguyên',
            'refund_percentage.min' => 'Phần trăm hoàn tiền phải từ 0 đến 100',
            'refund_percentage.max' => 'Phần trăm hoàn tiền phải từ 0 đến 100',
            'refund_reason.required' => 'Lý do hoàn tiền không được bỏ trống',
        ]);
        
        if ($booking->payment_status != Booking::PAYMENT_STATUS_PAID) {
            return back()->with('error', 'Chỉ có thể hoàn tiền cho buổi học đã thanh toán');
        }
        
        $refundPercentage = $validated['refund_percentage'];
        $refundReason = $validated['refund_reason'];
        
        // Cập nhật trạng thái booking
        $newPaymentStatus = ($refundPercentage == 100) 
            ? Booking::PAYMENT_STATUS_REFUNDED 
            : Booking::PAYMENT_STATUS_PARTIAL_REFUNDED;
            
        $booking->update([
            'payment_status' => $newPaymentStatus,
            'refund_percentage' => $refundPercentage,
        ]);
        
        // Tạo bản ghi hoàn tiền
        $refundAmount = ($booking->total_amount * $refundPercentage) / 100;
        
        \App\Models\Payment::create([
            'booking_id' => $booking->id,
            'vnp_txn_ref' => 'REFUND-' . time(),
            'amount' => -$refundAmount, // Số tiền âm thể hiện hoàn tiền
            'status' => 'completed',
            'payment_method' => 'admin',
            'paid_at' => now(),
            'notes' => "Hoàn tiền {$refundPercentage}%. Lý do: {$refundReason}"
        ]);
        
        // Nếu hoàn tiền 100% và buổi học chưa bị hủy, đánh dấu là đã hủy
        if ($refundPercentage == 100 && $booking->status != Booking::STATUS_CANCELLED) {
            $booking->update([
                'status' => Booking::STATUS_CANCELLED,
                'cancelled_reason' => $refundReason,
                'cancelled_by' => 'admin'
            ]);
        }
        
        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', "Đã xử lý hoàn tiền {$refundPercentage}% cho buổi học này");
    }

    public function destroy(Booking $booking)
    {
        // Chỉ cho phép xóa các đặt lịch đã hoàn thành hoặc đã hủy
        if (!in_array($booking->status, [Booking::STATUS_COMPLETED, Booking::STATUS_CANCELLED])) {
            return back()->with('error', 'Chỉ có thể xóa các đặt lịch đã hoàn thành hoặc đã hủy.');
        }

        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Đặt lịch đã được xóa thành công.');
    }
} 