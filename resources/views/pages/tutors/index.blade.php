@extends('layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">
                        Tìm Gia Sư Phù Hợp
                    </h1>
                    <p class="mt-3 max-w-md mx-auto text-base text-indigo-100 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                        Khám phá đội ngũ gia sư chất lượng cao, được tuyển chọn kỹ càng để đồng hành cùng hành trình học tập của bạn.
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Filters Section -->
            <div class="bg-white rounded-xl shadow-lg mb-8 overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Bộ Lọc Tìm Kiếm</h2>
                    <form action="{{ route('tutors.index') }}" method="GET" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Class Level Filter -->
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

                            <!-- Price Range -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Học Phí (VNĐ/giờ)</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <input type="number" name="min_price" placeholder="Tối thiểu" value="{{ request('min_price') }}" 
                                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <input type="number" name="max_price" placeholder="Tối đa" value="{{ request('max_price') }}" 
                                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Rating Filter -->
                            <div class="space-y-2">
                                <label for="rating" class="block text-sm font-medium text-gray-700">Đánh Giá Tối Thiểu</label>
                                <select id="rating" name="rating" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm">
                                    <option value="">Tất cả đánh giá</option>
                                    @foreach(range(5, 1) as $rating)
                                        <option value="{{ $rating }}" {{ request('rating') == $rating ? 'selected' : '' }}>
                                            {{ $rating }} sao trở lên
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Teaching Method -->
                                    <input type="hidden" hidden id="online_only" name="online_only" value="1" {{ request('online_only') ? 'checked' : '' }}
                                           class="h-4 w-4  text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                           

                            <!-- Sort -->
                            <div class="space-y-2">
                                <label for="sort_by" class="block text-sm font-medium text-gray-700">Sắp Xếp Theo</label>
                                <select id="sort_by" name="sort_by" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm">
                                    <option value="">Tất cả</option>
                                    <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Đánh giá cao nhất</option>
                                    <option value="hourly_rate" {{ request('sort_by') == 'hourly_rate' ? 'selected' : '' }}>Học phí thấp nhất</option>
                                    <option value="total_teaching_hours" {{ request('sort_by') == 'total_teaching_hours' ? 'selected' : '' }}>Kinh nghiệm nhiều nhất</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('tutors.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Đặt lại
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Tìm Kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tutors Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($tutors as $tutor)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1">
                        <div class="relative">
                            <img class="h-48 w-full object-cover" 
                                 src="{{ $tutor->avatar ? Storage::url($tutor->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($tutor->user->name) }}" 
                                 alt="{{ $tutor->user->name }}">
                            @if($tutor->is_verified)
                                <div class="absolute top-4 right-4 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Đã xác thực
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 hover:text-indigo-600">
                                        <a href="{{ route('tutors.show', $tutor) }}">
                                            {{ $tutor->user->name }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-500">{{ $tutor->education_level }}</p>
                                </div>
                                <div class="flex items-center bg-yellow-100 px-2.5 py-0.5 rounded-full">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="ml-1 text-sm font-medium text-yellow-800">{{ number_format($tutor->rating, 1) }}</span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <!-- Subjects -->
                                <div class="flex flex-wrap gap-2">
                                    @foreach($tutor->subjects->take(3) as $subject)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $subject->name }}
                                        </span>
                                    @endforeach
                                    @if($tutor->subjects->count() > 3)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            +{{ $tutor->subjects->count() - 3 }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Experience -->
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $tutor->total_teaching_hours }} giờ giảng dạy
                                </div>

                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <p class="text-md text-gray-900 mt-2">
                                    <span class="flex items-center text-sm text-gray-500">
                                        <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-14a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V4z" clip-rule="evenodd" />
                                        </svg>
                                        @vnd($tutor->hourly_rate)/giờ
                                    </span>
                                </p>
                                <a href="{{ route('tutors.show', $tutor) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Xem Chi Tiết
                                    <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            @if($tutors->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Không tìm thấy gia sư</h3>
                    <p class="mt-1 text-sm text-gray-500">Thử thay đổi bộ lọc tìm kiếm của bạn.</p>
                </div>
            @endif

            <!-- Pagination -->
            @if($tutors->hasPages())
                <div class="mt-8">
                    {{ $tutors->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection 