@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">
                Danh Sách Tin Tuyển Gia Sư
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base text-indigo-100 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Xem các yêu cầu từ phụ huynh và học sinh, chọn job phù hợp để ứng tuyển.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg mb-8 overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Bộ Lọc Tìm Kiếm</h2>
                <form action="{{ route('tutors.jobs.post') }}" method="GET" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Subject -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Môn học</label>
                            <select name="subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Tất cả</option>
                                @foreach($subjects as $s)
                                    <option value="{{ $s }}" {{ request('subject') == $s->name ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="class_level_id" class="block text-sm font-medium text-gray-700">Cấp Học</label>
                            <select id="class_level_id" name="class_level_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm">
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
                            <select name="mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Tất cả</option>
                                <option value="online" {{ request('mode') == 'online' ? 'selected' : '' }}>Online</option>
                                <option value="offline" {{ request('mode') == 'offline' ? 'selected' : '' }}>Offline</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Budget -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ngân sách (VNĐ)</label>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="number" name="budget_min" placeholder="Tối thiểu" value="{{ request('budget_min') }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <input type="number" name="budget_max" placeholder="Tối đa" value="{{ request('budget_max') }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sắp xếp theo</label>
                            <select name="sort_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Mặc định</option>
                                <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="budget" {{ request('sort_by') == 'budget' ? 'selected' : '' }}>Ngân sách cao nhất</option>
                                <option value="deadline" {{ request('sort_by') == 'deadline' ? 'selected' : '' }}>Hạn chót gần nhất</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('tutors.jobs.post') }}" class="px-6 py-3 rounded-md border bg-white text-gray-700 shadow hover:bg-gray-50">
                            Đặt lại
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-md bg-indigo-600 text-white shadow hover:bg-indigo-700">
                            Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Jobs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($dataJobs as $post)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            {{ $post->subject_name }} - {{ $post->class_level_name }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $post->description }}</p>

                        <div class="space-y-2 text-sm text-gray-500">
                            <div><strong>Mục tiêu:</strong> {{ $post->goal }}</div>
                            <div><strong>Số buổi/tuần:</strong> {{ $post->sessions_per_week }} buổi</div>
                            <div><strong>Thời lượng/buổi:</strong> {{ $post->session_length_min }} phút</div>
                            <div><strong>Ngân sách:</strong> {{ number_format($post->budget_min) }} - {{ number_format($post->budget_max) }} / {{ $post->budget_unit }}</div>
                            <div><strong>Hình thức:</strong> {{ ucfirst($post->mode) }}</div>
                            @if($post->deadline_at)
                                <div><strong>Hạn chót:</strong> {{ $post->deadline_at }}</div>
                            @endif
                        </div>

                        <div class="mt-6 flex justify-between items-center">
                            <a href=""
                               class="px-4 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Xem chi tiết
                            </a>
                            @auth
                                @if(auth()->user()->tutor)
                                    <form action="" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 text-sm font-medium rounded-md border border-indigo-600 text-indigo-600 hover:bg-indigo-50">
                                            Ứng tuyển
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
</div>
@endsection
