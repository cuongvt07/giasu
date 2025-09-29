@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="pb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Đặt Lịch Ca Dạy GS học với {{ $tutor->user->name }}</h1>
        <p class="mt-2 text-sm text-gray-600">
            Vui lòng chọn một trong những khung giờ rảnh của gia sư dưới đây.
        </p>
    </div>

    <!-- Thông báo lưu ý -->
    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 rounded-md p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Lưu ý:</strong> Chỉ được Đặt Lịch Ca Dạy GS trong các khung giờ rảnh đã được hiển thị dưới đây. Hệ thống đã tự động lọc bỏ các khung giờ đã có lịch học hoặc đã qua.
                </p>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="rounded-md bg-red-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">{{ session('error') }}</h3>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-6">
            <!-- Tabs cho từng ngày - Sử dụng JavaScript thuần thay vì Alpine.js -->
            <div class="booking-tabs space-y-8">
                <!-- Tab navigation -->
                <div class="border-b border-gray-200">
                    <div class="flex overflow-x-auto pb-1 hide-scrollbar" id="tab-buttons-container">
                        @forelse($availabilitiesByDate as $date => $availabilities)
                            @php
                                $dateObj = \Carbon\Carbon::parse($date);
                                $isToday = $dateObj->isToday();
                                $formattedDate = $dateObj->format('d/m/Y');
                                $dayName = $dateObj->locale('vi')->dayName;
                            @endphp
                            <button 
                                type="button"
                                class="tab-button py-2 px-4 text-sm font-medium border-b-2 whitespace-nowrap focus:outline-none text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent"
                                data-date="{{ $date }}"
                                onclick="bookingTabSystem.activateTab('{{ $date }}');"
                            >
                                {{ $dayName }} {{ $formattedDate }}
                                @if($isToday) <span class="ml-1 text-xs text-indigo-600">(Hôm nay)</span> @endif
                            </button>
                        @empty
                            <div class="py-4 text-center text-gray-500">
                                Gia sư chưa cấu hình ca dạy trong 14 ngày tới. Vui lòng liên hệ gia sư hoặc quay lại sau.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Tab content -->
                @foreach($availabilitiesByDate as $date => $availabilities)
                    <div class="tab-content space-y-6" data-date="{{ $date }}" style="display: none;">
                        <form action="{{ route('student.bookings.store', $tutor) }}" method="POST" class="bg-white" id="bookingForm_{{ $date }}">
                            @csrf
                            
                            <input type="hidden" name="selected_date" value="{{ $date }}">
                            
                            <div class="md:grid md:grid-cols-2 md:gap-6">
                                <div class="mb-6 md:mb-0">
                                    <!-- Khung giờ rảnh -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3">
                                            <span class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                </svg>
                                                Chọn khung giờ (Bước 1)
                                            </span>
                                        </label>
                                        <div class="grid grid-cols-1 gap-3">
                                            @if(count($availabilities) > 0)
                                                @foreach($availabilities as $index => $availability)
                                                    <div class="relative">
                                                        <input type="radio" id="time_slot_{{ $date }}_{{ $index }}" 
                                                            name="time_slot" 
                                                            value="{{ \Carbon\Carbon::parse($availability['start_time'])->format('H:i') }}_{{ \Carbon\Carbon::parse($availability['end_time'])->format('H:i') }}" 
                                                            class="sr-only peer time-slot-radio"
                                                            data-start="{{ \Carbon\Carbon::parse($availability['start_time'])->format('H:i') }}"
                                                            data-end="{{ \Carbon\Carbon::parse($availability['end_time'])->format('H:i') }}"
                                                            onclick="bookingTabSystem.onTimeSlotSelect(this);"
                                                            required>
                                                        <label for="time_slot_{{ $date }}_{{ $index }}" 
                                                            class="flex items-center justify-between p-4 text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-indigo-600 peer-checked:bg-indigo-50 hover:bg-gray-50 transition-all duration-200">
                                                            <div>
                                                                <span class="text-base font-medium peer-checked:text-indigo-600">{{ \Carbon\Carbon::parse($availability['start_time'])->format('H:i') }} - {{ \Carbon\Carbon::parse($availability['end_time'])->format('H:i') }}</span>
                                                            </div>
                                                            <div class="text-sm text-gray-500 peer-checked:text-indigo-500">
                                                                @php
                                                                    $startTime = \Carbon\Carbon::parse($availability['start_time'])->format('H:i');
                                                                    $endTime = \Carbon\Carbon::parse($availability['end_time'])->format('H:i');
                                                                    $startDateTime = \Carbon\Carbon::createFromFormat('H:i', $startTime);
                                                                    $endDateTime = \Carbon\Carbon::createFromFormat('H:i', $endTime);
                                                                    $duration = $startDateTime->diffInHours($endDateTime) . ' giờ';
                                                                @endphp
                                                                <span class="flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    {{ $duration }}
                                                                </span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="p-4 rounded-md bg-gray-50 text-gray-600 text-center">
                                                    Không có khung giờ rảnh nào trong ngày này.
                                                </div>
                                            @endif
                                        </div>
                                        @error('time_slot')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <!-- Môn học -->
                                    <div class="mb-6">
                                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-3">
                                            <span class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                                </svg>
                                                Chọn môn học (Bước 2)
                                            </span>
                                        </label>
                                        <select id="subject_id_{{ $date }}" name="subject_id" class="mt-1 block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" onchange="bookingTabSystem.calculatePrice();" required>
                                            <option value="">Chọn môn học</option>
                                            @foreach($tutor->subjects as $subject)
                                                <option value="{{ $subject->id }}" data-price="{{ $subject->pivot->price_per_hour ?? $tutor->hourly_rate }}">
                                                    {{ $subject->name }} - {{ format_vnd($subject->pivot->price_per_hour ?? $tutor->hourly_rate) }}/giờ
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('subject_id')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Ghi chú -->
                                    <div class="mb-6">
                                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-3">
                                            <span class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd" />
                                                </svg>
                                                Ghi chú (Tùy chọn)
                                            </span>
                                        </label>
                                        <div class="mt-1">
                                            <textarea id="notes" name="notes" rows="3" 
                                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                                                placeholder="Nhập ghi chú cho gia sư về nội dung, yêu cầu hoặc mong đợi của bạn..."></textarea>
                                        </div>
                                        @error('notes')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Tổng tiền -->

<!-- Tổng tiền -->
<div class="mt-8 border-t border-gray-200 pt-6">
    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg">
        <span class="text-base font-medium text-gray-700">Tổng tiền:</span>
        <div>
            <span id="total_amount" class="text-xl font-semibold text-indigo-600">0đ</span>
            <div class="text-xs text-gray-500" id="hour_detail"></div>
        </div>
    </div>
</div>

<div class="mt-6 flex justify-end">
    <a href="{{ route('tutors.show', $tutor) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
        Quay lại
    </a>
    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200" id="bookingSubmitBtn" disabled>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
        </svg>
        Đặt Lịch Ca Dạy GS và thanh toán
    </button>
</div>
                        </form>
                    </div>
                @endforeach

                @if(count($availabilitiesByDate) === 0)
                    <div class="py-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Không có ca dạy</h3>
                        <p class="mt-1 text-sm text-gray-500">Gia sư chưa cấu hình ca dạy trong 14 ngày tới.</p>
                        <div class="mt-6">
                            <a href="{{ route('tutors.show', $tutor) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Quay lại trang gia sư
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Hệ thống quản lý tab Đặt Lịch Ca Dạy GS và tính giá tiền - sử dụng JavaScript thuần, không dùng Alpine.js
    const bookingTabSystem = {
        // Biến lưu trạng thái hiện tại
        activeTab: null,          // Tab đang active
        selectedTimeSlot: null,   // Khung giờ đã chọn
        selectedSubject: null,    // Môn học đã chọn
        
        // Kích hoạt tab
        activateTab: function(tabDate) {
            console.clear();
            console.log('Kích hoạt tab:', tabDate);
            
            // Cập nhật trạng thái tab active
            this.activeTab = tabDate;
            
            // 1. Cập nhật giao diện tab buttons (loại bỏ active state của tất cả tab)
            const allTabButtons = document.querySelectorAll('.tab-button');
            allTabButtons.forEach(btn => {
                if (btn.getAttribute('data-date') === tabDate) {
                    btn.classList.add('text-indigo-600', 'border-indigo-600');
                    btn.classList.remove('text-gray-500', 'border-transparent');
                } else {
                    btn.classList.remove('text-indigo-600', 'border-indigo-600');
                    btn.classList.add('text-gray-500', 'border-transparent');
                }
            });
            
            // 2. Hiển thị nội dung tab tương ứng
            const allTabContents = document.querySelectorAll('.tab-content');
            allTabContents.forEach(content => {
                if (content.getAttribute('data-date') === tabDate) {
                    content.style.display = 'block';
                } else {
                    content.style.display = 'none';
                }
            });
            
            // 3. Kiểm tra xem trong tab hiện tại đã có time slot nào được chọn chưa
            this.checkSelectedTimeSlot();
            
            // 4. Tính lại giá tiền
            this.calculatePrice();
        },
        
        // Kiểm tra xem trong tab hiện tại đã có time slot nào được chọn chưa
        checkSelectedTimeSlot: function() {
            if (!this.activeTab) return;
            
            const activeTabContent = document.querySelector(`.tab-content[data-date="${this.activeTab}"]`);
            if (!activeTabContent) return;
            
            const checkedTimeSlot = activeTabContent.querySelector('.time-slot-radio:checked');
            if (checkedTimeSlot) {
                this.selectedTimeSlot = {
                    id: checkedTimeSlot.id,
                    value: checkedTimeSlot.value,
                    start: checkedTimeSlot.getAttribute('data-start'),
                    end: checkedTimeSlot.getAttribute('data-end')
                };
                console.log('Đã tìm thấy khung giờ đã chọn trong tab hiện tại:', this.selectedTimeSlot);
            } else {
                this.selectedTimeSlot = null;
                console.log('Không có khung giờ nào được chọn trong tab hiện tại');
            }
        },
        
        // Sự kiện khi chọn khung giờ
        onTimeSlotSelect: function(radioElement) {
            this.selectedTimeSlot = {
                id: radioElement.id,
                value: radioElement.value,
                start: radioElement.getAttribute('data-start'),
                end: radioElement.getAttribute('data-end')
            };
            
            console.log('Đã chọn khung giờ:', this.selectedTimeSlot);
            this.calculatePrice();
        },
        
        // Tính giá tiền dựa trên thông tin đã chọn
        calculatePrice: function() {
            console.log('Đang tính toán giá tiền...', this.activeTab);
            
            if (!this.activeTab) {
                console.log('Không có tab nào đang active');
                return;
            }
            
            // Lấy đúng phần tử cho mỗi tab
            const activeTabContent = document.querySelector(`.tab-content[data-date="${this.activeTab}"]`);
            if (!activeTabContent) {
                console.log('Không tìm thấy nội dung tab active');
                return;
            }
            
            // Sử dụng querySelector trong tab hiện tại thay vì document.getElementById
            const totalAmountElement = activeTabContent.querySelector('#total_amount');
            const hourDetailElement = activeTabContent.querySelector('#hour_detail');
            const submitButton = activeTabContent.querySelector('#bookingSubmitBtn');
            
            console.log('Các phần tử UI:', { 
                totalAmount: totalAmountElement, 
                hourDetail: hourDetailElement, 
                submitButton: submitButton 
            });
            
            // Sử dụng ID chính xác cho tab đang active
            const subjectSelect = document.getElementById(`subject_id_${this.activeTab}`);
            console.log('Tìm select với ID:', `subject_id_${this.activeTab}`, subjectSelect);
            
            if (!subjectSelect || subjectSelect.value === '') {
                console.log('Chưa chọn môn học');
                if (totalAmountElement) totalAmountElement.textContent = '0đ';
                if (hourDetailElement) hourDetailElement.textContent = '';
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.classList.add('bg-indigo-400');
                    submitButton.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                }
                return;
            }
            
            // Lưu thông tin môn học đã chọn
            const selectedOption = subjectSelect.options[subjectSelect.selectedIndex];
            this.selectedSubject = {
                id: subjectSelect.value,
                name: selectedOption.textContent.trim(),
                price: parseFloat(selectedOption.getAttribute('data-price'))
            };
            
            console.log('Đã chọn môn học:', this.selectedSubject);
            
            if (!this.selectedTimeSlot) {
                console.log('Chưa chọn khung giờ');
                if (totalAmountElement) totalAmountElement.textContent = '0đ';
                if (hourDetailElement) hourDetailElement.textContent = '';
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.classList.add('bg-indigo-400');
                    submitButton.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                }
                return;
            }
            
            // Đã chọn đủ thông tin, tính toán giá tiền
            try {
                console.log('Tính toán với:', {
                    'Khung giờ': this.selectedTimeSlot.value,
                    'Giá theo giờ': this.selectedSubject.price
                });
                
                // Phân tích thời gian từ time slot
                const timeSlotParts = this.selectedTimeSlot.value.split('_');
                const startTime = timeSlotParts[0];
                const endTime = timeSlotParts[1];
                
                // Tính số giờ
                const [startHour, startMinute] = startTime.split(':').map(Number);
                const [endHour, endMinute] = endTime.split(':').map(Number);
                
                const startDate = new Date(2023, 0, 1, startHour, startMinute);
                const endDate = new Date(2023, 0, 1, endHour, endMinute);
                
                // Tính số phút chênh lệch
                const diffMinutes = (endDate - startDate) / (1000 * 60);
                let duration = Math.floor(diffMinutes / 60);
                if (diffMinutes % 60 >= 30) {
                    duration += 0.5;
                }
                
                console.log('Số giờ:', duration);
                
                // Tính tổng tiền
                const total = this.selectedSubject.price * duration;
                console.log('Tổng tiền:', total);
                
                // Hiển thị kết quả
                if (hourDetailElement) hourDetailElement.textContent = `${duration} giờ x ${new Intl.NumberFormat('vi-VN').format(this.selectedSubject.price)}đ`;
                if (totalAmountElement) totalAmountElement.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total).replace('₫', 'đ');
                
                // Kích hoạt nút Đặt Lịch Ca Dạy GS
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.classList.remove('bg-indigo-400');
                    submitButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                }
            } catch (error) {
                console.error('Lỗi khi tính toán giá tiền:', error);
                if (totalAmountElement) totalAmountElement.textContent = '0đ';
                if (hourDetailElement) hourDetailElement.textContent = '';
                if (submitButton) submitButton.disabled = true;
            }
        },
        
        // Khởi tạo hệ thống
        init: function() {
            console.log('Khởi tạo hệ thống Đặt Lịch Ca Dạy GS');
            
            // Tìm tab đầu tiên và kích hoạt
            const firstTabButton = document.querySelector('.tab-button');
            if (firstTabButton) {
                const firstTabDate = firstTabButton.getAttribute('data-date');
                if (firstTabDate) {
                    this.activateTab(firstTabDate);
                }
            }
            
            console.log('Đã khởi tạo xong hệ thống Đặt Lịch Ca Dạy GS');
        }
    };
    
    // Chạy khi trang đã tải xong
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Trang đã tải xong, khởi tạo hệ thống Đặt Lịch Ca Dạy GS...');
        bookingTabSystem.init();
    });
</script>

<style>
    /* Hiệu ứng cho tab */
    .tab-button {
        transition: all 0.2s ease-in-out;
    }
    
    /* Thêm animation cho tab content */
    .tab-content {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    /* Làm nổi bật nút tab active */
    .tab-button.text-indigo-600 {
        position: relative;
    }
    
    .tab-button.text-indigo-600::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #4f46e5;
    }
</style>
@endpush
@endsection