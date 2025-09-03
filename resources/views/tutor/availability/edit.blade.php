@extends('layouts.tutor')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Chỉnh Sửa Lịch Rảnh
            </h2>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Cập nhật khung giờ dạy học
            </p>
        </div>

        <form method="POST" action="{{ route('tutor.availability.update', $availability) }}">
            @csrf
            @method('PUT')
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Ngày trong tuần <span class="text-red-500">*</span>
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <select name="day_of_week" id="day_of_week" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">-- Chọn ngày --</option>
                                @foreach($daysOfWeek as $value => $day)
                                    <option value="{{ $value }}" {{ (old('day_of_week', $availability->day_of_week) == $value) ? 'selected' : '' }}>
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                            @error('day_of_week')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </dd>
                    </div>

                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Ngày cụ thể (tùy chọn)
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <input type="date" name="date" id="date" value="{{ old('date', $availability->date ? $availability->date->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Để trống nếu lịch này lặp lại hàng tuần. Nếu chọn ngày cụ thể, lịch này sẽ chỉ hiển thị cho ngày đó.</p>
                            @error('date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </dd>
                    </div>
                
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Giờ bắt đầu <span class="text-red-500">*</span>
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <input type="time" name="start_time" id="start_time" required value="{{ old('start_time', $availability->start_time->format('H:i')) }}"
                                class="mt-1 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                            @error('start_time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </dd>
                    </div>

                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Giờ kết thúc <span class="text-red-500">*</span>
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <input type="time" name="end_time" id="end_time" required value="{{ old('end_time', $availability->end_time->format('H:i')) }}"
                                class="mt-1 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                            @error('end_time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </dd>
                    </div>

                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Lặp lại
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex items-center">
                                <input id="is_recurring" name="is_recurring" type="checkbox" value="1" {{ old('is_recurring', $availability->is_recurring) ? 'checked' : '' }}
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                <label for="is_recurring" class="ml-2 block text-sm text-gray-700">
                                    Lặp lại hàng tuần
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Lịch này sẽ được lặp lại hàng tuần. Nếu bạn đã chọn ngày cụ thể, tùy chọn này sẽ không có tác dụng.</p>
                            @error('is_recurring')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </dd>
                    </div>

                    @error('general')
                        <div class="bg-red-50 px-4 py-5 sm:px-6">
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="bg-white px-4 py-5 sm:px-6 flex justify-end space-x-3">
                        <a href="{{ route('tutor.availability.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Hủy
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cập Nhật
                        </button>
                    </div>
                </dl>
            </div>
        </form>
    </div>
@endsection 