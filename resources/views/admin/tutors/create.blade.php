@extends('layouts.admin')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="p-6">
        <h2 class="text-xl font-semibold text-gray-900">Thêm Gia Sư Mới</h2>
    </div>

    <form action="{{ route('admin.tutors.store') }}" method="POST" class="p-6 border-t border-gray-200">
        @csrf

        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="user_id" class="block text-sm font-medium text-gray-700">Chọn Người Dùng</label>
                <div class="mt-1">
                    <select id="user_id" name="user_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <option value="">-- Chọn người dùng --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('user_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="education_level" class="block text-sm font-medium text-gray-700">Trình Độ Học Vấn</label>
                <div class="mt-1">
                    <select id="education_level" name="education_level" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <option value="">-- Chọn trình độ --</option>
                        <option value="Sinh viên" {{ old('education_level') == 'Sinh viên' ? 'selected' : '' }}>Sinh viên</option>
                        <option value="Cử nhân" {{ old('education_level') == 'Cử nhân' ? 'selected' : '' }}>Cử nhân</option>
                        <option value="Thạc sĩ" {{ old('education_level') == 'Thạc sĩ' ? 'selected' : '' }}>Thạc sĩ</option>
                        <option value="Tiến sĩ" {{ old('education_level') == 'Tiến sĩ' ? 'selected' : '' }}>Tiến sĩ</option>
                    </select>
                </div>
                @error('education_level')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="university" class="block text-sm font-medium text-gray-700">Trường Đại Học</label>
                <div class="mt-1">
                    <input type="text" name="university" id="university" value="{{ old('university') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
                @error('university')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="major" class="block text-sm font-medium text-gray-700">Chuyên Ngành</label>
                <div class="mt-1">
                    <input type="text" name="major" id="major" value="{{ old('major') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
                @error('major')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-6">
                <label for="subjects" class="block text-sm font-medium text-gray-700">Môn Học Giảng Dạy</label>
                <div class="mt-1">
                    <select id="subjects" name="subjects[]" multiple class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ in_array($subject->id, old('subjects', [])) ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('subjects')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-6">
                <label for="class_levels" class="block text-sm font-medium text-gray-700">Cấp Học Giảng Dạy</label>
                <div class="mt-1">
                    <select id="class_levels" name="class_levels[]" multiple class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @foreach($classLevels as $level)
                            <option value="{{ $level->id }}" {{ in_array($level->id, old('class_levels', [])) ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('class_levels')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="teaching_experience" class="block text-sm font-medium text-gray-700">Kinh Nghiệm Giảng Dạy (năm)</label>
                <div class="mt-1">
                    <input type="number" name="teaching_experience" id="teaching_experience" value="{{ old('teaching_experience') }}" min="0" step="0.5" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
                @error('teaching_experience')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="hourly_rate" class="block text-sm font-medium text-gray-700">Học Phí/Giờ (VNĐ)</label>
                <div class="mt-1">
                    <input type="number" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate') }}" min="0" step="10000" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
                @error('hourly_rate')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-6">
                <label for="bio" class="block text-sm font-medium text-gray-700">Giới Thiệu Bản Thân</label>
                <div class="mt-1">
                    <textarea id="bio" name="bio" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('bio') }}</textarea>
                </div>
                @error('bio')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-6">
                <label for="status" class="block text-sm font-medium text-gray-700">Trạng Thái</label>
                <div class="mt-1">
                    <select id="status" name="status" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Chờ Duyệt</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt Động</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ngừng Hoạt Động</option>
                    </select>
                </div>
                @error('status')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.tutors.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Hủy
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Thêm Gia Sư
            </button>
        </div>
    </form>
</div>
@endsection 