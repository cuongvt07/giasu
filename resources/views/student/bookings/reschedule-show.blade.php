@extends('layouts.app')

@section('page_title', 'Chi Tiết Yêu Cầu Đổi Lịch')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Chi Tiết Yêu Cầu Đổi Lịch</h1>
        <a href="{{ route('student.bookings.index') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
            Quay lại
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-700 mb-4">Thông Tin Buổi Học</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-sm text-gray-600">Gia sư:</p>
                <p class="font-medium">{{ $rescheduleRequest->booking->tutor->user->name }}</p>
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
                <p class="text-sm text-gray-600">Trạng thái:</p>
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

        <div class="border-t border-gray-200 pt-4">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Yêu Cầu Đổi Lịch</h3>
            <div class="grid grid-cols-1 gap-4 mb-4">
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
            </div>
        </div>
    </div>

    @if($rescheduleRequest->isPending())
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-700 mb-4">Các thời gian thay thế</h2>
        <p class="text-sm text-gray-500 mb-4">Vui lòng chọn một trong các thời gian sau hoặc từ chối yêu cầu đổi lịch.</p>
        
        <form action="{{ route('student.reschedules.respond', $rescheduleRequest) }}" method="POST">
            @csrf
            <div class="space-y-4 mb-6">
                @foreach($rescheduleRequest->options as $option)
                <div class="border rounded-lg p-4 bg-gray-50">
                    <div class="flex items-center">
                        <input id="option_{{ $option->id }}" name="option_id" type="radio" value="{{ $option->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        <label for="option_{{ $option->id }}" class="ml-3 flex-1">
                            <span class="block font-medium text-gray-700">{{ $option->start_time->format('d/m/Y') }}</span>
                            <span class="block text-gray-500">{{ $option->start_time->format('H:i') }} - {{ $option->end_time->format('H:i') }}</span>
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mb-6">
                <label for="response_note" class="block text-sm font-medium text-gray-700 mb-1">Ghi chú phản hồi</label>
                <textarea id="response_note" name="response_note" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="submit" name="response" value="reject" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="return confirm('Bạn có chắc chắn muốn từ chối yêu cầu đổi lịch này?')">
                    Từ chối đổi lịch
                </button>
                <button type="submit" name="response" value="accept" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Chấp nhận thời gian đã chọn
                </button>
            </div>
        </form>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-700">Trạng Thái Yêu Cầu</h2>
            <span class="
                @if($rescheduleRequest->status == 'accepted') bg-green-100 text-green-800
                @elseif($rescheduleRequest->status == 'rejected') bg-red-100 text-red-800
                @endif
                px-2.5 py-0.5 rounded-full text-xs font-medium
            ">
                @if($rescheduleRequest->status == 'accepted') Đã chấp nhận
                @elseif($rescheduleRequest->status == 'rejected') Đã từ chối
                @endif
            </span>
        </div>
        
        @if($rescheduleRequest->status == 'accepted')
            <div class="p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600">Thời gian mới:</p>
                <p class="font-medium">
                    @php
                        $selectedOption = $rescheduleRequest->options()->where('is_selected', true)->first();
                    @endphp
                    @if($selectedOption)
                        {{ $selectedOption->start_time->format('d/m/Y H:i') }} - {{ $selectedOption->end_time->format('H:i') }}
                    @else
                        Không tìm thấy thông tin
                    @endif
                </p>
            </div>
        @endif
        
        @if($rescheduleRequest->response_note)
            <div class="mt-4">
                <p class="text-sm text-gray-600">Ghi chú phản hồi của bạn:</p>
                <p class="font-medium">{{ $rescheduleRequest->response_note }}</p>
            </div>
        @endif
    </div>
    @endif
</div>
@endsection 