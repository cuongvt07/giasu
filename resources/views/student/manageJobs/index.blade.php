@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8"
     x-data="{
        openDropdowns: {},
        accepting: false,
        flashMessage: null,
        flashType: 'success',
        showMessage(msg, type = 'success') {
            this.flashMessage = msg;
            this.flashType = type;
            setTimeout(() => this.flashMessage = null, 4000);
        },
        acceptTutor(jobId, tutorId) {
            if (!confirm('Bạn có chắc muốn chọn gia sư này không?')) return;
            this.accepting = true;

            fetch('{{ route('student.jobs.acceptTutor') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ job_id: jobId, tutor_id: tutorId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.showMessage(data.message);
                    const tutor = document.querySelector(`#btn-${jobId}-${tutorId}`);
                    if (tutor) {
                        tutor.outerHTML = `<span class='text-yellow-600 text-sm font-semibold'>Chờ xác nhận</span>`;
                    }
                } else {
                    this.showMessage('Có lỗi xảy ra.', 'error');
                }
            })
            .catch(() => this.showMessage('Lỗi mạng hoặc server.', 'error'))
            .finally(() => this.accepting = false);
        }
     }">

    <!-- Toast Notification -->
    <div x-show="flashMessage" x-transition
         class="fixed bottom-6 right-6 px-4 py-3 rounded-lg shadow-lg text-white"
         :class="flashType === 'success' ? 'bg-green-600' : 'bg-red-600'"
         x-text="flashMessage">
    </div>

    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Danh Sách Tin Đăng Gia Sư</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 bg-white border rounded-lg shadow-sm">
            <thead class="bg-gray-100 text-sm text-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left">Môn học</th>
                    <th class="px-6 py-3 text-left">Mục tiêu</th>
                    <th class="px-6 py-3 text-left">Ngân sách</th>
                    <th class="px-6 py-3 text-left">Số buổi</th>
                    <th class="px-6 py-3 text-center">Ứng tuyển</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach($jobs as $job)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-gray-900">
                            {{ $job->subject_name }} - {{ $job->class_level_name }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $job->goal }}</td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ number_format($job->budget_min) }} - {{ number_format($job->budget_max) }} {{ $job->budget_unit }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $job->sessions_per_week }} buổi/tuần</td>
                        <td class="px-6 py-4 text-center">
                            <button @click="openDropdowns[{{ $job->id }}] = !openDropdowns[{{ $job->id }}]"
                                    class="text-sm bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Xem gia sư ứng tuyển ({{ count($applications[$job->id] ?? []) }})
                            </button>
                        </td>
                    </tr>

                    <!-- Tutor Dropdown Row -->
                    <tr x-show="openDropdowns[{{ $job->id }}]" x-transition>
                        <td colspan="5" class="p-0 bg-gray-50">
                            <div class="p-4">
                                @if(count($applications[$job->id] ?? []) > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-sm divide-y divide-gray-200">
                                            <thead class="bg-gray-100 text-gray-600 text-left">
                                                <tr>
                                                    <th class="px-4 py-2">Gia sư</th>
                                                    <th class="px-4 py-2">Học vấn</th>
                                                    <th class="px-4 py-2">Kinh nghiệm</th>
                                                    <th class="px-4 py-2">Đánh giá</th>
                                                    <th class="px-4 py-2">Bằng</th>
                                                    <th class="px-4 py-2">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-100">
                                                @foreach($applications[$job->id] as $tutor)
                                                    <tr>
                                                        <!-- Avatar + Tên -->
                                                        <td class="px-4 py-3 flex items-center space-x-3">
                                                            <span class="font-medium text-gray-800">{{ $tutor->tutor_name }}</span>
                                                        </td>

                                                        <!-- Học vấn -->
                                                        <td class="px-4 py-3 text-gray-700">
                                                            {{ $tutor->university }}<br>
                                                            <span class="text-gray-500">{{ $tutor->major }}</span>
                                                        </td>

                                                        <!-- Kinh nghiệm -->
                                                        <td class="px-4 py-3 text-gray-700">
                                                            {{ $tutor->teaching_experience }} năm giảng dạy
                                                        </td>

                                                        <td class="px-4 py-3 text-gray-700">
                                                            {{ $tutor->education_level }}
                                                        </td>

                                                        <td class="px-4 py-3 text-gray-700">
                                                            {{ $tutor->rating ? number_format($tutor->rating, 1) : 'N/A' }} ⭐
                                                        </td>

                                                        <!-- Thao tác -->
                                                        <td class="px-4 py-3 space-x-3">
                                                            <a href="{{ route('tutors.show', $tutor->tutor_id) }}"
                                                               class="text-indigo-600 hover:underline">
                                                                Xem chi tiết
                                                            </a>

                                                            @if($tutor->app_status === 'requested')
                                                                <span class="text-yellow-600 font-semibold">Chờ xác nhận</span>
                                                            @else
                                                                <button
                                                                    id="btn-{{ $job->id }}-{{ $tutor->tutor_id }}"
                                                                    @click="acceptTutor({{ $job->id }}, {{ $tutor->tutor_id }})"
                                                                    class="bg-green-500 text-white text-sm px-3 py-1 rounded hover:bg-green-600">
                                                                    Chấp nhận
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-gray-500 text-sm">Chưa có gia sư nào ứng tuyển.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
