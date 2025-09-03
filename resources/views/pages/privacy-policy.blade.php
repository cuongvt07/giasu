@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Chính Sách Bảo Mật</h1>
                    
                    <div class="prose prose-indigo max-w-none">
                        <p class="text-gray-600 mb-4">Cập nhật lần cuối: {{ now()->format('d/m/Y') }}</p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">1. Giới thiệu</h2>
                        <p class="mb-4">
                            Chào mừng bạn đến với Nền tảng Kết nối Gia sư. Chúng tôi cam kết bảo vệ quyền riêng tư và thông tin cá nhân của bạn. 
                            Chính sách bảo mật này mô tả cách chúng tôi thu thập, sử dụng, tiết lộ, và bảo vệ thông tin của bạn khi bạn sử dụng 
                            dịch vụ của chúng tôi.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">2. Thông tin chúng tôi thu thập</h2>
                        <h3 class="text-lg font-medium text-gray-900 mt-6 mb-3">Thông tin cá nhân</h3>
                        <p class="mb-3">Khi bạn đăng ký và sử dụng dịch vụ của chúng tôi, chúng tôi có thể thu thập các thông tin sau:</p>
                        <ul class="list-disc ml-6 mb-4">
                            <li>Thông tin cá nhân: họ tên, địa chỉ email, số điện thoại</li>
                            <li>Thông tin xác thực: mật khẩu, câu hỏi bảo mật</li>
                            <li>Thông tin hồ sơ: trình độ học vấn, kinh nghiệm giảng dạy (đối với gia sư)</li>
                            <li>Thông tin thanh toán: thông tin thẻ tín dụng, thông tin ngân hàng</li>
                            <li>Hình ảnh, chứng chỉ và các tài liệu xác thực khác</li>
                        </ul>
                        
                        <h3 class="text-lg font-medium text-gray-900 mt-6 mb-3">Thông tin tự động</h3>
                        <p class="mb-4">
                            Chúng tôi cũng tự động thu thập một số thông tin khi bạn truy cập và sử dụng nền tảng của chúng tôi, bao gồm:
                        </p>
                        <ul class="list-disc ml-6 mb-4">
                            <li>Thông tin thiết bị: loại thiết bị, hệ điều hành, trình duyệt web</li>
                            <li>Thông tin sử dụng: thời gian truy cập, các trang được xem, tính năng sử dụng</li>
                            <li>Thông tin vị trí: dựa trên địa chỉ IP của bạn</li>
                            <li>Cookie và công nghệ theo dõi tương tự</li>
                        </ul>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">3. Cách chúng tôi sử dụng thông tin</h2>
                        <p class="mb-3">Chúng tôi sử dụng thông tin thu thập được cho các mục đích sau:</p>
                        <ul class="list-disc ml-6 mb-4">
                            <li>Cung cấp, duy trì và cải thiện dịch vụ của chúng tôi</li>
                            <li>Xác thực và xác minh danh tính của gia sư và học sinh</li>
                            <li>Kết nối gia sư và học sinh phù hợp</li>
                            <li>Xử lý các giao dịch thanh toán</li>
                            <li>Gửi thông báo, cập nhật, và thông tin liên quan đến dịch vụ</li>
                            <li>Phân tích xu hướng sử dụng và tối ưu hóa trải nghiệm người dùng</li>
                            <li>Phát hiện và ngăn chặn các hoạt động gian lận, lạm dụng</li>
                            <li>Tuân thủ các nghĩa vụ pháp lý</li>
                        </ul>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">4. Chia sẻ thông tin</h2>
                        <p class="mb-3">Chúng tôi có thể chia sẻ thông tin của bạn trong các trường hợp sau:</p>
                        <ul class="list-disc ml-6 mb-4">
                            <li><strong>Giữa gia sư và học sinh:</strong> Khi học sinh đặt lịch với gia sư, chúng tôi sẽ chia sẻ thông tin liên hệ và các chi tiết cần thiết khác.</li>
                            <li><strong>Với nhà cung cấp dịch vụ:</strong> Chúng tôi có thể chia sẻ thông tin với các nhà cung cấp dịch vụ bên thứ ba giúp chúng tôi cung cấp dịch vụ (ví dụ: xử lý thanh toán, lưu trữ đám mây).</li>
                            <li><strong>Theo yêu cầu pháp lý:</strong> Chúng tôi có thể tiết lộ thông tin nếu được yêu cầu bởi pháp luật hoặc quy trình pháp lý.</li>
                            <li><strong>Với sự đồng ý của bạn:</strong> Chúng tôi có thể chia sẻ thông tin với bên thứ ba khi bạn cấp quyền rõ ràng cho chúng tôi.</li>
                        </ul>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">5. Bảo mật thông tin</h2>
                        <p class="mb-4">
                            Chúng tôi áp dụng các biện pháp bảo mật hợp lý để bảo vệ thông tin cá nhân của bạn khỏi truy cập trái phép, 
                            mất mát, hoặc tiết lộ. Tuy nhiên, không có phương thức truyền tải qua internet hoặc lưu trữ điện tử nào là 
                            an toàn 100%. Do đó, mặc dù chúng tôi nỗ lực bảo vệ thông tin cá nhân của bạn, chúng tôi không thể đảm bảo 
                            tính bảo mật tuyệt đối.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">6. Quyền của bạn</h2>
                        <p class="mb-3">Tùy thuộc vào luật hiện hành, bạn có thể có các quyền sau liên quan đến thông tin cá nhân của bạn:</p>
                        <ul class="list-disc ml-6 mb-4">
                            <li>Quyền truy cập và xem thông tin cá nhân của bạn</li>
                            <li>Quyền cập nhật hoặc sửa chữa thông tin không chính xác</li>
                            <li>Quyền yêu cầu xóa thông tin cá nhân của bạn</li>
                            <li>Quyền hạn chế hoặc phản đối việc xử lý thông tin của bạn</li>
                            <li>Quyền nhận bản sao thông tin cá nhân của bạn</li>
                        </ul>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">7. Lưu giữ thông tin</h2>
                        <p class="mb-4">
                            Chúng tôi lưu giữ thông tin cá nhân của bạn trong thời gian cần thiết để cung cấp dịch vụ và thực hiện các mục đích 
                            được nêu trong Chính sách Bảo mật này, trừ khi thời gian lưu giữ lâu hơn được yêu cầu hoặc cho phép theo luật hiện hành.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">8. Thay đổi đối với Chính sách Bảo mật</h2>
                        <p class="mb-4">
                            Chúng tôi có thể cập nhật Chính sách Bảo mật này theo thời gian để phản ánh những thay đổi trong thực tiễn 
                            bảo mật của chúng tôi. Chúng tôi sẽ thông báo cho bạn về bất kỳ thay đổi quan trọng nào bằng cách đăng phiên 
                            bản mới trên trang web của chúng tôi hoặc thông qua các phương tiện liên lạc khác.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">9. Liên hệ với chúng tôi</h2>
                        <p class="mb-4">
                            Nếu bạn có bất kỳ câu hỏi, mối quan ngại hoặc yêu cầu liên quan đến Chính sách Bảo mật này hoặc cách chúng tôi 
                            xử lý thông tin cá nhân của bạn, vui lòng liên hệ với chúng tôi theo:
                        </p>
                        <ul class="list-disc ml-6 mb-4">
                            <li>Email: privacy@giasuconnect.vn</li>
                            <li>Điện thoại: 028 1234 5678</li>
                            <li>Địa chỉ: 123 Đường ABC, Quận 1, TP. Hồ Chí Minh</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 