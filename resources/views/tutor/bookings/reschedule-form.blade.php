@extends('layouts.tutor')

@section('page_title', 'Yêu Cầu Đổi Lịch')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Yêu Cầu Đổi Lịch</h1>
        <a href="{{ route('tutor.bookings.show', $booking) }}" class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300">
            Quay lại
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-700 mb-4">Thông Tin Buổi Học</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Học sinh:</p>
                <p class="font-medium">{{ $booking->student->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Môn học:</p>
                <p class="font-medium">{{ $booking->subject->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Thời gian hiện tại:</p>
                <p class="font-medium">{{ $booking->start_time->format('d/m/Y H:i') }} - {{ $booking->end_time->format('H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Trạng thái:</p>
                <p class="font-medium">
                    @switch($booking->status)
                        @case('pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Chờ xác nhận
                            </span>
                            @break
                        @case('confirmed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Đã xác nhận
                            </span>
                            @break
                        @case('scheduled')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                Đã lên lịch
                            </span>
                            @break
                        @default
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $booking->status }}
                            </span>
                    @endswitch
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-medium text-gray-700 mb-4">Yêu Cầu Đổi Lịch</h2>
        
        <form action="{{ route('tutor.bookings.reschedule.store', $booking) }}" method="POST" id="reschedule-form">
            @csrf
            
            <div class="mb-4">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Lý do đổi lịch <span class="text-red-500">*</span></label>
                <textarea id="reason" name="reason" rows="3" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <h3 class="text-md font-medium text-gray-700 mb-2">Đề xuất thời gian mới <span class="text-red-500">*</span></h3>
                <p class="text-sm text-gray-500 mb-4">Vui lòng đề xuất ít nhất một khung thời gian thay thế. Học sinh sẽ chọn một trong các khung thời gian này.</p>
                
                <div id="time-options-container">
                    <div class="time-option bg-gray-50 p-4 rounded-md mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ngày</label>
                                <input type="date" name="reschedule_options[0][date]" required 
                                    min="{{ now()->addDay()->format('Y-m-d') }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Giờ bắt đầu</label>
                                <input type="time" name="reschedule_options[0][start_time]" required 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Giờ kết thúc</label>
                                <input type="time" name="reschedule_options[0][end_time]" required 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="button" id="add-time-option" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Thêm tùy chọn thời gian
                </button>
                
                @error('reschedule_options')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Ghi chú bổ sung</label>
                <textarea id="notes" name="notes" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end space-x-3">
                <a href="{{ route('tutor.bookings.show', $booking) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Hủy
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Gửi yêu cầu đổi lịch
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('time-options-container');
        const addButton = document.getElementById('add-time-option');
        let optionCount = 1;
        
        addButton.addEventListener('click', function() {
            if (optionCount >= 5) {
                alert('Bạn chỉ có thể thêm tối đa 5 tùy chọn thời gian.');
                return;
            }
            
            const newOption = document.createElement('div');
            newOption.className = 'time-option bg-gray-50 p-4 rounded-md mb-4';
            newOption.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <h4 class="text-sm font-medium text-gray-700">Tùy chọn ${optionCount + 1}</h4>
                    <button type="button" class="remove-option text-red-500 hover:text-red-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày</label>
                        <input type="date" name="reschedule_options[${optionCount}][date]" required 
                            min="${new Date().toISOString().split('T')[0]}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giờ bắt đầu</label>
                        <input type="time" name="reschedule_options[${optionCount}][start_time]" required 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giờ kết thúc</label>
                        <input type="time" name="reschedule_options[${optionCount}][end_time]" required 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            `;
            
            container.appendChild(newOption);
            optionCount++;
            
            // Add event listener to remove button
            const removeButton = newOption.querySelector('.remove-option');
            removeButton.addEventListener('click', function() {
                container.removeChild(newOption);
                optionCount--;
                updateOptionNumbers();
            });
        });
        
        function updateOptionNumbers() {
            const options = container.querySelectorAll('.time-option');
            options.forEach((option, index) => {
                const heading = option.querySelector('h4');
                if (heading && index > 0) { // Skip the first option which doesn't have a heading
                    heading.textContent = `Tùy chọn ${index + 1}`;
                }
                
                // Update input names
                const inputs = option.querySelectorAll('input');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        input.setAttribute('name', newName);
                    }
                });
            });
        }

        // Form submission handler để kiểm tra và log lỗi
        const form = document.getElementById('reschedule-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log('Form is being submitted');
                
                // Kiểm tra validate client-side (optional)
                const reason = document.getElementById('reason').value;
                if (!reason.trim()) {
                    console.error('Reason is required');
                    alert('Vui lòng nhập lý do đổi lịch');
                    e.preventDefault();
                    return false;
                }
                
                // Kiểm tra xem có ít nhất một tùy chọn thời gian không
                const dateInputs = document.querySelectorAll('input[name^="reschedule_options"][name$="[date]"]');
                if (dateInputs.length === 0) {
                    console.error('No time options available');
                    alert('Vui lòng thêm ít nhất một tùy chọn thời gian');
                    e.preventDefault();
                    return false;
                }
                
                // Tiếp tục submit form nếu mọi thứ OK
                return true;
            });
        }
    });
</script>
@endpush
@endsection 