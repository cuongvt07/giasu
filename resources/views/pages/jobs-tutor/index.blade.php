@extends('layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen" x-data="{
    selectedJob: null,
    applyJob: null,
    isApplying: false,
    flashMessage: null,
    flashType: 'success',

    showMessage(msg, type = 'success') {
        this.flashMessage = msg;
        this.flashType = type;
        setTimeout(() => this.flashMessage = null, 4000);
    },

    applyToJob(job) {
        this.applyJob = job;
        this.isApplying = true;

        fetch('/tutors/apply', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ job_id: job.id }),
        })
        .then(res => {
            if (!res.ok) throw new Error('Ứng tuyển thất bại');
            return res.json();
        })
        .then(data => {
            this.showMessage('Ứng tuyển thành công!', 'success');
            this.applyJob = null;
            this.selectedJob = null;
            window.location.reload();
        })
        .catch(err => {
            this.showMessage('Đã xảy ra lỗi khi ứng tuyển.', 'error');
            console.error(err);
        })
        .finally(() => this.isApplying = false);
    }
}">

<div 
    x-show="flashMessage"
    x-transition
    class="fixed bottom-6 right-6 px-4 py-3 rounded-lg shadow-lg text-white"
    :class="flashType === 'success' ? 'bg-green-600' : 'bg-red-600'"
    x-text="flashMessage"
>
</div>

        <!-- Hero Section -->
        <div class="bg-white border-b border-gray-200 py-10">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 font-serif">Danh Sách Tin Tuyển Gia Sư</h1>
                <p class="mt-3 text-gray-600 max-w-2xl mx-auto">
                    Tổng hợp các nhu cầu tìm gia sư từ phụ huynh & học sinh – chọn job phù hợp để ứng tuyển.
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border mb-10">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-filter text-indigo-500"></i> Bộ Lọc Tìm Kiếm
                    </h2>
                    <form action="{{ route('tutors.jobs.post') }}" method="GET" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Subject -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Môn học</label>
                                <select name="subject"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Tất cả</option>
                                    @foreach($subjects as $s)
                                        <option value="{{ $s }}" {{ request('subject') == $s->name ? 'selected' : '' }}>
                                            {{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Class Level -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cấp học</label>
                                <select name="class_level_id"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Tất cả cấp học</option>
                                    @foreach($classLevels as $level)
                                        <option value="{{ $level->id }}" {{ request('class_level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Mode -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Hình thức</label>
                                <select name="mode"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Tất cả</option>
                                    <option value="online" {{ request('mode') == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="offline" {{ request('mode') == 'offline' ? 'selected' : '' }}>Offline
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Budget -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ngân sách (VNĐ)</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="number" name="budget_min" placeholder="Tối thiểu"
                                        value="{{ request('budget_min') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <input type="number" name="budget_max" placeholder="Tối đa"
                                        value="{{ request('budget_max') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>

                            <!-- Sort -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sắp xếp theo</label>
                                <select name="sort_by"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Mặc định</option>
                                    <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Mới nhất
                                    </option>
                                    <option value="budget" {{ request('sort_by') == 'budget' ? 'selected' : '' }}>Ngân sách
                                        cao nhất</option>
                                    <option value="deadline" {{ request('sort_by') == 'deadline' ? 'selected' : '' }}>Hạn chót
                                        gần nhất</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('tutors.jobs.post') }}"
                                class="px-6 py-2 rounded-lg border bg-white text-gray-700 shadow hover:bg-gray-50">
                                Đặt lại
                            </a>
                            <button type="submit"
                                class="px-6 py-2 rounded-lg bg-indigo-600 text-white shadow hover:bg-indigo-700">
                                Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Jobs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($dataJobs as $post)
                    <div
                        class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition p-6 flex flex-col">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-gray-900 font-serif">
                                {{ $post->subject_name }} - {{ $post->class_level_name }}
                            </h3>
                            <span
                                class="px-3 py-1 text-xs rounded-full {{ $post->mode == 'online' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600' }}">
                                {{ ucfirst($post->mode) }}
                            </span>
                        </div>

                        <p class="text-gray-600 text-sm mb-4 line-clamp-2 italic">"{{ $post->goal }}"</p>

                        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-700 flex-grow">
                            <div>
                                <dt class="font-medium">Số buổi/tuần:</dt>
                                <dd>{{ $post->sessions_per_week }} buổi</dd>
                            </div>
                            <div>
                                <dt class="font-medium">Thời lượng:</dt>
                                <dd>{{ $post->session_length_min }} phút</dd>
                            </div>
                            <div>
                                <dt class="font-medium">Ngân sách:</dt>
                                <dd>{{ number_format($post->budget_min) }} - {{ number_format($post->budget_max) }} /
                                    {{ $post->budget_unit }}</dd>
                            </div>
                            @if($post->deadline_at)
                                <div>
                                    <dt class="font-medium">Hạn chót:</dt>
                                    <dd>{{ $post->deadline_at }}</dd>
                                </div>
                            @endif
                        </dl>

                        <div class="mt-6 flex justify-between items-center border-t pt-4 gap-3">
                            <button 
                                @click="selectedJob = @js($post)"
                                class="text-sm font-medium text-indigo-600 hover:underline">
                                Xem chi tiết
                            </button>

                            @php
                                $user = auth()->user();
                                $tutorId = $user?->tutor?->user_id; // Lấy ID gia sư nếu có
                                $appliedIds = $post->applied_tutor_ids ?? [];
                            @endphp

                            @if($tutorId)
                                @if(in_array($tutorId, $appliedIds))
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-200 text-gray-600 text-sm shadow">
                                        Đã ứng tuyển
                                    </span>
                                @else
                                    <button 
                                        @click="applyToJob(@js($post))"
                                        x-bind:disabled="isApplying && applyJob?.id === {{ $post->id }}"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 text-sm shadow">
                                        
                                        <span x-show="!(isApplying && applyJob?.id === {{ $post->id }})">Ứng tuyển</span>
                                        
                                        <span x-show="isApplying && applyJob?.id === {{ $post->id }}" class="inline-flex items-center gap-1">
                                            <svg class="h-3.5 w-3.5 animate-spin" viewBox="0 0 24 24" fill="none">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>
                                            Đang gửi...
                                        </span>
                                    </button>
                                @endif
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Không có tin tuyển gia sư nào</h3>
                        <p class="mt-1 text-sm text-gray-500">Hãy quay lại sau hoặc thử thay đổi bộ lọc tìm kiếm.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($dataJobs->hasPages())
                <div class="mt-8">
                    {{ $dataJobs->withQueryString()->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Job Detail -->
        <div x-show="selectedJob" x-trap="selectedJob" @keydown.escape.window="selectedJob = null" x-transition.opacity
            style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/30 backdrop-blur-md" role="dialog"
            aria-modal="true">

            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click.self="selectedJob = null"></div>

            <!-- Modal Panel -->
            <div x-transition
                class="relative z-10 w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-xl ring-1 ring-black/10">

                <!-- Header -->
                <div class="sticky top-0 flex items-center justify-between border-b bg-white/80 px-6 py-4 backdrop-blur">
                    <h2 class="text-lg font-semibold text-gray-900">Chi tiết tin tuyển</h2>
                    <button @click="selectedJob = null"
                        class="h-8 w-8 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="px-6 py-5 space-y-4">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="font-medium text-gray-600">Môn:</dt>
                            <dd x-text="selectedJob.subject_name"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Cấp học:</dt>
                            <dd x-text="selectedJob.class_level_name || '—'"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Mục tiêu:</dt>
                            <dd x-text="selectedJob.goal || '—'"></dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="font-medium text-gray-600">Mô tả:</dt>
                            <dd x-text="selectedJob.description || '—'"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Ngân sách:</dt>
                            <dd
                                x-text="selectedJob.budget_min + ' - ' + selectedJob.budget_max + ' / ' + selectedJob.budget_unit">
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Số buổi/tuần:</dt>
                            <dd x-text="selectedJob.sessions_per_week + ' buổi'"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Thời lượng/buổi:</dt>
                            <dd x-text="selectedJob.session_length_min + ' phút'"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Ghi chú lịch học:</dt>
                            <dd x-text="selectedJob.schedule_notes || '—'"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Hình thức:</dt>
                            <dd x-text="selectedJob.mode == 'offline' ? 'Offline - Tại nhà' : 'Online'"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Địa chỉ:</dt>
                            <dd x-text="selectedJob.address_line || '—'"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Số học sinh:</dt>
                            <dd x-text="selectedJob.student_count"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Tuổi học sinh:</dt>
                            <dd x-text="selectedJob.student_age || '—'"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Yêu cầu khác:</dt>
                            <dd x-text="selectedJob.qualifications || '—'"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Kinh nghiệm tối thiểu:</dt>
                            <dd x-text="selectedJob.min_experience_yr + ' năm'"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Ghi chú đặc biệt:</dt>
                            <dd x-text="selectedJob.special_notes || '—'"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Điện thoại liên hệ:</dt>
                            <dd x-text="selectedJob.contact_phone"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Email liên hệ:</dt>
                            <dd x-text="selectedJob.contact_email || '—'"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Hạn chót:</dt>
                            <dd x-text="selectedJob.deadline_at || '—'"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Trạng thái:</dt>
                            <dd x-text="selectedJob.status == 'published' ? 'Đã đăng' : 'Nháp'"></dd>
                        </div>
                    </dl>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 border-t bg-white/80 px-6 py-4 backdrop-blur">
                    <button @click="selectedJob = null"
                        class="rounded-lg border px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Đóng
                    </button>

                    @auth
                        <template x-if="selectedJob">
                            <button @click="applyToJob(selectedJob)" x-ref="applyBtn" x-bind:disabled="isApplying"
                                class="inline-flex items-center px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 transition-all font-medium shadow-lg">
                                <span x-show="!isApplying" class="inline">Ứng tuyển</span>
                                <span x-show="isApplying" class="inline-flex items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                        </path>
                                    </svg>
                                    Đang gửi...
                                </span>
                            </button>
                        </template>
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection