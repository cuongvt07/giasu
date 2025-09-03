@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Điều Khoản Sử Dụng</h1>
                    
                    <div class="prose prose-indigo max-w-none">
                        <p class="text-gray-600 mb-4">Cập nhật lần cuối: {{ now()->format('d/m/Y') }}</p>
                        
                        <div class="mb-8">
                            <p class="mb-4">
                                Chào mừng bạn đến với Nền tảng Kết nối Gia sư. Vui lòng đọc kỹ các Điều khoản sử dụng sau đây trước khi sử dụng 
                                dịch vụ của chúng tôi. Bằng cách truy cập hoặc sử dụng dịch vụ của chúng tôi, bạn đồng ý tuân thủ và bị ràng buộc 
                                bởi các điều khoản và điều kiện này. Nếu bạn không đồng ý với bất kỳ phần nào của các điều khoản này, vui lòng 
                                không sử dụng dịch vụ của chúng tôi.
                            </p>
                        </div>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">1. Giới thiệu</h2>
                        <p class="mb-4">
                            Nền tảng Kết nối Gia sư (sau đây gọi là "Nền tảng") là dịch vụ trực tuyến kết nối học sinh, phụ huynh với gia sư 
                            cung cấp các dịch vụ giảng dạy. Nền tảng do Công ty TNHH Giáo Dục và Công Nghệ XYZ ("Công ty", "chúng tôi", "của chúng tôi") 
                            sở hữu và vận hành. Các điều khoản này điều chỉnh việc sử dụng dịch vụ của chúng tôi và xác định các quyền và nghĩa vụ 
                            của bạn khi sử dụng Nền tảng.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">2. Đăng ký tài khoản</h2>
                        <p class="mb-4">
                            Để sử dụng đầy đủ các tính năng của Nền tảng, bạn cần đăng ký tài khoản. Khi đăng ký, bạn đồng ý cung cấp thông tin 
                            chính xác, đầy đủ và cập nhật. Bạn chịu trách nhiệm bảo mật thông tin tài khoản của mình, bao gồm mật khẩu, và chịu 
                            trách nhiệm cho tất cả hoạt động diễn ra dưới tài khoản của bạn.
                        </p>
                        <p class="mb-4">
                            Chúng tôi có quyền từ chối dịch vụ, chấm dứt tài khoản, xóa hoặc chỉnh sửa nội dung, hoặc hủy đơn đặt hàng 
                            theo quyết định riêng của chúng tôi và không cần thông báo trước.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">3. Gia sư</h2>
                        <p class="mb-4">
                            <strong>3.1. Yêu cầu để trở thành gia sư</strong><br>
                            Để đăng ký làm gia sư trên Nền tảng, bạn phải:
                        </p>
                        <ul class="list-disc ml-6 mb-4">
                            <li>Có đủ năng lực hành vi dân sự theo quy định của pháp luật Việt Nam</li>
                            <li>Cung cấp thông tin cá nhân và giấy tờ tùy thân hợp lệ</li>
                            <li>Cung cấp bằng cấp, chứng chỉ và các tài liệu chứng minh trình độ chuyên môn</li>
                            <li>Tuân thủ quy trình xác minh của Nền tảng</li>
                            <li>Đồng ý với và tuân thủ các chính sách dành cho gia sư của chúng tôi</li>
                        </ul>
                        <p class="mb-4">
                            <strong>3.2. Trách nhiệm của gia sư</strong><br>
                            Khi trở thành gia sư trên Nền tảng, bạn đồng ý:
                        </p>
                        <ul class="list-disc ml-6 mb-4">
                            <li>Cung cấp dịch vụ giảng dạy chất lượng, chuyên nghiệp và đúng giờ</li>
                            <li>Cung cấp thông tin chính xác và trung thực về trình độ, kinh nghiệm và khả năng giảng dạy</li>
                            <li>Duy trì liên lạc kịp thời với học sinh và Nền tảng</li>
                            <li>Tuân thủ các quy tắc ứng xử của Nền tảng</li>
                            <li>Thanh toán phí dịch vụ cho Nền tảng theo tỷ lệ đã thỏa thuận</li>
                        </ul>
                        <p class="mb-4">
                            <strong>3.3. Phí dịch vụ</strong><br>
                            Chúng tôi thu phí dịch vụ từ mỗi buổi học thành công thông qua Nền tảng. Phí dịch vụ là [X]% giá trị của mỗi buổi học. 
                            Phí này sẽ được khấu trừ tự động từ số tiền thanh toán cho gia sư. Chi tiết về phí dịch vụ có thể thay đổi và sẽ được cập nhật trong mục Phí và Thanh toán.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">4. Học sinh và phụ huynh</h2>
                        <p class="mb-4">
                            <strong>4.1. Quyền và trách nhiệm</strong><br>
                            Khi sử dụng Nền tảng để tìm và đặt lịch với gia sư, bạn đồng ý:
                        </p>
                        <ul class="list-disc ml-6 mb-4">
                            <li>Cung cấp thông tin chính xác về nhu cầu học tập và mong đợi</li>
                            <li>Tôn trọng thời gian và công sức của gia sư</li>
                            <li>Thanh toán đầy đủ và đúng hạn cho các dịch vụ bạn đã sử dụng</li>
                            <li>Thông báo trước ít nhất 24 giờ nếu cần hủy hoặc đổi lịch buổi học</li>
                            <li>Cung cấp đánh giá trung thực về gia sư sau mỗi buổi học</li>
                        </ul>
                        <p class="mb-4">
                            <strong>4.2. Chính sách hủy và hoàn tiền</strong><br>
                            Chúng tôi hiểu rằng kế hoạch có thể thay đổi. Chính sách hủy và hoàn tiền của chúng tôi như sau:
                        </p>
                        <ul class="list-disc ml-6 mb-4">
                            <li>Hủy trước 24 giờ: hoàn tiền 100%</li>
                            <li>Hủy trong vòng 12-24 giờ: hoàn tiền 50%</li>
                            <li>Hủy trong vòng dưới 12 giờ: không hoàn tiền</li>
                            <li>Trường hợp gia sư hủy buổi học: hoàn tiền 100% hoặc đặt lịch lại mà không mất phí</li>
                        </ul>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">5. Thanh toán</h2>
                        <p class="mb-4">
                            <strong>5.1. Phương thức thanh toán</strong><br>
                            Chúng tôi chấp nhận nhiều phương thức thanh toán khác nhau, bao gồm:
                        </p>
                        <ul class="list-disc ml-6 mb-4">
                            <li>Thẻ tín dụng/ghi nợ</li>
                            <li>Ví điện tử (MoMo, Zalopay, VNPay)</li>
                            <li>Chuyển khoản ngân hàng</li>
                        </ul>
                        <p class="mb-4">
                            <strong>5.2. Quy trình thanh toán</strong><br>
                            Học sinh thanh toán trước cho buổi học thông qua Nền tảng. Tiền sẽ được giữ lại cho đến khi buổi học hoàn thành 
                            và được xác nhận bởi cả hai bên. Sau đó, tiền sẽ được chuyển cho gia sư (đã trừ phí dịch vụ) theo lịch thanh toán định kỳ.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">6. Quyền sở hữu trí tuệ</h2>
                        <p class="mb-4">
                            Nền tảng và tất cả nội dung, tính năng và chức năng của nó thuộc sở hữu của Công ty và được bảo vệ bởi luật sở hữu trí 
                            tuệ Việt Nam và quốc tế. Bạn không được sao chép, sửa đổi, phân phối, bán, cho thuê hoặc khai thác trái phép bất kỳ 
                            phần nào của Nền tảng.
                        </p>
                        <p class="mb-4">
                            Bất kỳ tài liệu giảng dạy nào do gia sư cung cấp vẫn thuộc quyền sở hữu trí tuệ của gia sư đó hoặc bên thứ ba sở hữu chúng. 
                            Học sinh chỉ được sử dụng các tài liệu này cho mục đích học tập cá nhân.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">7. Bảo mật thông tin</h2>
                        <p class="mb-4">
                            Chúng tôi coi trọng quyền riêng tư của bạn. Việc thu thập và sử dụng thông tin cá nhân của bạn được quy định trong 
                            <a href="{{ route('privacy-policy') }}" class="text-indigo-600 hover:text-indigo-700">Chính sách Bảo mật</a> của chúng tôi. 
                            Bằng cách sử dụng Nền tảng, bạn đồng ý với việc thu thập và sử dụng thông tin như được mô tả trong Chính sách Bảo mật.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">8. Giới hạn trách nhiệm</h2>
                        <p class="mb-4">
                            Mặc dù chúng tôi nỗ lực cung cấp dịch vụ tốt nhất, nhưng chúng tôi không đảm bảo rằng Nền tảng sẽ không có lỗi, 
                            an toàn hoàn toàn, hoặc luôn có sẵn. Chúng tôi không chịu trách nhiệm về bất kỳ thiệt hại nào phát sinh từ việc sử dụng 
                            hoặc không thể sử dụng Nền tảng.
                        </p>
                        <p class="mb-4">
                            Chúng tôi hoạt động như một nền tảng kết nối và không chịu trách nhiệm về chất lượng dịch vụ giảng dạy của gia sư hoặc 
                            hành vi của học sinh. Tuy nhiên, chúng tôi sẽ hỗ trợ giải quyết tranh chấp và thực hiện các biện pháp hợp lý để duy trì 
                            chất lượng dịch vụ.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">9. Chấm dứt</h2>
                        <p class="mb-4">
                            Chúng tôi có quyền chấm dứt hoặc đình chỉ quyền truy cập của bạn vào Nền tảng ngay lập tức, không cần thông báo trước, 
                            nếu bạn vi phạm bất kỳ điều khoản nào trong Điều khoản sử dụng này.
                        </p>
                        <p class="mb-4">
                            Bạn có thể chấm dứt tài khoản của mình bất kỳ lúc nào bằng cách thông báo cho chúng tôi. Sau khi chấm dứt, 
                            bạn sẽ mất quyền truy cập vào tài khoản của mình và tất cả dữ liệu liên quan.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">10. Thay đổi Điều khoản</h2>
                        <p class="mb-4">
                            Chúng tôi có thể sửa đổi Điều khoản sử dụng này vào bất kỳ lúc nào. Khi thay đổi, chúng tôi sẽ cập nhật ngày "Cập nhật lần cuối" 
                            ở đầu trang này và đăng thông báo trên Nền tảng. Việc bạn tiếp tục sử dụng Nền tảng sau khi thay đổi có hiệu lực đồng nghĩa 
                            với việc bạn chấp nhận các điều khoản mới.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">11. Luật áp dụng</h2>
                        <p class="mb-4">
                            Các Điều khoản sử dụng này được điều chỉnh và hiểu theo luật pháp Việt Nam. Bất kỳ tranh chấp nào liên quan đến 
                            các Điều khoản này hoặc việc sử dụng Nền tảng sẽ được giải quyết tại các tòa án có thẩm quyền tại Việt Nam.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">12. Liên hệ</h2>
                        <p class="mb-4">
                            Nếu bạn có bất kỳ câu hỏi nào về Điều khoản sử dụng này, vui lòng liên hệ với chúng tôi qua:
                        </p>
                        <ul class="list-disc ml-6 mb-4">
                            <li>Email: terms@giasuconnect.vn</li>
                            <li>Điện thoại: 028 1234 5678</li>
                            <li>Địa chỉ: 123 Đường ABC, Quận 1, TP. Hồ Chí Minh</li>
                        </ul>
                        
                        <div class="mt-12 p-6 bg-gray-50 rounded-lg text-center">
                            <p class="mb-4 font-semibold">Bằng cách sử dụng dịch vụ của chúng tôi, bạn xác nhận rằng bạn đã đọc, hiểu và đồng ý với các Điều khoản sử dụng này.</p>
                            <p>Nếu bạn không đồng ý với bất kỳ điều khoản nào, vui lòng không sử dụng Nền tảng của chúng tôi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 