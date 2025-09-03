@extends('layouts.admin')

@section('page_title', 'Tổng Quan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Thống kê người dùng -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-600 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Tổng Người Dùng</p>
                <p class="text-lg font-semibold text-gray-700">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>

    <!-- Thống kê gia sư -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-600 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Tổng Gia Sư</p>
                <p class="text-lg font-semibold text-gray-700">{{ $totalTutors }}</p>
            </div>
        </div>
    </div>

    <!-- Thống kê môn học -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-600 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Tổng Môn Học</p>
                <p class="text-lg font-semibold text-gray-700">{{ $totalSubjects }}</p>
            </div>
        </div>
    </div>

    <!-- Thống kê đặt lịch -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-600 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Tổng Đặt Lịch</p>
                <p class="text-lg font-semibold text-gray-700">{{ $totalBookings }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Thống kê doanh thu và thanh toán -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 mt-8">
    <!-- Tổng doanh thu -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-600 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Tổng Doanh Thu</p>
                <p class="text-lg font-semibold text-gray-700">{{ number_format($platformStats['total_earnings'], 0, ',', '.') }}đ</p>
            </div>
        </div>
    </div>

    <!-- Phí nền tảng -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-600 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Phí Nền Tảng</p>
                <p class="text-lg font-semibold text-gray-700">{{ number_format($platformStats['total_platform_fee'], 0, ',', '.') }}đ</p>
            </div>
        </div>
    </div>

    <!-- Đang chờ thanh toán -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-600 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Chờ Thanh Toán</p>
                <p class="text-lg font-semibold text-gray-700">{{ number_format($platformStats['pending_payments'], 0, ',', '.') }}đ</p>
            </div>
        </div>
    </div>

    <!-- Đã thanh toán -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-600 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Đã Thanh Toán</p>
                <p class="text-lg font-semibold text-gray-700">{{ number_format($platformStats['completed_payments'], 0, ',', '.') }}đ</p>
            </div>
        </div>
    </div>
</div>

<!-- Đặt lịch hôm nay -->
<div class="bg-white rounded-lg shadow mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Đặt Lịch Hôm Nay</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Học Sinh</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gia Sư</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Môn Học</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời Gian</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($todayBookings as $booking)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $booking->student->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $booking->tutor->user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $booking->subject->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @switch($booking->status)
                            @case('pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Chờ xác nhận
                                </span>
                                @break
                            @case('confirmed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Đã xác nhận
                                </span>
                                @break
                            @case('cancelled')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Đã hủy
                                </span>
                                @break
                            @default
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $booking->status }}
                                </span>
                        @endswitch
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Không có đặt lịch nào hôm nay
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Khoản thanh toán đang chờ xử lý -->
<div class="bg-white rounded-lg shadow mb-8">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-medium text-gray-900">Thanh Toán Gia Sư Chờ Xử Lý</h2>
        <a href="{{ route('admin.earnings.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Xem tất cả</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gia Sư</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buổi Học</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số Tiền</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày Tạo</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pendingEarnings as $earning)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $earning->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $earning->tutor->user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $earning->booking->subject->name }} <br>
                        <span class="text-xs text-gray-500">{{ $earning->booking->start_time->format('d/m/Y H:i') }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ number_format($earning->amount, 0, ',', '.') }}đ
                        <div class="text-xs text-gray-500">Phí: {{ number_format($earning->platform_fee, 0, ',', '.') }}đ</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $earning->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.earnings.edit', $earning) }}" class="text-indigo-600 hover:text-indigo-900">Xử lý</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Không có khoản thanh toán nào đang chờ xử lý
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Biểu đồ -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Biểu đồ đăng ký gia sư -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Đăng Ký Gia Sư</h2>
        </div>
        <div class="p-6">
            <canvas id="tutorRegistrationChart"></canvas>
        </div>
    </div>

    <!-- Biểu đồ đặt lịch theo môn học -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Đặt Lịch Theo Môn Học</h2>
        </div>
        <div class="p-6">
            <canvas id="bookingsBySubjectChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ đăng ký gia sư
    const tutorRegistrationCtx = document.getElementById('tutorRegistrationChart').getContext('2d');
    new Chart(tutorRegistrationCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($tutorRegistrationChart['labels']) !!},
            datasets: [{
                label: 'Số lượng đăng ký',
                data: {!! json_encode($tutorRegistrationChart['data']) !!},
                borderColor: 'rgb(79, 70, 229)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Biểu đồ đặt lịch theo môn học
    const bookingsBySubjectCtx = document.getElementById('bookingsBySubjectChart').getContext('2d');
    new Chart(bookingsBySubjectCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($bookingsBySubjectChart['labels']) !!},
            datasets: [{
                label: 'Số lượng đặt lịch',
                data: {!! json_encode($bookingsBySubjectChart['data']) !!},
                backgroundColor: 'rgba(79, 70, 229, 0.2)',
                borderColor: 'rgb(79, 70, 229)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection 