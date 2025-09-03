@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Đánh Giá Gia Sư</h1>
                
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <img src="{{ $tutor->avatar ? Storage::url($tutor->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($tutor->user->name) }}" alt="{{ $tutor->user->name }}" class="h-16 w-16 rounded-full object-cover mr-4">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ $tutor->user->name }}</h2>
                            <p class="text-sm text-gray-600">{{ $tutor->subjects->pluck('name')->join(', ') }}</p>
                        </div>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('student.tutors.review.store', $tutor) }}">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="booking_id" class="block text-sm font-medium text-gray-700 mb-2">Buổi học</label>
                        <select id="booking_id" name="booking_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @foreach($completedBookings as $booking)
                                <option value="{{ $booking->id }}">
                                    {{ $booking->subject->name }} - 
                                    {{ $booking->start_time->format('d/m/Y H:i') }} đến 
                                    {{ $booking->end_time->format('H:i') }}
                                </option>
                            @endforeach
                        </select>
                        @error('booking_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Đánh giá</label>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="flex items-center mr-4 cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" {{ old('rating') == $i ? 'checked' : '' }}>
                                    <div class="w-10 h-10 flex items-center justify-center rounded-full border-2 peer-checked:border-yellow-400 peer-checked:bg-yellow-50">
                                        <svg class="h-6 w-6 {{ old('rating') >= $i ? 'text-yellow-400' : 'text-gray-300' }} peer-checked:text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                    <span class="ml-2">{{ $i }}</span>
                                </label>
                            @endfor
                        </div>
                        @error('rating')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Nhận xét của bạn</label>
                        <textarea id="comment" name="comment" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Hãy chia sẻ trải nghiệm của bạn với gia sư này...">{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input id="is_anonymous" name="is_anonymous" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_anonymous') ? 'checked' : '' }}>
                            <label for="is_anonymous" class="ml-2 block text-sm text-gray-700">
                                Đánh giá ẩn danh
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Tên của bạn sẽ không hiển thị công khai nếu bạn chọn đánh giá ẩn danh</p>
                    </div>
                    
                    <div class="flex justify-end">
                        <a href="{{ route('student.bookings.tutors') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                            Hủy
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Gửi đánh giá
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 