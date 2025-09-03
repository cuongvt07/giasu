@extends('layouts.admin')

@section('page_title', 'Cập Nhật Trạng Thái Thanh Toán')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Cập nhật trạng thái thanh toán #{{ $earning->id }}
        </h3>
        <div class="mt-3 sm:mt-0 sm:ml-4">
            <a href="{{ route('admin.earnings.show', $earning) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Xem chi tiết
            </a>
            <a href="{{ route('admin.earnings.index') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Quay lại danh sách
            </a>
        </div>
    </div>

    <!-- Thông tin thu nhập hiện tại -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Thông tin hiện tại</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Gia sư
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $earning->tutor->user->name }} ({{ $earning->tutor->user->email }})
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Buổi học
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $earning->booking->subject->name }} - 
                        {{ $earning->booking->start_time->format('d/m/Y H:i') }} - {{ $earning->booking->end_time->format('H:i') }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Số tiền
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ number_format($earning->amount, 0, ',', '.') }}đ <span class="text-sm text-gray-500">(90%)</span>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Phí nền tảng
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ number_format($earning->platform_fee, 0, ',', '.') }}đ <span class="text-sm text-gray-500">(10%)</span>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Trạng thái hiện tại
                    </dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                        @if($earning->status == 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Chờ thanh toán
                            </span>
                        @elseif($earning->status == 'processing')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Đang xử lý
                            </span>
                        @elseif($earning->status == 'completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Đã thanh toán
                            </span>
                        @elseif($earning->status == 'cancelled')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Đã hủy
                            </span>
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Ngày thanh toán
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $earning->paid_at ? $earning->paid_at->format('d/m/Y H:i:s') : 'Chưa thanh toán' }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Form cập nhật trạng thái -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Cập nhật trạng thái</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Cập nhật trạng thái thanh toán cho gia sư</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <form action="{{ route('admin.earnings.update', $earning) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="space-y-6">
                    <!-- Trạng thái thanh toán -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="pending" {{ $earning->status == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                            <option value="processing" {{ $earning->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="completed" {{ $earning->status == 'completed' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="cancelled" {{ $earning->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    
                    <!-- Mã giao dịch -->
                    <div>
                        <label for="transaction_reference" class="block text-sm font-medium text-gray-700">Mã giao dịch</label>
                        <div class="mt-1">
                            <input type="text" name="transaction_reference" id="transaction_reference" value="{{ $earning->transaction_reference }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Nhập mã giao dịch (không bắt buộc)">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Mã giao dịch chuyển khoản hoặc mã tham chiếu</p>
                    </div>
                    
                    <!-- Ghi chú -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Ghi chú</label>
                        <div class="mt-1">
                            <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Nhập ghi chú (không bắt buộc)">{{ $earning->notes }}</textarea>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Ghi chú về thanh toán hoặc thông tin liên quan</p>
                    </div>
                    
                    <!-- Lưu ý cho admin -->
                    <div class="rounded-md bg-yellow-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Lưu ý quan trọng</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>
                                        Khi bạn thay đổi trạng thái thành "Đã thanh toán", hệ thống sẽ tự động cập nhật ngày thanh toán.
                                        Bạn nên điền mã giao dịch và ghi chú cho các thanh toán quan trọng.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-5">
                        <div class="flex justify-end">
                            <a href="{{ route('admin.earnings.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Hủy
                            </a>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cập nhật trạng thái
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 