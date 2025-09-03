@props(['tutor'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Câu hỏi thường gặp</h3>
        </div>

        <div class="space-y-6">
            <!-- Câu hỏi 1 -->
            <div class="bg-gray-50 rounded-lg p-5">
                <h4 class="text-lg font-medium text-gray-900 mb-2 flex items-start">
                    <svg class="flex-shrink-0 h-6 w-6 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Làm thế nào để đặt lịch học với gia sư?</span>
                </h4>
                <div class="pl-8 text-gray-700">
                    <p>Để đặt lịch học với gia sư {{ $tutor->user->name }}, bạn có thể:</p>
                    <ul class="list-disc pl-5 mt-2 space-y-1 text-sm">
                        <li>Nhấn vào nút "Đặt lịch học" ở đầu trang</li>
                        <li>Chọn môn học, thời gian và địa điểm phù hợp</li>
                        <li>Xác nhận thông tin và hoàn tất đặt lịch</li>
                    </ul>
                </div>
            </div>

            <!-- Câu hỏi 2 -->
            <div class="bg-gray-50 rounded-lg p-5">
                <h4 class="text-lg font-medium text-gray-900 mb-2 flex items-start">
                    <svg class="flex-shrink-0 h-6 w-6 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Học phí được tính như thế nào?</span>
                </h4>
                <div class="pl-8 text-gray-700">
                    <p>Học phí được tính theo giờ với mức @vnd($tutor->hourly_rate)/giờ. Học phí này đã bao gồm:</p>
                    <ul class="list-disc pl-5 mt-2 space-y-1 text-sm">
                        <li>Thời gian giảng dạy trực tiếp</li>
                        <li>Tài liệu học tập cơ bản</li>
                        <li>Bài tập về nhà và đánh giá tiến độ</li>
                    </ul>
                    <p class="mt-2 text-sm text-gray-500 italic">* Có thể phát sinh thêm chi phí khác tùy thuộc vào yêu cầu đặc biệt.</p>
                </div>
            </div>

            <!-- Câu hỏi 3 -->
            <div class="bg-gray-50 rounded-lg p-5">
                <h4 class="text-lg font-medium text-gray-900 mb-2 flex items-start">
                    <svg class="flex-shrink-0 h-6 w-6 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Làm sao để hủy hoặc đổi lịch học?</span>
                </h4>
                <div class="pl-8 text-gray-700">
                    <p>Bạn có thể hủy hoặc đổi lịch học thông qua các bước sau:</p>
                    <ul class="list-disc pl-5 mt-2 space-y-1 text-sm">
                        <li>Vào mục "Lịch học" trong tài khoản của bạn</li>
                        <li>Tìm buổi học cần hủy/đổi và chọn tùy chọn tương ứng</li>
                        <li>Thông báo cho gia sư ít nhất 24 giờ trước buổi học</li>
                    </ul>
                    <p class="mt-2 text-sm text-gray-500 italic">* Việc hủy muộn có thể dẫn đến phí hủy lịch theo chính sách của chúng tôi.</p>
                </div>
            </div>

            <!-- Câu hỏi 4 -->
            <div class="bg-gray-50 rounded-lg p-5">
                <h4 class="text-lg font-medium text-gray-900 mb-2 flex items-start">
                    <svg class="flex-shrink-0 h-6 w-6 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Gia sư có đảm bảo kết quả học tập không?</span>
                </h4>
                <div class="pl-8 text-gray-700">
                    <p>Gia sư {{ $tutor->user->name }} cam kết:</p>
                    <ul class="list-disc pl-5 mt-2 space-y-1 text-sm">
                        <li>Chuẩn bị kỹ lưỡng cho mỗi buổi học</li>
                        <li>Thiết kế lộ trình học phù hợp với khả năng của học sinh</li>
                        <li>Theo dõi và đánh giá tiến độ học tập thường xuyên</li>
                        <li>Điều chỉnh phương pháp giảng dạy nếu cần thiết</li>
                    </ul>
                    <p class="mt-2">Tuy nhiên, kết quả học tập còn phụ thuộc vào nỗ lực của học sinh và thời gian học tập.</p>
                </div>
            </div>
        </div>
    </div>
</div> 