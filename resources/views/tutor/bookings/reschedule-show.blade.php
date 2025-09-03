@extends('layouts.tutor')

@section('page_title', 'Chi Tiết Yêu Cầu Đổi Lịch')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Chi Tiết Yêu Cầu Đổi Lịch</h1>
        <a href="{{ route('tutor.bookings.show', $rescheduleRequest->booking) }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
            Quay lại buổi học
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-700 mb-4">Thông Tin Buổi Học</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Học sinh:</p>
                <p class="font-medium">{{ $rescheduleRequest->booking->student->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Môn học:</p>
                <p class="font-medium">{{ $rescheduleRequest->booking->subject->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Thời gian hiện tại:</p>
                <p class="font-medium">{{ $rescheduleRequest->booking->start_time->format('d/m/Y H:i') }} - {{ $rescheduleRequest->booking->end_time->format('H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Trạng thái buổi học:</p>
                <p class="font-medium">
                    @switch($rescheduleRequest->booking->status)
                        @case('pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Chờ xác nhận
                            </span>
                            @break
                        @case('confirmed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Đã xác nhận
                            </span>
                            @break
                        @case('scheduled')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                Đã lên lịch
                            </span>
                            @break
                        @default
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $rescheduleRequest->booking->status }}
                            </span>
                    @endswitch
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium text-gray-700">Thông Tin Yêu Cầu Đổi Lịch</h2>
            <span class="
                @if($rescheduleRequest->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($rescheduleRequest->status == 'accepted') bg-green-100 text-green-800
                @elseif($rescheduleRequest->status == 'rejected') bg-red-100 text-red-800
                @endif
                px-2.5 py-0.5 rounded-full text-xs font-medium
            ">
                @if($rescheduleRequest->status == 'pending') Đang chờ phản hồi
                @elseif($rescheduleRequest->status == 'accepted') Đã chấp nhận
                @elseif($rescheduleRequest->status == 'rejected') Đã từ chối
                @endif
            </span>
        </div>
        
        <div class="grid grid-cols-1 gap-4 mb-4">
            <div>
                <p class="text-sm text-gray-600">Ngày yêu cầu:</p>
                <p class="font-medium">{{ $rescheduleRequest->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Lý do đổi lịch:</p>
                <p class="font-medium">{{ $rescheduleRequest->reason }}</p>
            </div>
            @if($rescheduleRequest->notes)
            <div>
                <p class="text-sm text-gray-600">Ghi chú bổ sung:</p>
                <p class="font-medium">{{ $rescheduleRequest->notes }}</p>
            </div>
            @endif
            @if($rescheduleRequest->response_note)
            <div>
                <p class="text-sm text-gray-600">Phản hồi từ học sinh:</p>
                <p class="font-medium">{{ $rescheduleRequest->response_note }}</p>
            </div>
            @endif
        </div>
        
        @if($rescheduleRequest->status == 'pending')
        <div class="mt-4 flex justify-end">
            <form action="{{ route('tutor.reschedules.cancel', $rescheduleRequest) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Bạn có chắc chắn muốn hủy yêu cầu đổi lịch này?')">
                    Hủy yêu cầu
                </button>
            </form>
        </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-medium text-gray-700 mb-4">Tùy Chọn Thời Gian</h2>
        
        <div class="space-y-4">
            @forelse($rescheduleRequest->options as $option)
            <div class="p-4 border rounded-lg @if($option->is_selected) bg-green-50 border-green-200 @else bg-gray-50 @endif">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium">{{ $option->start_time->format('d/m/Y') }}</p>
                        <p class="text-gray-600">{{ $option->start_time->format('H:i') }} - {{ $option->end_time->format('H:i') }}</p>
                    </div>
                    @if($option->is_selected)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Đã chọn
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Không có tùy chọn thời gian nào</p>
            @endforelse
        </div>
    </div>
</div>
@endsection 