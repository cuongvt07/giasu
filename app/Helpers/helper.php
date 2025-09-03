<?php

if (!function_exists('format_vnd')) {
    /**
     * Định dạng số tiền theo chuẩn Việt Nam
     *
     * @param float|int $amount Số tiền cần định dạng
     * @param bool $includeCurrency Có hiển thị đơn vị tiền tệ (VNĐ/đ) hay không
     * @param string $currencySymbol Ký hiệu tiền tệ (mặc định: 'đ')
     * @return string Chuỗi số tiền đã được định dạng
     */
    function format_vnd($amount, $includeCurrency = true, $currencySymbol = 'đ')
    {
        return \App\Helpers\CurrencyHelper::formatVND($amount, $includeCurrency, $currencySymbol);
    }
}

if (!function_exists('format_vnd_with_intl')) {
    /**
     * Định dạng số tiền theo chuẩn Việt Nam (sử dụng Intl)
     * 
     * @param float|int $amount Số tiền cần định dạng
     * @return string Chuỗi số tiền đã được định dạng
     */
    function format_vnd_with_intl($amount)
    {
        return \App\Helpers\CurrencyHelper::formatVNDWithIntl($amount);
    }
} 