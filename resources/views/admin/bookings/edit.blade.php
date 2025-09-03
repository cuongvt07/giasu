@extends('layouts.admin')

@section('page_title', 'Chỉnh Sửa Đặt Lịch')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Chỉnh Sửa Trạng Thái Đặt Lịch</h2>
        </div>

        <div class="p-6">
            <!-- Thông tin đặt lịch -->
            <div class="mb-8 bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-md font-medium text-gray-700">Thông tin buổi học</h3>
                        <div class="mt-2 space-y-2">
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Học sinh:</span> {{ $booking->student->name }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Gia sư:</span> {{ $booking->tutor->user->name }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Môn học:</span> {{ $booking->subject->name }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-md font-medium text-gray-700">Thời gian</h3>
                        <div class="mt-2 space-y-2">
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Bắt đầu:</span> {{ $booking->start_time->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Kết thúc:</span> {{ $booking->end_time->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Giá:</span> {{ number_format($booking->total_amount, 0, ',', '.') }} đ
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <!-- Trạng thái -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                        <div class="mt-1">
                            <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ghi chú của admin -->
                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700">Ghi chú của admin</label>
                        <div class="mt-1">
                            <textarea id="admin_notes" name="admin_notes" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('admin_notes', $booking->admin_notes ?? '') }}</textarea>
                        </div>
                        @error('admin_notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.bookings.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Quay lại
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 