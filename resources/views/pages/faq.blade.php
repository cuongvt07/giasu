@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Câu Hỏi Thường Gặp (FAQ)</h1>
                    
                    <div class="prose prose-indigo max-w-none">
                        <p class="mb-8 text-lg text-gray-600">
                            Dưới đây là các câu hỏi thường gặp về dịch vụ của chúng tôi. Nếu bạn không tìm thấy câu trả lời cho câu hỏi của mình, 
                            vui lòng <a href="{{ route('contact') }}" class="text-indigo-600 hover:text-indigo-700">liên hệ với chúng tôi</a>.
                        </p>
                        
                        <div class="space-y-8">
                            <!-- Nhóm câu hỏi: Tổng quan -->
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Tổng quan về dịch vụ</h2>
                                
                                <div class="mt-6 space-y-6">
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nền tảng Kết nối Gia sư là gì?</h3>
                                        <div class="text-gray-600">
                                            <p>Nền tảng Kết nối Gia sư là một dịch vụ trực tuyến giúp kết nối học sinh/phụ huynh với các gia sư chất lượng cao. Chúng tôi cung cấp một nền tảng an toàn, tiện lợi và đáng tin cậy để bạn có thể tìm kiếm, đặt lịch và thanh toán các buổi học với gia sư phù hợp với nhu cầu học tập của bạn.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Làm thế nào để bắt đầu sử dụng dịch vụ?</h3>
                                        <div class="text-gray-600">
                                            <p>Để bắt đầu sử dụng dịch vụ, bạn chỉ cần đăng ký một tài khoản trên trang web của chúng tôi. Sau khi đăng ký, bạn có thể tìm kiếm gia sư theo môn học, cấp học hoặc các tiêu chí khác, xem hồ sơ chi tiết của gia sư, và đặt lịch học với gia sư mà bạn chọn.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Dịch vụ có mất phí không?</h3>
                                        <div class="text-gray-600">
                                            <p>Việc đăng ký tài khoản và tìm kiếm gia sư trên nền tảng của chúng tôi hoàn toàn miễn phí. Bạn chỉ thanh toán chi phí cho các buổi học theo mức học phí do gia sư niêm yết. Lưu ý rằng chúng tôi sẽ thu một khoản phí nhỏ từ phía gia sư để duy trì và phát triển nền tảng.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Nhóm câu hỏi: Tìm gia sư -->
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Tìm kiếm gia sư</h2>
                                
                                <div class="mt-6 space-y-6">
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Làm thế nào để tìm gia sư phù hợp?</h3>
                                        <div class="text-gray-600">
                                            <p>Bạn có thể sử dụng công cụ tìm kiếm của chúng tôi để lọc gia sư theo môn học, cấp học, giá cả, và các tiêu chí khác. Mỗi gia sư đều có hồ sơ chi tiết bao gồm kinh nghiệm, trình độ học vấn, và đánh giá từ học sinh trước đó để giúp bạn đưa ra quyết định.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Gia sư trên nền tảng có được xác minh không?</h3>
                                        <div class="text-gray-600">
                                            <p>Có, tất cả gia sư trên nền tảng của chúng tôi đều phải trải qua quá trình xác minh danh tính và trình độ học vấn. Chúng tôi kiểm tra các chứng chỉ, bằng cấp và thông tin cá nhân của gia sư để đảm bảo họ đủ điều kiện giảng dạy. Ngoài ra, hệ thống đánh giá của chúng tôi cho phép học sinh đánh giá gia sư sau mỗi buổi học, giúp duy trì chất lượng cao.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tôi có thể học online hay chỉ học trực tiếp?</h3>
                                        <div class="text-gray-600">
                                            <p>Nền tảng của chúng tôi hỗ trợ cả hai hình thức học: trực tuyến và trực tiếp. Bạn có thể lựa chọn hình thức phù hợp với nhu cầu của mình. Nhiều gia sư cung cấp cả hai lựa chọn, và bạn có thể lọc tìm kiếm theo hình thức dạy học mà bạn ưa thích.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Nhóm câu hỏi: Đặt lịch và thanh toán -->
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Đặt lịch và thanh toán</h2>
                                
                                <div class="mt-6 space-y-6">
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Làm thế nào để đặt lịch học với gia sư?</h3>
                                        <div class="text-gray-600">
                                            <p>Sau khi tìm được gia sư phù hợp, bạn có thể xem lịch trống của gia sư và chọn thời gian phù hợp để đặt lịch. Bạn cần điền thông tin về môn học, mục tiêu học tập và các yêu cầu cụ thể. Sau khi gửi yêu cầu, gia sư sẽ xác nhận và buổi học sẽ được lên lịch.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tôi có thể hủy hoặc đổi lịch học không?</h3>
                                        <div class="text-gray-600">
                                            <p>Có, bạn có thể hủy hoặc đổi lịch học thông qua nền tảng của chúng tôi. Tuy nhiên, để đảm bảo sự tôn trọng đối với thời gian của gia sư, chúng tôi yêu cầu bạn thông báo trước ít nhất 24 giờ. Việc hủy đột xuất có thể dẫn đến phí hủy tùy thuộc vào chính sách của gia sư.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Làm thế nào để thanh toán cho buổi học?</h3>
                                        <div class="text-gray-600">
                                            <p>Chúng tôi cung cấp nhiều phương thức thanh toán khác nhau, bao gồm thẻ tín dụng/ghi nợ, ví điện tử, và chuyển khoản ngân hàng. Thanh toán được thực hiện trực tiếp qua nền tảng của chúng tôi, đảm bảo an toàn và minh bạch. Bạn có thể thanh toán cho từng buổi học hoặc mua gói nhiều buổi học để được giảm giá.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Nhóm câu hỏi: Trở thành gia sư -->
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Trở thành gia sư</h2>
                                
                                <div class="mt-6 space-y-6">
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Làm thế nào để đăng ký trở thành gia sư?</h3>
                                        <div class="text-gray-600">
                                            <p>Để đăng ký trở thành gia sư, bạn cần tạo một tài khoản trên nền tảng của chúng tôi và chọn "Trở thành gia sư". Sau đó, bạn sẽ điền thông tin cá nhân, trình độ học vấn, kinh nghiệm giảng dạy, các môn học và cấp học mà bạn có thể dạy, và tải lên các chứng chỉ hoặc bằng cấp liên quan.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tôi có thể tự đặt giá dạy của mình không?</h3>
                                        <div class="text-gray-600">
                                            <p>Có, bạn có toàn quyền quyết định mức học phí của mình. Chúng tôi cung cấp thông tin về mức giá trung bình theo môn học để giúp bạn đặt giá hợp lý và cạnh tranh. Lưu ý rằng chúng tôi sẽ thu một khoản phí nhỏ từ mỗi buổi dạy thành công để duy trì nền tảng.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Khi nào tôi sẽ nhận được thanh toán?</h3>
                                        <div class="text-gray-600">
                                            <p>Sau khi buổi học hoàn thành và được xác nhận bởi học sinh, thanh toán sẽ được chuyển vào tài khoản của bạn trên nền tảng. Bạn có thể rút tiền từ tài khoản này vào tài khoản ngân hàng của mình theo lịch thanh toán được quy định trong điều khoản dịch vụ (thường là 5-7 ngày làm việc).</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Nhóm câu hỏi: Hỗ trợ và an toàn -->
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Hỗ trợ và an toàn</h2>
                                
                                <div class="mt-6 space-y-6">
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Làm thế nào để liên hệ với bộ phận hỗ trợ?</h3>
                                        <div class="text-gray-600">
                                            <p>Bạn có thể liên hệ với bộ phận hỗ trợ của chúng tôi qua nhiều kênh khác nhau: email hỗ trợ (support@giasuconnect.vn), chat trực tuyến trên trang web, hoặc gọi điện thoại đến số hotline (028 1234 5678) trong giờ làm việc. Đội ngũ hỗ trợ của chúng tôi sẵn sàng giải đáp mọi thắc mắc và hỗ trợ bạn.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Điều gì xảy ra nếu tôi không hài lòng với buổi học?</h3>
                                        <div class="text-gray-600">
                                            <p>Nếu bạn không hài lòng với buổi học, chúng tôi khuyến khích bạn thảo luận trực tiếp với gia sư để giải quyết vấn đề. Nếu vấn đề không được giải quyết, bạn có thể liên hệ với bộ phận hỗ trợ của chúng tôi. Trong một số trường hợp, chúng tôi có thể hoàn tiền hoặc cung cấp buổi học bổ sung tùy thuộc vào tình huống cụ thể.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="rounded-lg bg-gray-50 p-5">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Thông tin cá nhân của tôi có được bảo vệ không?</h3>
                                        <div class="text-gray-600">
                                            <p>Có, chúng tôi coi trọng quyền riêng tư và an toàn thông tin cá nhân của bạn. Chúng tôi áp dụng các biện pháp bảo mật tiên tiến và tuân thủ các quy định về bảo vệ dữ liệu. Thông tin chi tiết về cách chúng tôi thu thập, sử dụng, và bảo vệ dữ liệu của bạn có thể được tìm thấy trong <a href="{{ route('privacy-policy') }}" class="text-indigo-600 hover:text-indigo-700">Chính sách Bảo mật</a> của chúng tôi.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-12 p-6 bg-indigo-50 rounded-lg">
                            <h2 class="text-xl font-semibold text-center text-gray-900 mb-4">Bạn còn câu hỏi khác?</h2>
                            <p class="text-center mb-6">Nếu bạn không tìm thấy câu trả lời cho câu hỏi của mình, đừng ngần ngại liên hệ với chúng tôi.</p>
                            <div class="flex justify-center">
                                <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Liên hệ ngay
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 