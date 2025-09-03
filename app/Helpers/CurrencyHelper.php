<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Định dạng số tiền theo chuẩn Việt Nam
     *
     * @param float|int $amount Số tiền cần định dạng
     * @param bool $includeCurrency Có hiển thị đơn vị tiền tệ (VNĐ/đ) hay không
     * @param string $currencySymbol Ký hiệu tiền tệ (mặc định: 'đ')
     * @return string Chuỗi số tiền đã được định dạng
     */
    public static function formatVND($amount, $includeCurrency = true, $currencySymbol = 'đ')
    {
        // Làm tròn đến số nguyên
        $amount = round($amount);
        
        // Định dạng với dấu chấm phân cách hàng nghìn
        $formattedAmount = number_format($amount, 0, ',', '.');
        
        // Thêm ký hiệu tiền tệ nếu yêu cầu
        if ($includeCurrency) {
            return $formattedAmount . $currencySymbol;
        }
        
        return $formattedAmount;
    }
    
    /**
     * Định dạng số tiền theo chuẩn Việt Nam (sử dụng Intl)
     * 
     * @param float|int $amount Số tiền cần định dạng
     * @return string Chuỗi số tiền đã được định dạng
     */
    public static function formatVNDWithIntl($amount)
    {
        return (new \NumberFormatter('vi-VN', \NumberFormatter::CURRENCY))->format($amount);
    }
} 