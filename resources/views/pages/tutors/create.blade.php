@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900">Đăng Ký Làm Gia Sư</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Vui lòng cung cấp thông tin chi tiết để chúng tôi có thể xem xét hồ sơ của bạn.
                    </p>

                    <form action="{{ route('tutors.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                            <!-- Trình độ học vấn -->
                            <div class="sm:col-span-2">
                                <label for="education_level" class="block text-sm font-medium text-gray-700">
                                    Trình độ học vấn <span class="text-red-500">*</span>
                                </label>
                                <select id="education_level" name="education_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Chọn trình độ</option>
                                    <option value="Sinh viên">Sinh viên</option>
                                    <option value="Cử nhân">Cử nhân</option>
                                    <option value="Thạc sĩ">Thạc sĩ</option>
                                    <option value="Tiến sĩ">Tiến sĩ</option>
                                    <option value="Giảng viên">Giảng viên</option>
                                </select>
                            </div>

                            <!-- Trường đại học -->
                            <div class="sm:col-span-2">
                                <label for="university" class="block text-sm font-medium text-gray-700">
                                    Trường đại học
                                </label>
                                <input type="text" name="university" id="university" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Chuyên ngành -->
                            <div class="sm:col-span-2">
                                <label for="major" class="block text-sm font-medium text-gray-700">
                                    Chuyên ngành
                                </label>
                                <input type="text" name="major" id="major" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Kinh nghiệm giảng dạy -->
                            <div class="sm:col-span-2">
                                <label for="teaching_experience" class="block text-sm font-medium text-gray-700">
                                    Kinh nghiệm giảng dạy <span class="text-red-500">*</span>
                                </label>
                                <textarea id="teaching_experience" name="teaching_experience" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>

                            <!-- Giới thiệu bản thân -->
                            <div class="sm:col-span-2">
                                <label for="bio" class="block text-sm font-medium text-gray-700">
                                    Giới thiệu bản thân <span class="text-red-500">*</span>
                                </label>
                                <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>

                            <!-- Ảnh đại diện -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Ảnh đại diện
                                </label>
                                <div class="mt-1 flex items-center">
                                    <input type="file" name="avatar" id="avatar" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>
                            </div>

                            <!-- Học phí -->
                            <div>
                                <label for="hourly_rate" class="block text-sm font-medium text-gray-700">
                                    Học phí (VNĐ/giờ) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="hourly_rate" id="hourly_rate" min="0" step="1000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>


                            <!-- Môn học -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Môn học <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-2 grid grid-cols-2 gap-2">
                                    @foreach($subjects->groupBy('category') as $category => $subjectGroup)
                                        <div class="space-y-2">
                                            <h4 class="font-medium text-gray-700">{{ $category }}</h4>
                                            @foreach($subjectGroup as $subject)
                                                <div class="relative flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input id="subject_{{ $subject->id }}" name="subjects[]" 
                                                            value="{{ $subject->id }}" type="checkbox" 
                                                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label for="subject_{{ $subject->id }}" class="font-medium text-gray-700">{{ $subject->name }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                                @error('subjects')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quản lý giá cho từng môn học -->
                            <div class="sm:col-span-2 mt-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Quản lý giá cho từng môn học</h3>
                                <p class="text-sm text-gray-600 mb-4">Thiết lập giá cụ thể cho từng môn học. Nếu để trống, hệ thống sẽ sử dụng giá mặc định.</p>
                                
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Môn học</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá mỗi giờ (VNĐ)</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chi tiết kinh nghiệm</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" id="subject-prices-container">
                                            <!-- JavaScript sẽ tự động tạo hàng cho các môn học đã chọn -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Cấp học -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Cấp học <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-2 grid grid-cols-2 gap-2">
                                    @foreach($classLevels as $level)
                                        <div class="relative flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="level_{{ $level->id }}" name="class_levels[]" value="{{ $level->id }}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="level_{{ $level->id }}" class="font-medium text-gray-700">{{ $level->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="pt-5">
                            <div class="flex justify-end">
                                <button type="button" onclick="window.history.back()" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Hủy
                                </button>
                                <button type="submit" class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Đăng ký
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    // Script để cập nhật bảng giá môn học khi chọn/bỏ chọn môn học
    document.addEventListener('DOMContentLoaded', function() {
        const subjectCheckboxes = document.querySelectorAll('input[name="subjects[]"]');
        const priceContainer = document.getElementById('subject-prices-container');
        
        // Cập nhật hiển thị bảng giá
        function updatePriceTable() {
            // Lấy giá mặc định hiện tại
            const defaultPrice = document.getElementById('hourly_rate').value || 0;
            
            // Xóa tất cả các hàng hiện tại và tạo lại bảng
            priceContainer.innerHTML = '';
            
            // Thêm lại các hàng cho các môn học đã chọn
            subjectCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const subjectId = checkbox.value;
                    const subjectLabel = document.querySelector(`label[for="subject_${subjectId}"]`);
                    const subjectName = subjectLabel ? subjectLabel.textContent.trim() : `Môn học ${subjectId}`;
                    
                    // Nếu có thể tìm thông tin category
                    let categoryName = "";
                    const parentElement = subjectLabel.closest('.space-y-2');
                    if (parentElement) {
                        const categoryHeader = parentElement.querySelector('h4');
                        if (categoryHeader) {
                            categoryName = categoryHeader.textContent.trim();
                        }
                    }
                    
                    // Tìm giá và mô tả kinh nghiệm hiện tại (nếu có)
                    let existingPrice = defaultPrice;
                    let existingExperience = '';
                    
                    const existingPriceInput = document.querySelector(`input[name="subject_prices[${subjectId}][price]"]`);
                    if (existingPriceInput) {
                        existingPrice = existingPriceInput.value;
                    }
                    
                    const existingExpTextarea = document.querySelector(`textarea[name="subject_prices[${subjectId}][experience]"]`);
                    if (existingExpTextarea) {
                        existingExperience = existingExpTextarea.value;
                    }
                    
                    // Tạo hàng mới
                    createNewPriceRow(subjectId, subjectName, categoryName, existingPrice, existingExperience);
                }
            });
        }

        // Tạo hàng mới cho môn học đã chọn
        function createNewPriceRow(subjectId, subjectName, categoryName, price, experience) {
            const newRow = document.createElement('tr');
            newRow.className = 'subject-price-row';
            newRow.dataset.subjectId = subjectId;
            
            newRow.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    ${subjectName} ${categoryName ? `(${categoryName})` : ''}
                    <input type="hidden" name="subject_prices[${subjectId}][id]" value="${subjectId}">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <input type="number" name="subject_prices[${subjectId}][price]" 
                        value="${price}" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Giá mặc định">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <textarea name="subject_prices[${subjectId}][experience]" rows="2"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Chi tiết kinh nghiệm giảng dạy môn này">${experience}</textarea>
                </td>
            `;
            
            priceContainer.appendChild(newRow);
        }

        // Khởi tạo bảng giá
        updatePriceTable();

        // Lắng nghe sự kiện thay đổi trên các checkbox môn học
        subjectCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updatePriceTable);
        });
        
        // Lắng nghe sự kiện thay đổi giá mặc định
        document.getElementById('hourly_rate').addEventListener('change', function() {
            // Cập nhật giá mặc định cho các môn chưa được thiết lập giá
            const emptyPriceInputs = document.querySelectorAll('input[name^="subject_prices"][name$="[price]"]');
            emptyPriceInputs.forEach(input => {
                if (!input.value) {
                    input.value = this.value;
                }
            });
        });
    });
</script>
@endsection 