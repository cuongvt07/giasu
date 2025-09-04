@extends('layouts.admin')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="p-6">
        <h2 class="text-xl font-semibold text-gray-900">📚 Quản lý Tin Tuyển Gia Sư</h2>
    </div>

    {{-- CHỜ XÁC NHẬN --}}
    <div class="p-6 border-t border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Chờ Xác Nhận</h3>
        <p class="text-sm text-gray-600 mb-4">Danh sách tin tuyển chờ admin phê duyệt.</p>
        @include('admin.manageJobs.partials.table', [
            'bookings' => $pendingJobs,
            'statusLabel' => 'Chờ xác nhận',
            'statusColor' => 'bg-yellow-100 text-yellow-800',
            'showStatus' => true,
            'canDelete' => false,
        ])
    </div>

    {{-- ĐÃ XÁC NHẬN --}}
    <div class="p-6 border-t border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Đã Xác Nhận - Chờ Hoàn Thành</h3>
        <p class="text-sm text-gray-600 mb-4">Danh sách tin tuyển đã được phê duyệt, chờ hoàn thành.</p>
        @include('admin.manageJobs.partials.table', [
            'bookings' => $publishedJobs,
            'statusLabel' => 'Đã xác nhận',
            'statusColor' => 'bg-green-100 text-green-800',
            'showStatus' => true,
            'canDelete' => false,
        ])
    </div>

    {{-- ĐÃ HOÀN THÀNH --}}
    <div class="p-6 border-t border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Đã Hoàn Thành</h3>
        <p class="text-sm text-gray-600 mb-4">Danh sách tin tuyển đã hoàn thành.</p>
        @include('admin.manageJobs.partials.table', [
            'bookings' => $closedJobs,
            'statusLabel' => 'Hoàn thành',
            'statusColor' => 'bg-blue-100 text-blue-800',
            'showStatus' => true,
            'canDelete' => true,
        ])
    </div>
</div>
@endsection