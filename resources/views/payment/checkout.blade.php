@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="pb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Thanh toán buổi học</h1>
        <p class="mt-2 text-sm text-gray-600">
            Vui lòng kiểm tra thông tin và hoàn tất thanh toán để đặt lịch học với gia sư.
        </p>
    </div>

    @if(session('error'))
        <div class="rounded-md bg-red-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">{{ session('error') }}</h3>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="rounded-md bg-green-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Thông tin buổi học
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Chi tiết về gia sư, thời gian và môn học.
            </p>
        </div>
        
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Gia sư
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $booking->tutor->user->name }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Môn học
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $booking->subject->name }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Thời gian
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}, {{ $booking->start_time->format('d/m/Y') }}
                        @php
                            $durationHours = $booking->start_time->diffInMinutes($booking->end_time) / 60;
                            $durationHours = round($durationHours * 2) / 2; // Làm tròn đến 0.5
                        @endphp
                        <span class="text-sm text-gray-500 ml-2">({{ $durationHours }} giờ)</span>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Đơn giá / giờ
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ format_vnd($booking->price_per_hour) }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Tổng thành tiền
                    </dt>
                    <dd class="mt-1 text-sm font-bold text-indigo-600 sm:mt-0 sm:col-span-2">
                        {{ format_vnd($booking->total_amount) }}
                    </dd>
                </div>
                @if($booking->notes)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Ghi chú
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $booking->notes }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
    
    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
            Phương thức thanh toán
        </h3>
        
        <div class="space-y-6">
            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                <div class="flex items-center">
                    <input id="payment-vnpay" name="payment-method" type="radio" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                    <label for="payment-vnpay" class="ml-3 block text-sm font-medium text-gray-700">
                        Thanh toán qua VNPay (Thẻ ATM, Thẻ tín dụng, Ví điện tử)
                    </label>
                </div>
                <div class="mt-2 flex flex-wrap gap-2">
                    <img src="{{ asset('images/vnpay.png') }}" alt="VNPay" class="h-8 object-contain">
                </div>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-md border border-blue-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Lưu ý:</strong> Sau khi thanh toán thành công, buổi học sẽ được xác nhận và gửi thông báo cho gia sư. Bạn có thể hủy buổi học trước 24 giờ để được hoàn tiền 100%.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between">
                <a href="{{ route('student.bookings.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Quay lại
                </a>
                
                <a href="{{ $vnpUrl }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                    Thanh toán ngay {{ format_vnd($booking->total_amount) }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 