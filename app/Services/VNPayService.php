<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VNPayService
{
    private $vnp_TmnCode;
    private $vnp_HashSecret;
    private $vnp_Url;
    private $vnp_Returnurl;

    public function __construct()
    {
        $this->vnp_TmnCode = config('vnpay.tmn_code');
        $this->vnp_HashSecret = config('vnpay.hash_secret');
        $this->vnp_Url = config('vnpay.url');
        $this->vnp_Returnurl = config('vnpay.return_url');
    }

    public function createPayment(Booking $booking)
    {
        $vnp_TxnRef = Str::random(16); // Mã giao dịch
        $vnp_OrderInfo = "Thanh toan buoi hoc ID: " . $booking->id;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $booking->total_amount * 100; // Nhân 100 vì VNPay yêu cầu
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();
        $vnp_CreateDate = Carbon::now()->format('YmdHis');

        // Thêm booking_id vào return URL để có thể định danh booking sau khi quay lại
        $returnUrl = url($this->vnp_Returnurl);
        if (strpos($returnUrl, '?') !== false) {
            $returnUrl .= '&';
        } else {
            $returnUrl .= '?';
        }
        $returnUrl .= 'booking_id=' . $booking->id;
        
        // Thêm user_id nếu người dùng đã đăng nhập
        if (Auth::check()) {
            $returnUrl .= '&user_id=' . Auth::id();
        }

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $returnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Tạo payment record
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'vnp_txn_ref' => $vnp_TxnRef,
            'amount' => $booking->total_amount,
            'status' => 'pending'
        ]);

        $vnp_Url = $this->vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        return [
            'payment' => $payment,
            'redirect_url' => $vnp_Url
        ];
    }

    public function processCallback($request)
    {
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);
        $vnp_SecureHash = $request->vnp_SecureHash;

        if ($secureHash == $vnp_SecureHash) {
            $payment = Payment::where('vnp_txn_ref', $request->vnp_TxnRef)->first();
            
            if (!$payment) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy giao dịch. Vui lòng liên hệ với bộ phận hỗ trợ để được trợ giúp.'
                ];
            }

            // Nếu có user_id trong request, có thể sử dụng để login người dùng nếu cần
            $userId = $request->user_id;
            $bookingId = $request->booking_id;

            if ($request->vnp_ResponseCode == '00') {
                $payment->update([
                    'status' => 'completed',
                    'bank_code' => $request->vnp_BankCode,
                    'bank_tran_no' => $request->vnp_BankTranNo,
                    'card_type' => $request->vnp_CardType,
                    'paid_at' => Carbon::now(),
                    'response_data' => $request->all()
                ]);

                // Cập nhật trạng thái booking
                $payment->booking->update([
                    'status' => 'confirmed'
                ]);

                // Định dạng số tiền cho thông báo
                $formattedAmount = number_format($payment->amount, 0, ',', '.') . 'đ';

                return [
                    'success' => true,
                    'message' => "Thanh toán thành công! Số tiền: {$formattedAmount}. Buổi học của bạn đã được xác nhận.",
                    'user_id' => $userId,
                    'booking_id' => $bookingId,
                    'amount' => $payment->amount,
                    'formatted_amount' => $formattedAmount
                ];
            }

            $payment->update([
                'status' => 'failed',
                'response_data' => $request->all()
            ]);

            $errorMessages = [
                '01' => 'Giao dịch đã tồn tại',
                '02' => 'Merchant không hợp lệ',
                '03' => 'Dữ liệu gửi sang không đúng định dạng',
                '04' => 'Khởi tạo GD không thành công do Website đang bị tạm khóa',
                '05' => 'Giao dịch không thành công do: Quý khách nhập sai mật khẩu quá số lần quy định',
                '06' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu',
                '07' => 'Giao dịch bị nghi ngờ gian lận',
                '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa',
                '10' => 'Giao dịch không thành công do: Quý khách nhập sai CSC',
                '11' => 'Giao dịch không thành công do: Tài khoản không đủ số dư',
                '12' => 'Giao dịch không thành công do: Thẻ hết hạn sử dụng',
                '13' => 'Giao dịch không thành công do: Vượt quá hạn mức thanh toán',
                '24' => 'Giao dịch không thành công do: Quý khách hủy giao dịch',
                '51' => 'Giao dịch không thành công do: Tài khoản không đủ số dư',
                '65' => 'Giao dịch không thành công do: Tài khoản quý khách đã vượt quá hạn mức giao dịch',
                '75' => 'Ngân hàng thanh toán đang bảo trì',
                '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán nhiều lần',
                '99' => 'Lỗi không xác định'
            ];

            $errorCode = $request->vnp_ResponseCode;
            $errorMessage = $errorMessages[$errorCode] ?? 'Thanh toán thất bại. Vui lòng thử lại sau hoặc liên hệ hỗ trợ.';

            return [
                'success' => false,
                'message' => $errorMessage,
                'error_code' => $errorCode,
                'user_id' => $userId,
                'booking_id' => $bookingId
            ];
        }

        return [
            'success' => false,
            'message' => 'Chữ ký không hợp lệ. Vui lòng không thay đổi dữ liệu trong quá trình thanh toán.'
        ];
    }
} 