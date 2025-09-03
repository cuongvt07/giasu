@extends('layouts.admin')

@section('page_title', 'Chỉnh Sửa Gia Sư')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Chỉnh Sửa Gia Sư: {{ $tutor->user->name }}</h2>
        </div>

        <form action="{{ route('admin.tutors.update', $tutor) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="education_level" class="block text-sm font-medium text-gray-700">Trình Độ</label>
                    <select id="education_level" name="education_level" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="Cử Nhân" {{ $tutor->education_level === 'Cử Nhân' ? 'selected' : '' }}>Cử Nhân</option>
                        <option value="Thạc Sĩ" {{ $tutor->education_level === 'Thạc Sĩ' ? 'selected' : '' }}>Thạc Sĩ</option>
                        <option value="Tiến Sĩ" {{ $tutor->education_level === 'Tiến Sĩ' ? 'selected' : '' }}>Tiến Sĩ</option>
                    </select>
                    @error('education_level')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="university" class="block text-sm font-medium text-gray-700">Trường Đại Học</label>
                    <input type="text" name="university" id="university" value="{{ old('university', $tutor->university) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('university')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="major" class="block text-sm font-medium text-gray-700">Chuyên Ngành</label>
                    <input type="text" name="major" id="major" value="{{ old('major', $tutor->major) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('major')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="teaching_experience" class="block text-sm font-medium text-gray-700">Kinh Nghiệm Giảng Dạy (năm)</label>
                    <input type="number" name="teaching_experience" id="teaching_experience" value="{{ old('teaching_experience', $tutor->teaching_experience) }}" required min="0"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('teaching_experience')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hourly_rate" class="block text-sm font-medium text-gray-700">Giá/Giờ (VNĐ)</label>
                    <input type="number" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', $tutor->hourly_rate) }}" required min="0" step="1000"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('hourly_rate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700">Giới Thiệu</label>
                    <textarea name="bio" id="bio" rows="4" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('bio', $tutor->bio) }}</textarea>
                    @error('bio')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Môn Học</label>
                    <div class="mt-2 space-y-2">
                        @foreach($subjects as $subject)
                        <div class="flex items-center">
                            <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" id="subject_{{ $subject->id }}"
                                {{ in_array($subject->id, old('subjects', $tutor->subjects->pluck('id')->toArray())) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="subject_{{ $subject->id }}" class="ml-2 text-sm text-gray-700">{{ $subject->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    @error('subjects')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Cấp Học</label>
                    <div class="mt-2 space-y-2">
                        @foreach($classLevels as $level)
                        <div class="flex items-center">
                            <input type="checkbox" name="class_levels[]" value="{{ $level->id }}" id="level_{{ $level->id }}"
                                {{ in_array($level->id, old('class_levels', $tutor->classLevels->pluck('id')->toArray())) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="level_{{ $level->id }}" class="ml-2 text-sm text-gray-700">{{ $level->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    @error('class_levels')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Trạng Thái</label>
                    <select id="status" name="status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="pending" {{ $tutor->status === 'pending' ? 'selected' : '' }}>Chờ Duyệt</option>
                        <option value="active" {{ $tutor->status === 'active' ? 'selected' : '' }}>Hoạt Động</option>
                        <option value="inactive" {{ $tutor->status === 'inactive' ? 'selected' : '' }}>Ngừng Hoạt Động</option>
                    </select>
                    @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.tutors.show', $tutor) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Hủy
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cập Nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 