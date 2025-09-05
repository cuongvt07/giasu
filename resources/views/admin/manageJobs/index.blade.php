@extends('layouts.admin')

@section('content')
<div x-data="{
        flashMessage: null,
        flashType: 'success',
        openDropdowns: {},
        accepting: false,
        showMessage(msg, type = 'success') {
            this.flashMessage = msg;
            this.flashType = type;
            setTimeout(() => this.flashMessage = null, 4000);
        },
        acceptAndComplete(jobId, applicationId, tutorId) {
            if (!confirm('Bạn có chắc muốn chọn gia sư này và hoàn tất phân công không?')) return;
            this.accepting = true;

            fetch('{{ route('admin.jobs.acceptAndComplete') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ job_id: jobId, application_id: applicationId, tutor_id: tutorId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.showMessage(data.message);
                    const button = document.querySelector(`#btn-${jobId}-${applicationId}`);
                    if (button) {
                        button.outerHTML = `<span class='text-green-600 font-semibold'>Đã xác nhận</span>`;
                    }
                } else {
                    this.showMessage(data.message || 'Có lỗi xảy ra.', 'error');
                }
            })
            .catch(() => this.showMessage('Lỗi mạng hoặc server.', 'error'))
            .finally(() => this.accepting = false);
        }
    }" class="bg-white shadow rounded-lg">
    <!-- Toast Notification -->
    <div x-show="flashMessage" x-transition
         class="fixed bottom-6 right-6 px-4 py-3 rounded-lg shadow-lg text-white"
         :class="flashType === 'success' ? 'bg-green-600' : 'bg-red-600'"
         x-text="flashMessage">
    </div>

    <div class="p-6">
        <h2 class="text-xl font-semibold text-gray-900">📚 Quản lý Tin Tuyển Gia Sư</h2>
    </div>

    <!-- CHỜ XÁC NHẬN -->
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

    <!-- ĐÃ XÁC NHẬN -->
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

    <!-- ĐÃ HOÀN THÀNH -->
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