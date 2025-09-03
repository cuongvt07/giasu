<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Tạo thanh toán cho booking
     */
    public function createPayment(Booking $booking)
    {
        // Kiểm tra quyền sở hữu booking
        if ($booking->student_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thanh toán buổi học này');
        }

        // Kiểm tra trạng thái booking
        if ($booking->status === Booking::STATUS_CANCELLED) {
            return redirect()->route('student.bookings.index')
                ->with('error', 'Không thể thanh toán buổi học đã hủy');
        }
        
        // Kiểm tra nếu booking đã được thanh toán
        if ($booking->payment_status === Booking::PAYMENT_STATUS_PAID) {
            return redirect()->route('student.bookings.show', $booking)
                ->with('info', 'Buổi học này đã được thanh toán');
        }

        // Tạo mã giao dịch
        $txnRef = 'PAY' . time() . Str::random(6);
        
        // Tạo thanh toán trong hệ thống
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'vnp_txn_ref' => $txnRef,
            'amount' => $booking->total_amount,
            'status' => 'pending',
        ]);
        
        // Tạo URL thanh toán với VNPay (ví dụ)
        $vnpUrl = $this->createPaymentUrl($payment, $booking);
            
        return view('payment.checkout', compact('booking', 'payment', 'vnpUrl'));
    }
    
    /**
     * Tạo URL thanh toán cho VNPay
     */
    private function createPaymentUrl($payment, $booking)
    {
        // Thông tin cấu hình thanh toán
        $vnp_TmnCode = env('VNPAY_TMN_CODE', 'your_tmn_code');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'your_hash_secret');
        $VNPAY_URL = env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $vnp_Returnurl = route('payment.callback');
        
        // Thông tin đơn hàng
        $vnp_TxnRef = $payment->vnp_txn_ref;
        $vnp_OrderInfo = "Thanh toán buổi học " . $booking->subject->name;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $payment->amount * 100; // VNPay yêu cầu số tiền * 100
        
        // Thông tin ngân hàng và khách hàng
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = request()->ip();
        
        // Tạo dữ liệu gửi đi
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );
        
        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        
        // Sắp xếp dữ liệu theo key
        ksort($inputData);
        $query = http_build_query($inputData);
        
        // Tạo chữ ký
        $vnpSecureHash = hash_hmac('sha512', $query, $vnp_HashSecret);
        $vnpUrl = $VNPAY_URL . "?" . $query . '&vnp_SecureHash=' . $vnpSecureHash;
        
        return $vnpUrl;
        }
    
    /**
     * Xử lý callback từ VNPay
     */
    public function handleCallback(Request $request)
    {
        Log::info('Payment callback received', $request->all());
        
        $vnp_TmnCode = env('VNPAY_TMN_CODE', 'your_tmn_code');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'your_hash_secret');
            
        // Lấy dữ liệu từ VNPay gửi về
        $vnp_TxnRef = $request->vnp_TxnRef;
        $vnp_Amount = $request->vnp_Amount / 100; // Chia cho 100 để lấy số tiền thực
        $vnp_ResponseCode = $request->vnp_ResponseCode;
            
        // Tìm payment theo mã giao dịch
        $payment = Payment::where('vnp_txn_ref', $vnp_TxnRef)->first();
        
        if (!$payment) {
            Log::error('Payment not found', ['txn_ref' => $vnp_TxnRef]);
            return redirect()->route('student.bookings.index')
                ->with('error', 'Không tìm thấy thông tin thanh toán');
        }
        
        // Kiểm tra mã phản hồi: 00 là thành công, còn lại là thất bại
        $success = ($vnp_ResponseCode == '00');
        
        DB::beginTransaction();
        try {
            // Cập nhật thông tin payment
            $paymentData = [
                'status' => $success ? 'completed' : 'failed',
                'response_code' => $vnp_ResponseCode,
                'bank_code' => $request->vnp_BankCode ?? null,
                'card_type' => $request->vnp_CardType ?? null,
                'response_data' => json_encode($request->all()),
            ];
            
            if ($success) {
                $paymentData['paid_at'] = now();
            }
            
            $payment->update($paymentData);
            
            // Nếu thanh toán thành công, cập nhật trạng thái booking
            if ($success) {
                // Cập nhật trạng thái thanh toán của booking
                $booking = $payment->booking;
                $booking->update([
                    'payment_status' => Booking::PAYMENT_STATUS_PAID
                ]);
                
                // Tối ưu: Luôn chuyển sang confirmed sau khi thanh toán, bỏ qua bước gia sư xác nhận
                if ($booking->status === Booking::STATUS_PENDING) {
                    $booking->update([
                        'status' => Booking::STATUS_CONFIRMED
                    ]);
                    }
                    
                // Gửi thông báo cho gia sư về việc có booking mới đã thanh toán
                // TODO: Thêm notification sau
            }
            
            DB::commit();
            
            // Chuyển hướng người dùng
            if ($success) {
                return redirect()->route('student.bookings.show', $payment->booking_id)
                    ->with('success', 'Thanh toán thành công. Buổi học đã được xác nhận tự động.');
            } else {
                return redirect()->route('payment.create', $payment->booking_id)
                    ->with('error', 'Thanh toán thất bại. Vui lòng thử lại.');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing payment callback', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('student.bookings.index')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán');
        }
    }
    
    /**
     * Xem lịch sử thanh toán (cho học sinh)
     */
    public function history()
    {
        $payments = Payment::with(['booking.subject', 'booking.tutor.user'])
            ->whereHas('booking', function($query) {
                $query->where('student_id', Auth::id());
            })
            ->latest()
            ->paginate(10);
            
        return view('payment.history', compact('payments'));
    }
} 