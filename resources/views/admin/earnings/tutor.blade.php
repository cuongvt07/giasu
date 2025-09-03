@extends('layouts.admin')

@section('page_title', 'Thu Nhập Của Gia Sư')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Thông tin gia sư -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-medium text-gray-900">Thông tin gia sư</h2>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Chi tiết thu nhập của gia sư</p>
            </div>
            <div>
                <a href="{{ route('admin.tutors.show', $tutor) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Xem hồ sơ gia sư
                </a>
                <a href="{{ route('admin.earnings.index') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Quay lại danh sách
                </a>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Tên gia sư
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $tutor->user->name }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Email
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $tutor->user->email }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Trạng thái
                    </dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                        @if($tutor->status == 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Chờ duyệt
                            </span>
                        @elseif($tutor->status == 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Hoạt động
                            </span>
                        @elseif($tutor->status == 'inactive')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Không hoạt động
                            </span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Thống kê thu nhập -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500">Tổng thu nhập đã thanh toán</h3>
            <div class="mt-1 flex items-baseline">
                <div class="flex items-baseline text-2xl font-semibold text-green-600">
                    {{ number_format($stats['total_paid'], 0, ',', '.') }}đ
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500">Thu nhập chờ thanh toán</h3>
            <div class="mt-1 flex items-baseline">
                <div class="flex items-baseline text-2xl font-semibold text-yellow-600">
                    {{ number_format($stats['total_pending'], 0, ',', '.') }}đ
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500">Tổng số buổi học</h3>
            <div class="mt-1 flex items-baseline">
                <div class="flex items-baseline text-2xl font-semibold text-blue-600">
                    {{ $stats['total_bookings'] }}
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách thu nhập -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900">Lịch sử thu nhập</h2>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Danh sách các khoản thu nhập của gia sư</p>
        </div>
        
        <div class="border-t border-gray-200">
            @if($earnings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mã
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Buổi học
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Số tiền
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Trạng thái
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ngày tạo
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($earnings as $earning)
                            <tr>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                    #{{ $earning->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $earning->booking->subject->name }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $earning->booking->start_time->format('d/m/Y H:i') }} - {{ $earning->booking->end_time->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Học sinh: {{ $earning->booking->student->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($earning->amount, 0, ',', '.') }}đ</div>
                                    <div class="text-xs text-gray-500">Phí: {{ number_format($earning->platform_fee, 0, ',', '.') }}đ</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($earning->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Chờ thanh toán
                                        </span>
                                    @elseif($earning->status == 'processing')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Đang xử lý
                                        </span>
                                    @elseif($earning->status == 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Đã thanh toán
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $earning->paid_at ? $earning->paid_at->format('d/m/Y') : '' }}
                                        </div>
                                    @elseif($earning->status == 'cancelled')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Đã hủy
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $earning->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.earnings.show', $earning) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Chi tiết</a>
                                    <a href="{{ route('admin.earnings.edit', $earning) }}" class="text-green-600 hover:text-green-900">Cập nhật</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex items-center justify-between">
                    <div>
                        <span class="text-sm text-gray-700">
                            Hiển thị {{ $earnings->firstItem() ?? 0 }} - {{ $earnings->lastItem() ?? 0 }} trên tổng số {{ $earnings->total() }} kết quả
                        </span>
                    </div>
                    <div>
                        {{ $earnings->links() }}
                    </div>
                </div>
            @else
                <div class="px-4 py-5 sm:px-6 text-gray-500 text-center">
                    Không có dữ liệu thu nhập
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 