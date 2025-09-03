@extends('layouts.admin')

@section('page_title', 'Chi Tiết Đặt Lịch')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Chi Tiết Đặt Lịch #{{ $booking->id }}</h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.bookings.edit', $booking) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Chỉnh Sửa
                </a>
                @if(in_array($booking->status, ['completed', 'cancelled']))
                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa đặt lịch này?')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Xóa
                    </button>
                </form>
                @endif
            </div>
        </div>

        <div class="p-6">
            <!-- Thông tin chung -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin chung</h3>
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Trạng thái</h4>
                            <div class="mt-1">
                                @switch($booking->status)
                                    @case('pending')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Chờ xác nhận
                                        </span>
                                        @break
                                    @case('confirmed')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Đã xác nhận
                                        </span>
                                        @break
                                    @case('completed')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Hoàn thành
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Đã hủy
                                        </span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Thời gian đặt lịch</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $booking->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Thời gian bắt đầu</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $booking->start_time->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Thời gian kết thúc</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $booking->end_time->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Giá/giờ</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($booking->price_per_hour, 0, ',', '.') }} đ</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Tổng tiền</h4>
                            <p class="mt-1 text-sm text-gray-900 font-semibold">{{ number_format($booking->total_amount, 0, ',', '.') }} đ</p>
                        </div>
                        @if($booking->completed_at)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Hoàn thành vào</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $booking->completed_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                        @if($booking->cancelled_reason)
                        <div class="col-span-2">
                            <h4 class="text-sm font-medium text-gray-500">Lý do hủy</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $booking->cancelled_reason }}</p>
                            <p class="text-xs text-gray-500">Hủy bởi: {{ ucfirst($booking->cancelled_by) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Thông tin học sinh và gia sư -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Học sinh -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin học sinh</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <img class="h-12 w-12 rounded-full" src="{{ $booking->student->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($booking->student->name) }}" alt="{{ $booking->student->name }}">
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">{{ $booking->student->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $booking->student->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Gia sư -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin gia sư</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <img class="h-12 w-12 rounded-full" src="{{ $booking->tutor->avatar ? Storage::url($booking->tutor->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($booking->tutor->user->name) }}" alt="{{ $booking->tutor->user->name }}">
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">{{ $booking->tutor->user->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $booking->tutor->education_level }} - {{ $booking->tutor->university }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Môn học -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin môn học</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900">{{ $booking->subject->name }}</h4>
                    <p class="mt-1 text-sm text-gray-500">{{ $booking->subject->description }}</p>
                </div>
            </div>

            <!-- Ghi chú -->
            @if($booking->notes)
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ghi chú của học sinh</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-900">{{ $booking->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Ghi chú của admin -->
            @if(!empty($booking->admin_notes))
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ghi chú của admin</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-900">{{ $booking->admin_notes }}</p>
                </div>
            </div>
            @endif

            <!-- Thanh toán -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin thanh toán</h3>
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    @if($booking->payments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã giao dịch</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số tiền</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phương thức</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($booking->payments as $payment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->vnp_txn_ref }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($payment->amount < 0)
                                                    <span class="text-red-600">{{ number_format(abs($payment->amount), 0, ',', '.') }} đ (Hoàn tiền)</span>
                                                @else
                                                    {{ number_format($payment->amount, 0, ',', '.') }} đ
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ strtoupper($payment->payment_method) }}
                                                @if($payment->bank_code)
                                                    <span class="text-xs">({{ $payment->bank_code }})</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @switch($payment->status)
                                                    @case('pending')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            Đang xử lý
                                                        </span>
                                                        @break
                                                    @case('completed')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Thành công
                                                        </span>
                                                        @break
                                                    @case('failed')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Thất bại
                                                        </span>
                                                        @break
                                                    @case('refunded')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            Đã hoàn tiền
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            {{ $payment->status }}
                                                        </span>
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-sm text-gray-500">
                            Chưa có thông tin thanh toán.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 