@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-6 sm:px-6">
                    <!-- Thông tin cơ bản - Enhanced Header -->
                    <div class="md:flex md:items-center md:justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start md:items-center">
                                <div class="relative">
                                    <img class="h-24 w-24 md:h-32 md:w-32 rounded-full object-cover border-4 border-white shadow" 
                                        src="{{ $tutor->avatar ? Storage::url($tutor->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($tutor->user->name) }}" 
                                        alt="{{ $tutor->user->name }}">
                                    @if($tutor->is_verified)
                                        <div class="absolute bottom-0 right-0 bg-green-100 rounded-full p-1 shadow-sm border-2 border-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 md:ml-6">
                                    <div class="flex items-center">
                                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                                            {{ $tutor->user->name }}
                                        </h2>
                                        @if($tutor->is_verified)
                                            <span class="ml-2 px-2 py-1 text-xs rounded-md bg-green-100 text-green-800 font-medium">
                                                Đã xác minh
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Rating Section -->
                                    <div class="mt-2 flex items-center">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="h-5 w-5 {{ $i <= $tutor->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-700">
                                                <span class="font-bold">{{ number_format($tutor->rating, 1) }}</span> 
                                                <span class="text-gray-500">({{ $tutor->reviews_count }} đánh giá)</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-3 flex flex-col sm:flex-row sm:flex-wrap sm:space-x-6">
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                            </svg>
                                            {{ $tutor->education_level }}
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                                                <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                                            </svg>
                                            {{ $tutor->university }}
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $tutor->major }}
                                        </div>
                                        
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="font-medium">{{ $tutor->bookings_count ?? 0 }}</span> 
                                            <span class="ml-1">buổi đã dạy</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 flex lg:mt-0 lg:ml-4">
                            <span>
                                <a href="{{ route('student.bookings.create', $tutor) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out transform hover:scale-105">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    Đặt lịch học
                                </a>
                            </span>
                        </div>
                    </div>

                    <!-- Stats strip -->
                    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="bg-indigo-50 rounded-lg p-4 flex items-center">
                            <div class="rounded-md bg-indigo-100 p-2 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-indigo-600">Kinh nghiệm</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $tutor->teaching_experience }} năm</p>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 rounded-lg p-4 flex items-center">
                            <div class="rounded-md bg-green-100 p-2 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-green-600">Học phí</p>
                                <p class="text-2xl font-semibold text-gray-900">@vnd($tutor->hourly_rate)/giờ</p>
                            </div>
                        </div>
                        
                        <div class="bg-yellow-50 rounded-lg p-4 flex items-center">
                            <div class="rounded-md bg-yellow-100 p-2 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-yellow-600">Đánh giá</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($tutor->rating, 1) }}/5.0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tab Navigation -->
    <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 overflow-x-auto tutor-detail-tabs" aria-label="Tabs">
                <a href="#gioi-thieu" class="border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Giới thiệu
                </a>
                
                <a href="#mon-hoc" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Môn học & Phương pháp
                </a>
                
                <a href="#lich-day" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Lịch dạy 
                </a>
             
                
                <a href="#danh-gia" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Đánh giá
                </a>
               
            </nav>
        </div>
    </div>
    
    <!-- Các phần thông tin chi tiết -->
    <div id="gioi-thieu" class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-tutors.bio :tutor="$tutor" />
    </div>
    
    <div id="mon-hoc" class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-tutors.subjects :tutor="$tutor" :subjects="$tutor->subjects" />
    </div>
    
    <div id="lich-day" class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-tutors.availability :tutor="$tutor" :schedules="$schedules" :availabilities="$availabilities" />
    </div>
    
    <!-- Phần đánh giá -->
    <div id="danh-gia" class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Đánh giá từ học sinh</h3>
                    
                    @auth
                        @if(auth()->user()->hasCompletedBookingWith($tutor->id) && !auth()->user()->hasReviewedTutor($tutor->id))
                            <a href="{{ route('student.tutors.review', $tutor) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Viết đánh giá
                            </a>
                        @endif
                    @endauth
                </div>
                
                @if($reviews->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có đánh giá</h3>
                        <p class="mt-1 text-sm text-gray-500">Hãy là người đầu tiên đánh giá gia sư này.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($reviews as $review)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        @if($review->is_anonymous)
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        @else
                                            <img class="h-10 w-10 rounded-full" src="{{ $review->student->avatar ? Storage::url($review->student->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($review->student->name) }}" alt="{{ $review->student->name }}">
                                        @endif
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    {{ $review->is_anonymous ? 'Học sinh ẩn danh' : $review->student->name }}
                                                </h4>
                                                <div class="mt-1 flex items-center">
                                                    <div class="flex items-center">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                    <span class="ml-2 text-xs text-gray-500">
                                                        {{ $review->created_at->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                            @if($review->booking && $review->booking->subject)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    {{ $review->booking->subject->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-2 text-sm text-gray-700">
                                            <p>{{ $review->comment }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- JavaScript để xử lý tab -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chỉ chọn các tab trong phần nội dung gia sư, không ảnh hưởng đến header
            const tabs = document.querySelectorAll('.tutor-detail-tabs a');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    // Chỉ ngăn chặn hành vi mặc định nếu đây là tab nội bộ (bắt đầu bằng #)
                    const href = this.getAttribute('href');
                    
                    // Nếu href là một id fragment (bắt đầu bằng #), xử lý tab trong trang
                    if (href && href.startsWith('#')) {
                        e.preventDefault();
                        
                        // Xóa trạng thái active cho tất cả các tab
                        tabs.forEach(t => {
                            t.classList.remove('border-indigo-500', 'text-indigo-600');
                            t.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                        });
                        
                        // Thêm trạng thái active cho tab được chọn
                        this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                        this.classList.add('border-indigo-500', 'text-indigo-600');
                        
                        // Cuộn đến phần tương ứng (trừ 100px để có khoảng cách từ top)
                        const targetElement = document.querySelector(href);
                        if (targetElement) {
                            const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - 100;
                            window.scrollTo({
                                top: targetPosition,
                                behavior: 'smooth'
                            });
                        }
                    }
                    // Nếu không phải fragment (không bắt đầu bằng #), để cho nó chuyển trang bình thường
                });
            });
        });
    </script>
@endsection 