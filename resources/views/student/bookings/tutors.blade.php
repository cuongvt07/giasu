@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Gia sư của tôi</h1>
            <p class="mt-2 text-sm text-gray-600">Danh sách gia sư đang và đã từng dạy bạn.</p>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Gia sư hiện tại</h2>
                
                @if($currentTutors->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-gray-500">Bạn chưa có gia sư hiện tại nào.</p>
                        <a href="{{ route('tutors.index') }}" class="mt-2 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Tìm gia sư ngay
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($currentTutors as $tutor)
                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition duration-150 ease-in-out overflow-hidden">
                                <div class="p-5">
                                    <div class="flex items-center mb-4">
                                        <img src="{{ $tutor->avatar ? Storage::url($tutor->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($tutor->user->name) }}" alt="{{ $tutor->user->name }}" class="h-12 w-12 rounded-full object-cover mr-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $tutor->user->name }}</h3>
                                            <p class="text-sm text-gray-600">{{ $tutor->subjects->pluck('name')->join(', ') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center mb-1">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span class="ml-1 text-sm font-medium text-gray-600">{{ number_format($tutor->reviews->avg('rating') ?? 5, 1) }}/5</span>
                                        </div>
                                        
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium">Buổi học tiếp theo:</span> 
                                            @if($tutor->latest_booking)
                                                {{ \Carbon\Carbon::parse($tutor->latest_booking->start_time)->format('H:i d/m/Y') }}
                                            @else
                                                Chưa có lịch
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('tutors.show', $tutor) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded text-sm">
                                            Thông tin
                                        </a>
                                        <a href="{{ route('student.bookings.create', $tutor) }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded text-sm">
                                            Đặt buổi học
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Gia sư đã thuê trước đây</h2>
                
                @if($pastTutors->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-gray-500">Bạn chưa từng học với gia sư nào trước đây.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($pastTutors as $tutor)
                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition duration-150 ease-in-out overflow-hidden">
                                <div class="p-5">
                                    <div class="flex items-center mb-4">
                                        <img src="{{ $tutor->avatar ? Storage::url($tutor->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($tutor->user->name) }}" alt="{{ $tutor->user->name }}" class="h-12 w-12 rounded-full object-cover mr-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $tutor->user->name }}</h3>
                                            <p class="text-sm text-gray-600">{{ $tutor->subjects->pluck('name')->join(', ') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center mb-1">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span class="ml-1 text-sm font-medium text-gray-600">{{ number_format($tutor->reviews->avg('rating') ?? 5, 1) }}/5</span>
                                        </div>
                                        
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium">Trạng thái:</span> 
                                            @if($tutor->reviews->where('student_id', auth()->id())->count() > 0)
                                                Đã đánh giá
                                            @else
                                                Chưa đánh giá
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('tutors.show', $tutor) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded text-sm">
                                            Thông tin
                                        </a>
                                        @if($tutor->reviews->where('student_id', auth()->id())->count() == 0)
                                            <a href="{{ route('student.tutors.review', $tutor) }}" class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white text-center py-2 px-4 rounded text-sm">
                                                Đánh giá
                                            </a>
                                        @else
                                            <a href="{{ route('student.bookings.create', $tutor) }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded text-sm">
                                                Đặt lại
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 