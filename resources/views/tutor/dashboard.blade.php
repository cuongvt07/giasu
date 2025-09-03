@extends('layouts.tutor')

@section('content')
<!-- Dashboard Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Xin chào, {{ Auth::user()->name }}</h1>
    <p class="mt-1 text-lg text-gray-600">Chào mừng bạn quay trở lại với trang quản lý gia sư.</p>
</div>

<!-- Thống kê tổng quan -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Thống kê lịch dạy -->
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 overflow-hidden shadow-lg rounded-xl text-white transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm font-medium uppercase tracking-wider">
                        Lịch Dạy Hôm Nay
                    </p>
                    <p class="mt-2 text-4xl font-extrabold">
                        {{ $todayBookings }}
                    </p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-6 flex items-center">
                <a href="{{ route('tutor.bookings.index') }}" class="inline-flex items-center text-sm font-medium text-white hover:text-indigo-100">
                    Xem chi tiết
                    <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Thống kê học sinh -->
    <div class="bg-gradient-to-br from-emerald-500 to-green-600 overflow-hidden shadow-lg rounded-xl text-white transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium uppercase tracking-wider">
                        Học Sinh Đã Dạy
                    </p>
                    <p class="mt-2 text-4xl font-extrabold">
                        {{ $totalStudents }}
                    </p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-6 flex items-center">
                <span class="text-sm font-medium text-green-100">
                    Đã kết nối thành công
                </span>
            </div>
        </div>
    </div>

    <!-- Thống kê giờ dạy -->
    <div class="bg-gradient-to-br from-amber-500 to-yellow-600 overflow-hidden shadow-lg rounded-xl text-white transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium uppercase tracking-wider">
                        Tổng Giờ Dạy
                    </p>
                    <p class="mt-2 text-4xl font-extrabold">
                        {{ $totalTeachingHours }}
                    </p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-6 flex items-center">
                <span class="text-sm font-medium text-yellow-100">
                    Giờ kinh nghiệm giảng dạy
                </span>
            </div>
        </div>
    </div>

    <!-- Thống kê thu nhập -->
    <div class="bg-gradient-to-br from-rose-500 to-pink-600 overflow-hidden shadow-lg rounded-xl text-white transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-100 text-sm font-medium uppercase tracking-wider">
                        Thu Nhập Tháng Này
                    </p>
                    <p class="mt-2 text-4xl font-extrabold">
                        {{ number_format($monthlyEarnings, 0, ',', '.') }}đ
                    </p>
                </div>
                <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-6 flex items-center">
                <a href="{{ route('tutor.earnings.index') }}" class="inline-flex items-center text-sm font-medium text-white hover:text-pink-100">
                    Xem chi tiết
                    <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Lịch dạy sắp tới -->
    <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <svg class="mr-2 h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Lịch Dạy Sắp Tới
            </h3>
            <a href="{{ route('tutor.bookings.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                Xem tất cả
            </a>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                @forelse($upcomingBookings as $booking)
                <div class="mb-4 p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 mr-3">
                                <div class="h-10 w-10 bg-indigo-100 text-indigo-500 rounded-full flex items-center justify-center">
                                    <span class="font-bold">{{ substr($booking->student->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $booking->student->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $booking->subject->name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ $booking->start_time->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</p>
                        </div>
                    </div>
                    <div class="mt-2 flex justify-between items-center">
                        <span class="inline-flex text-xs px-2.5 py-0.5 rounded-full
                            @switch($booking->status)
                                @case('pending') bg-yellow-100 text-yellow-800 @break
                                @case('confirmed') bg-green-100 text-green-800 @break
                                @case('completed') bg-blue-100 text-blue-800 @break
                                @case('cancelled') bg-red-100 text-red-800 @break
                            @endswitch
                        ">
                            @switch($booking->status)
                                @case('pending') Chờ xác nhận @break
                                @case('confirmed') Đã xác nhận @break
                                @case('completed') Hoàn thành @break
                                @case('cancelled') Đã hủy @break
                            @endswitch
                        </span>
                        <a href="{{ route('tutor.bookings.show', $booking) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">
                            Chi tiết
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Không có lịch dạy nào sắp tới</h3>
                    <p class="mt-1 text-sm text-gray-500">Khi có học sinh đặt lịch, thông tin sẽ xuất hiện ở đây.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Đánh giá gần đây -->
    <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <svg class="mr-2 h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                Đánh Giá Gần Đây
            </h3>
        </div>
        <div class="p-6">
            @forelse($recentReviews as $review)
            <div class="mb-5 last:mb-0">
                <div class="flex">
                    <div class="flex-shrink-0 mr-3">
                        <div class="h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="font-bold text-gray-600">{{ substr($review->is_anonymous ? 'A' : $review->student->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center mb-1">
                            <h4 class="text-sm font-medium text-gray-900 mr-2">{{ $review->is_anonymous ? 'Học sinh ẩn danh' : $review->student->name }}</h4>
                            <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="text-sm text-gray-700 line-clamp-3">{{ $review->comment }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có đánh giá nào</h3>
                <p class="mt-1 text-sm text-gray-500">Khi có học sinh đánh giá, thông tin sẽ xuất hiện ở đây.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Cập nhật thông tin hồ sơ -->
<div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 mb-8">
    <div class="p-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Cập nhật hồ sơ gia sư</h3>
                <p class="mt-1 text-sm text-gray-500">Hồ sơ hoàn chỉnh sẽ giúp học sinh dễ dàng lựa chọn bạn.</p>
            </div>
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('tutor.profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cập nhật ngay
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Lịch học tuần này -->
<div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
    <div class="px-6 py-5 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 flex items-center">
            <svg class="mr-2 h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Lịch Dạy Trong Tuần
        </h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-7 gap-2">
            @php
                $daysOfWeek = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
                $today = now()->dayOfWeek;
            @endphp

            @foreach($daysOfWeek as $index => $day)
                <div class="text-center">
                    <div class="mb-2 font-medium text-sm text-gray-600">{{ $day }}</div>
                    <div class="h-10 w-10 mx-auto rounded-full flex items-center justify-center {{ $today == $index ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700' }}">
                        {{ now()->startOfWeek()->addDays($index == 0 ? 6 : $index - 1)->day }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            <a href="{{ route('tutor.schedule.index') }}" class="block w-full text-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Quản lý lịch rảnh
            </a>
        </div>
    </div>
</div>
@endsection 