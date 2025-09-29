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

            // Gom các thao tác vào 1 hàm
            acceptAndComplete(jobId, status = null, requireConfirm = false, applicationId = null, tutorId = null) {
                if (requireConfirm && !window.confirm('Bạn có chắc muốn thực hiện hành động này?')) return;

                this.accepting = true;

                fetch('{{ route('admin.jobs.acceptAndComplete') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        job_id: jobId,
                        status: status,
                        application_id: applicationId,
                        tutor_id: tutorId,
                        confirm: status === null // chỉ khi hoàn tất (Closed) mới gửi confirm = true
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.showMessage(data.message);
                        const button = document.querySelector(`#btn-${jobId}`);
                        if (button) {
                            button.outerHTML = `<span class='text-green-600 font-semibold'>Đã xác nhận</span>`;
                        }
                        window.location.reload();
                    } else {
                        this.showMessage(data.message || 'Có lỗi xảy ra.', 'error');
                    }
                })
                .catch(() => this.showMessage('Lỗi mạng hoặc server.', 'error'))
                .finally(() => this.accepting = false);
            }
        }" class="bg-white shadow rounded-lg">
        <!-- Toast Notification -->
        <div x-show="flashMessage" x-transition class="fixed bottom-6 right-6 px-4 py-3 rounded-lg shadow-lg text-white"
            :class="flashType === 'success' ? 'bg-green-600' : 'bg-red-600'" x-text="flashMessage">
        </div>

        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-900">📚 Quản lý Tin Tuyển Gia Sư</h2>
        </div>

        <!-- CHỜ XÁC NHẬN -->
        <!-- DRAFT -->
        <div class="p-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Chờ Xác Nhận (Được đăng tin lên hệ thống)</h3>
            @include('admin.manageJobs.partials.table', [
                'bookings' => $draftJobs,
                'statusLabel' => 'Draft',
                'statusColor' => 'bg-yellow-100 text-yellow-800',
            ])
        </div>

        <!-- PENDING -->
        <div class="p-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Chờ Admin Xác Nhận Yêu Cầu Khách Hàng</h3>
            @include('admin.manageJobs.partials.table', [
                'bookings' => $pendingJobs,
                'statusLabel' => 'Pending',
                'statusColor' => 'bg-orange-100 text-orange-800',
            ])
        </div>

        <!-- PUBLISHED -->
        <div class="p-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Đã Xác Nhận </h3>
            @include('admin.manageJobs.partials.table', [
                'bookings' => $publishedJobs,
                'statusLabel' => 'Published',
                'statusColor' => 'bg-green-100 text-green-800',
            ])
        </div>

        <!-- CLOSED -->
        <div class="p-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Đã Hoàn Thành (Hợp đồng có hiệu lực)</h3>
            @include('admin.manageJobs.partials.table', [
                'bookings' => $closedJobs,
                'statusLabel' => 'Closed',
                'statusColor' => 'bg-blue-100 text-blue-800',
            ])
        </div>
    </div>
@endsection