@extends('layouts.admin')

@section('page_title', 'Quản Lý Thu Nhập Gia Sư')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Thống kê tổng quan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500">Thu nhập chờ thanh toán</h3>
            <div class="mt-1 flex items-baseline">
                <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                    {{ number_format($stats['total_pending'], 0, ',', '.') }}đ
                </div>
            </div>
            <div class="text-xs text-gray-500 mt-2">{{ $stats['count_pending'] }} khoản chờ thanh toán</div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500">Đang xử lý thanh toán</h3>
            <div class="mt-1 flex items-baseline">
                <div class="flex items-baseline text-2xl font-semibold text-yellow-600">
                    {{ number_format($stats['total_processing'], 0, ',', '.') }}đ
                </div>
            </div>
            <div class="text-xs text-gray-500 mt-2">{{ $stats['count_processing'] }} khoản đang xử lý</div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500">Đã thanh toán</h3>
            <div class="mt-1 flex items-baseline">
                <div class="flex items-baseline text-2xl font-semibold text-green-600">
                    {{ number_format($stats['total_completed'], 0, ',', '.') }}đ
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-500">Phí nền tảng</h3>
            <div class="mt-1 flex items-baseline">
                <div class="flex items-baseline text-2xl font-semibold text-blue-600">
                    {{ number_format($stats['total_platform_fee'], 0, ',', '.') }}đ
                </div>
            </div>
            <div class="text-xs text-gray-500 mt-2">10% trên mỗi giao dịch</div>
        </div>
    </div>

    <!-- Danh sách thu nhập chờ thanh toán -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Thu nhập chờ thanh toán</h2>
        </div>
        @if($pendingEarnings->count() > 0)
        <form id="processingForm" action="{{ route('admin.earnings.mark-as-processing') }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="select-all-pending" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mã
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Gia sư
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Buổi học
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Số tiền
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày tạo
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingEarnings as $earning)
                        <tr>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <input type="checkbox" name="earnings[]" value="{{ $earning->id }}" class="pending-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                #{{ $earning->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $earning->tutor->user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ID: {{ $earning->tutor_id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $earning->booking->subject->name }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $earning->booking->start_time->format('d/m/Y H:i') }} - {{ $earning->booking->end_time->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($earning->amount, 0, ',', '.') }}đ</div>
                                <div class="text-xs text-gray-500">Phí: {{ number_format($earning->platform_fee, 0, ',', '.') }}đ</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $earning->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.earnings.show', $earning) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Chi tiết</a>
                                <a href="{{ route('admin.earnings.edit', $earning) }}" class="text-green-600 hover:text-green-900">Cập nhật</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center">
                <div>
                    <span class="text-sm text-gray-700">
                        Hiển thị {{ $pendingEarnings->firstItem() ?? 0 }}-{{ $pendingEarnings->lastItem() ?? 0 }} trên {{ $pendingEarnings->total() }} kết quả
                    </span>
                </div>
                <div class="processingActionButtons" style="display: none;">
                    <input type="text" name="notes" placeholder="Ghi chú (không bắt buộc)" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm mr-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Đánh dấu đang xử lý
                    </button>
                </div>
                <div>
                    {{ $pendingEarnings->links() }}
                </div>
            </div>
        </form>
        @else
        <div class="px-4 py-5 sm:px-6 text-gray-500 text-center">
            Không có thu nhập nào đang chờ thanh toán
        </div>
        @endif
    </div>

    <!-- Danh sách thu nhập đang xử lý -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Đang xử lý thanh toán</h2>
        </div>
        @if($processingEarnings->count() > 0)
        <form id="completedForm" action="{{ route('admin.earnings.mark-as-completed') }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="select-all-processing" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mã
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Gia sư
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Buổi học
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Số tiền
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày tạo
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($processingEarnings as $earning)
                        <tr>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <input type="checkbox" name="earnings[]" value="{{ $earning->id }}" class="processing-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                #{{ $earning->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $earning->tutor->user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ID: {{ $earning->tutor_id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $earning->booking->subject->name }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $earning->booking->start_time->format('d/m/Y H:i') }} - {{ $earning->booking->end_time->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($earning->amount, 0, ',', '.') }}đ</div>
                                <div class="text-xs text-gray-500">Phí: {{ number_format($earning->platform_fee, 0, ',', '.') }}đ</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $earning->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.earnings.show', $earning) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Chi tiết</a>
                                <a href="{{ route('admin.earnings.edit', $earning) }}" class="text-green-600 hover:text-green-900">Cập nhật</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center">
                <div>
                    <span class="text-sm text-gray-700">
                        Hiển thị {{ $processingEarnings->firstItem() ?? 0 }}-{{ $processingEarnings->lastItem() ?? 0 }} trên {{ $processingEarnings->total() }} kết quả
                    </span>
                </div>
                <div class="completedActionButtons" style="display: none;">
                    <input type="text" name="transaction_reference" placeholder="Mã giao dịch (không bắt buộc)" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm mr-3">
                    <input type="text" name="notes" placeholder="Ghi chú (không bắt buộc)" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm mr-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Đánh dấu đã thanh toán
                    </button>
                </div>
                <div>
                    {{ $processingEarnings->links() }}
                </div>
            </div>
        </form>
        @else
        <div class="px-4 py-5 sm:px-6 text-gray-500 text-center">
            Không có thu nhập nào đang xử lý
        </div>
        @endif
    </div>

    <!-- Danh sách thu nhập đã thanh toán -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-medium text-gray-900">Đã thanh toán gần đây</h2>
        </div>
        @if($completedEarnings->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mã
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Gia sư
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Buổi học
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Số tiền
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày thanh toán
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($completedEarnings as $earning)
                    <tr>
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                            #{{ $earning->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $earning->tutor->user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        ID: {{ $earning->tutor_id }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $earning->booking->subject->name }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $earning->booking->start_time->format('d/m/Y H:i') }} - {{ $earning->booking->end_time->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ number_format($earning->amount, 0, ',', '.') }}đ</div>
                            <div class="text-xs text-gray-500">Phí: {{ number_format($earning->platform_fee, 0, ',', '.') }}đ</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $earning->paid_at ? $earning->paid_at->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.earnings.show', $earning) }}" class="text-indigo-600 hover:text-indigo-900">Chi tiết</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center">
            <div>
                <span class="text-sm text-gray-700">
                    Hiển thị {{ $completedEarnings->firstItem() ?? 0 }}-{{ $completedEarnings->lastItem() ?? 0 }} trên {{ $completedEarnings->total() }} kết quả
                </span>
            </div>
            <div>
                {{ $completedEarnings->links() }}
            </div>
        </div>
        @else
        <div class="px-4 py-5 sm:px-6 text-gray-500 text-center">
            Không có thu nhập nào đã thanh toán
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chức năng chọn tất cả
        var selectAllPending = document.getElementById('select-all-pending');
        var pendingCheckboxes = document.querySelectorAll('.pending-checkbox');
        
        if (selectAllPending) {
            selectAllPending.addEventListener('change', function() {
                pendingCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = selectAllPending.checked;
                });
                toggleProcessingButtons();
            });
        }
        
        pendingCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', toggleProcessingButtons);
        });
        
        var selectAllProcessing = document.getElementById('select-all-processing');
        var processingCheckboxes = document.querySelectorAll('.processing-checkbox');
        
        if (selectAllProcessing) {
            selectAllProcessing.addEventListener('change', function() {
                processingCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = selectAllProcessing.checked;
                });
                toggleCompletedButtons();
            });
        }
        
        processingCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', toggleCompletedButtons);
        });
        
        // Hiển thị nút xử lý hàng loạt
        document.getElementById('show-mass-processing').addEventListener('click', function() {
            if (document.querySelector('.processingActionButtons').style.display === 'none') {
                document.querySelector('.processingActionButtons').style.display = 'block';
                selectAllPending.checked = true;
                pendingCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                });
            } else {
                document.querySelector('.processingActionButtons').style.display = 'none';
                selectAllPending.checked = false;
                pendingCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }
        });
        
        function toggleProcessingButtons() {
            var anyChecked = Array.from(pendingCheckboxes).some(function(checkbox) {
                return checkbox.checked;
            });
            
            document.querySelector('.processingActionButtons').style.display = anyChecked ? 'block' : 'none';
        }
        
        function toggleCompletedButtons() {
            var anyChecked = Array.from(processingCheckboxes).some(function(checkbox) {
                return checkbox.checked;
            });
            
            document.querySelector('.completedActionButtons').style.display = anyChecked ? 'block' : 'none';
        }
    });
</script>
@endpush
@endsection 