@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tìm kiếm và Lọc -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form action="{{ route('subjects.index') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Tìm kiếm -->
                            <div class="col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700">Tìm kiếm môn học</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="search" id="search" value="{{ $search }}"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Nhập tên môn học, danh mục hoặc mô tả...">
                                </div>
                            </div>
                            
                            <!-- Lọc theo danh mục -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Danh mục</label>
                                <select id="category" name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Tất cả danh mục</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ $selectedCategory == $category ? 'selected' : '' }}>{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Nút tìm kiếm -->
                        <div class="flex justify-end">
                            <a href="{{ route('subjects.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 mr-3">
                                Đặt lại
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">Danh Sách Môn Học</h2>

                    @forelse($subjects as $category => $categorySubjects)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $category }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($categorySubjects as $subject)
                                    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-200">
                                        <a href="{{ route('subjects.show', $subject) }}" class="block">
                                            <h4 class="text-xl font-medium text-gray-900 mb-2">{{ $subject->name }}</h4>
                                            @if($subject->description)
                                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($subject->description, 100) }}</p>
                                            @endif
                                            <div class="flex items-center text-sm text-gray-500">
                                                <span class="flex items-center">
                                                    <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                    </svg>
                                                    {{ $subject->tutors->count() }} Gia sư
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Không có môn học nào</h3>
                            <p class="mt-1 text-sm text-gray-500">Vui lòng thử lại sau.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection 