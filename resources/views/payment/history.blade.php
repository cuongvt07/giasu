@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="pb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Lịch sử thanh toán</h1>
        <p class="mt-2 text-sm text-gray-600">
            Danh sách các giao dịch thanh toán của bạn.
        </p>
    </div>

    @if(session('success'))
        <div class="rounded-md bg-green-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Danh sách thanh toán -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if($payments->isEmpty())
            <div class="py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có thanh toán nào</h3>
                <p class="mt-1 text-sm text-gray-500">Bạn chưa thực hiện giao dịch thanh toán nào.</p>
                <div class="mt-6">
                    <a href="{{ route('tutors.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Tìm gia sư
                    </a>
                </div>
            </div>
        @else
            <ul role="list" class="divide-y divide-gray-200">
                @foreach($payments as $payment)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                        {{ $payment->booking->subject->name }} - {{ $payment->booking->tutor->user->name }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        @if($payment->status == 'completed')
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Đã thanh toán
                                            </p>
                                        @elseif($payment->status == 'pending')
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Đang xử lý
                                            </p>
                                        @else
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Thất bại
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ format_vnd($payment->amount) }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        Thời gian học: {{ $payment->booking->start_time->format('H:i') }} - {{ $payment->booking->end_time->format('H:i') }}, {{ $payment->booking->start_time->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <p>
                                        Mã giao dịch: {{ $payment->vnp_txn_ref }}
                                    </p>
                                    <p class="ml-4">
                                        @if($payment->paid_at)
                                            <time datetime="{{ $payment->paid_at }}">{{ $payment->paid_at->format('d/m/Y H:i') }}</time>
                                        @else
                                            <time datetime="{{ $payment->created_at }}">{{ $payment->created_at->format('d/m/Y H:i') }}</time>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 flex">
                                <a href="{{ route('student.bookings.show', $payment->booking_id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                    Xem chi tiết buổi học
                                    <span aria-hidden="true"> &rarr;</span>
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            
            <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 