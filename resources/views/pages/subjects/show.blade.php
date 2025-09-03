@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Subject Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $subject->name }}</h1>
                        <span class="px-3 py-1 text-sm font-medium bg-indigo-100 text-indigo-800 rounded-full">
                            {{ $subject->category }}
                        </span>
                    </div>

                    @if($subject->description)
                        <p class="text-gray-600 mb-4">{{ $subject->description }}</p>
                    @endif

                    <div class="flex items-center text-sm text-gray-500">
                        <span class="flex items-center">
                            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            {{ $tutors->total() }} Gia sư
                        </span>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Bộ lọc</h3>
                    
                    <form action="{{ route('subjects.show', $subject) }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Giá thấp nhất -->
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700">Giá thấp nhất</label>
                            <input type="number" name="min_price" id="min_price" value="{{ $filters['min_price'] ?? '' }}" min="0" step="10000"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="VNĐ">
                        </div>
                        
                        <!-- Giá cao nhất -->
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700">Giá cao nhất</label>
                            <input type="number" name="max_price" id="max_price" value="{{ $filters['max_price'] ?? '' }}" min="0" step="10000"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="VNĐ">
                        </div>
                        
                        <!-- Đánh giá tối thiểu -->
                        <div>
                            <label for="min_rating" class="block text-sm font-medium text-gray-700">Đánh giá tối thiểu</label>
                            <select name="min_rating" id="min_rating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Tất cả</option>
                                <option value="5" {{ isset($filters['min_rating']) && $filters['min_rating'] == 5 ? 'selected' : '' }}>5 sao</option>
                                <option value="4" {{ isset($filters['min_rating']) && $filters['min_rating'] == 4 ? 'selected' : '' }}>4+ sao</option>
                                <option value="3" {{ isset($filters['min_rating']) && $filters['min_rating'] == 3 ? 'selected' : '' }}>3+ sao</option>
                                <option value="2" {{ isset($filters['min_rating']) && $filters['min_rating'] == 2 ? 'selected' : '' }}>2+ sao</option>
                                <option value="1" {{ isset($filters['min_rating']) && $filters['min_rating'] == 1 ? 'selected' : '' }}>1+ sao</option>
                            </select>
                        </div>
                        
                        <!-- Sắp xếp -->
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700">Sắp xếp theo</label>
                            <select name="sort" id="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="" {{ $filters['sort'] == '' ? 'selected' : '' }}>Tất cả</option>
                                <option value="rating_desc" {{ $filters['sort'] == 'rating_desc' ? 'selected' : '' }}>Đánh giá cao nhất</option>
                                <option value="rating_asc" {{ $filters['sort'] == 'rating_asc' ? 'selected' : '' }}>Đánh giá thấp nhất</option>
                                <option value="price_asc" {{ $filters['sort'] == 'price_asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                                <option value="price_desc" {{ $filters['sort'] == 'price_desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                            </select>
                        </div>
                        
                        <!-- Nút lọc -->
                        <div class="md:col-span-2 lg:col-span-4 flex justify-end mt-4">
                            <a href="{{ route('subjects.show', $subject) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 mr-3">
                                Đặt lại
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Áp dụng bộ lọc
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tutors List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Gia Sư Dạy {{ $subject->name }}</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($tutors as $tutor)
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center mb-4">
                                    <img class="h-12 w-12 rounded-full object-cover" 
                                         src="{{ $tutor->avatar ? Storage::url($tutor->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($tutor->user->name) }}" 
                                         alt="{{ $tutor->user->name }}">
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            {{ $tutor->user->name }}
                                        </h3>
                                        <p class="text-sm text-gray-500">{{ $tutor->education_level }}</p>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="flex items-center mb-2">
                                        <span class="text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $tutor->rating)
                                                    ★
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                        </span>
                                        <span class="ml-1 text-sm text-gray-500">
                                            {{ number_format($tutor->rating, 1) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ Str::limit($tutor->bio, 100) }}</p>
                                </div>

                                <div class="mt-2 flex items-center text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-14a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-md font-medium text-gray-900">
                                        @vnd($tutor->pivot->price_per_hour)/giờ
                                    </span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <a href="{{ route('tutors.show', $tutor) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        Xem Chi Tiết
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có gia sư nào dạy môn này</h3>
                                <p class="mt-1 text-sm text-gray-500">Vui lòng quay lại sau.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $tutors->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 