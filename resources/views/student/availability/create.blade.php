@extends('layouts.student')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-indigo-50 to-white">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Thêm Lịch Rảnh
            </h2>
            <p class="mt-1 max-w-2xl text-sm text-gray-600">
                Thiết lập khung giờ bạn có thể học để gia sư dễ dàng đặt lịch phù hợp
            </p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('student.availability.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700">Ngày trong tuần <span class="text-red-500">*</span></label>
                    <select id="day_of_week" name="day_of_week" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="">-- Chọn ngày --</option>
                        <option value="0" {{ old('day_of_week') == '0' ? 'selected' : '' }}>Chủ Nhật</option>
                        <option value="1" {{ old('day_of_week') == '1' ? 'selected' : '' }}>Thứ Hai</option>
                        <option value="2" {{ old('day_of_week') == '2' ? 'selected' : '' }}>Thứ Ba</option>
                        <option value="3" {{ old('day_of_week') == '3' ? 'selected' : '' }}>Thứ Tư</option>
                        <option value="4" {{ old('day_of_week') == '4' ? 'selected' : '' }}>Thứ Năm</option>
                        <option value="5" {{ old('day_of_week') == '5' ? 'selected' : '' }}>Thứ Sáu</option>
                        <option value="6" {{ old('day_of_week') == '6' ? 'selected' : '' }}>Thứ Bảy</option>
                    </select>
                    @error('day_of_week')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="sm:col-span-3">
                    <label for="date" class="block text-sm font-medium text-gray-700">Ngày cụ thể (không bắt buộc)</label>
                    <input type="date" name="date" id="date" value="{{ old('date') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Nếu bạn chọn ngày cụ thể, lịch này sẽ chỉ áp dụng cho ngày đó.</p>
                </div>

                <div class="sm:col-span-3">
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Giờ bắt đầu <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    @error('start_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="end_time" class="block text-sm font-medium text-gray-700">Giờ kết thúc <span class="text-red-500">*</span></label>
                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    @error('end_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                

                <div class="sm:col-span-6">
                    <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <select id="status" name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Khả dụng</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không khả dụng</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('student.availability.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                    Hủy
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Lưu Lịch Rảnh
                </button>
            </div>
        </form>
    </div>
@endsection 