@extends('layouts.app')

@section('page_title', 'Yêu Cầu Đổi Lịch')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Yêu Cầu Đổi Lịch</h1>
        <a href="{{ route('student.bookings.index') }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
            Quay lại danh sách buổi học
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-medium text-gray-700 mb-4">Yêu Cầu Đổi Lịch Chờ Phản Hồi</h2>
        
        @if($pendingRequests->count() > 0)
            <div class="space-y-4">
                @foreach($pendingRequests as $request)
                    <div class="border rounded-lg p-4 bg-yellow-50 border-yellow-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-800">{{ $request->booking->subject->name }} với {{ $request->booking->tutor->user->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Buổi học hiện tại: <span class="font-medium">{{ $request->booking->start_time->format('d/m/Y H:i') }} - {{ $request->booking->end_time->format('H:i') }}</span>
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Lý do đổi lịch: <span class="font-medium">{{ $request->reason }}</span>
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Yêu cầu lúc: <span class="font-medium">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                                </p>
                            </div>
                            <a href="{{ route('student.reschedules.show', $request) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-4">Không có yêu cầu đổi lịch nào đang chờ phản hồi</p>
        @endif
    </div>
</div>
@endsection 