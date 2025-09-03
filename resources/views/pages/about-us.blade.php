@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Về Chúng Tôi</h1>
                    
                    <div class="prose prose-indigo max-w-none">
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">Sứ mệnh của chúng tôi</h2>
                        <p class="mb-4">
                            Nền tảng Kết nối Gia sư được thành lập với sứ mệnh kết nối học sinh với những gia sư có trình độ 
                            cao nhất và phù hợp nhất, góp phần nâng cao chất lượng giáo dục tại Việt Nam. Chúng tôi tin rằng 
                            mỗi học sinh đều xứng đáng được tiếp cận với phương pháp giáo dục cá nhân hóa để phát triển 
                            tối đa tiềm năng của mình.
                        </p>
                        
                        <div class="my-8 flex justify-center">
                            <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" 
                                alt="Gia sư và học sinh" 
                                class="rounded-lg shadow-md max-w-full h-auto" 
                                style="max-height: 400px;">
                        </div>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">Câu chuyện của chúng tôi</h2>
                        <p class="mb-4">
                            Nền tảng của chúng tôi bắt đầu vào năm 2023 khi nhóm sáng lập - những người đã từng là gia sư và 
                            có nhiều năm kinh nghiệm trong lĩnh vực giáo dục - nhận ra rằng có một khoảng cách lớn giữa 
                            học sinh cần tìm gia sư và những giáo viên tài năng đang tìm kiếm cơ hội giảng dạy.
                        </p>
                        <p class="mb-4">
                            Với tầm nhìn xây dựng một nền tảng hiện đại, minh bạch và hiệu quả, chúng tôi đã phát triển 
                            hệ thống kết nối gia sư trực tuyến này. Hệ thống của chúng tôi không chỉ đơn thuần kết nối 
                            học sinh với gia sư, mà còn đảm bảo sự phù hợp tối ưu thông qua thuật toán thông minh và 
                            hệ thống đánh giá chặt chẽ.
                        </p>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">Giá trị cốt lõi</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 my-8">
                            <div class="bg-indigo-50 p-6 rounded-lg">
                                <h3 class="font-bold text-lg mb-2 text-indigo-700">Chất lượng</h3>
                                <p>Chúng tôi cam kết mang đến dịch vụ giáo dục chất lượng cao với đội ngũ gia sư được tuyển chọn kỹ lưỡng và đánh giá thường xuyên.</p>
                            </div>
                            <div class="bg-indigo-50 p-6 rounded-lg">
                                <h3 class="font-bold text-lg mb-2 text-indigo-700">Sự tiện lợi</h3>
                                <p>Nền tảng của chúng tôi giúp việc tìm kiếm, đặt lịch và thanh toán cho gia sư trở nên đơn giản và thuận tiện.</p>
                            </div>
                            <div class="bg-indigo-50 p-6 rounded-lg">
                                <h3 class="font-bold text-lg mb-2 text-indigo-700">Sự tin cậy</h3>
                                <p>Mọi gia sư đều được xác minh danh tính và trình độ chuyên môn, đảm bảo an toàn và đáng tin cậy cho học sinh.</p>
                            </div>
                        </div>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">Đội ngũ của chúng tôi</h2>
                        <p class="mb-4">
                            Đội ngũ của chúng tôi bao gồm những chuyên gia đam mê về giáo dục, công nghệ và dịch vụ khách hàng. 
                            Mỗi thành viên đều mang đến những kinh nghiệm và góc nhìn độc đáo, cùng nhau xây dựng một nền tảng 
                            giáo dục hiện đại và hiệu quả.
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 my-8">
                            <div class="flex flex-col items-center">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="CEO" class="w-32 h-32 rounded-full mb-4">
                                <h3 class="font-bold text-lg">Nguyễn Văn A</h3>
                                <p class="text-gray-600">Đồng sáng lập & CEO</p>
                            </div>
                            <div class="flex flex-col items-center">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="COO" class="w-32 h-32 rounded-full mb-4">
                                <h3 class="font-bold text-lg">Trần Thị B</h3>
                                <p class="text-gray-600">Đồng sáng lập & COO</p>
                            </div>
                        </div>
                        
                        <h2 class="text-xl font-semibold text-gray-900 mt-8 mb-4">Đối tác của chúng tôi</h2>
                        <p class="mb-8">
                            Chúng tôi tự hào hợp tác với các trường học, tổ chức giáo dục và doanh nghiệp uy tín để mang đến 
                            những dịch vụ giáo dục tốt nhất. Sự hợp tác này giúp chúng tôi không ngừng cải thiện và mở rộng 
                            danh sách gia sư chất lượng cao.
                        </p>
                        
                        <div class="bg-gray-50 p-6 rounded-lg my-8">
                            <h2 class="text-xl font-semibold text-center text-gray-900 mb-6">Liên hệ với chúng tôi</h2>
                            <p class="text-center mb-4">
                                Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Nếu bạn có bất kỳ câu hỏi, đề xuất hoặc phản hồi nào, 
                                vui lòng liên hệ với chúng tôi qua:
                            </p>
                            <div class="flex justify-center">
                                <a href="{{ route('contact') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
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