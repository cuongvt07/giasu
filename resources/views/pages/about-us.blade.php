@extends('layouts.app')

@section('content')

<style>
    /* Animation mượt cho các section */
    .fade-up {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeUp 0.8s ease-out forwards;
    }

    @keyframes fadeUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-delay-1 { animation-delay: .2s; }
    .fade-delay-2 { animation-delay: .4s; }
    .fade-delay-3 { animation-delay: .6s; }
    .fade-delay-4 { animation-delay: .8s; }
</style>

{{-- ===========================
      HERO SECTION
=========================== --}}
<div class="relative bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-24 px-6">
    <div class="max-w-6xl mx-auto text-center fade-up">
        <h1 class="text-4xl md:text-6xl font-extrabold mb-6">
            Kết Nối Gia Sư – Vì Tương Lai Tốt Đẹp Hơn
        </h1>
        <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto">
            Chúng tôi mang đến cầu nối giữa học sinh và gia sư chuyên nghiệp, giúp việc học trở nên dễ dàng hơn bao giờ hết.
        </p>

        <a href="{{ route('contact') }}"
            class="inline-block mt-8 px-8 py-3 bg-white text-indigo-600 rounded-lg font-semibold shadow-md hover:shadow-lg transition">
            Liên hệ ngay
        </a>
    </div>

    <div class="absolute inset-0 bg-[url('https://www.toptal.com/designers/subtlepatterns/uploads/moroccan-flower.png')] opacity-20"></div>
</div>


{{-- ===========================
      SỨ MỆNH
=========================== --}}
<div class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6">
        
        <div class="fade-up">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Sứ mệnh của chúng tôi</h2>
            <p class="text-gray-700 leading-relaxed max-w-3xl mb-4">
                Mang đến nền tảng giáo dục hiện đại, kết nối học sinh và gia sư một cách tối ưu, minh bạch và hiệu quả.
                Chúng tôi tin rằng mỗi học sinh xứng đáng có một người thầy phù hợp để phát triển tối đa năng lực.
            </p>
            <p class="text-gray-700 leading-relaxed max-w-3xl mb-4">
                Với công nghệ tiên tiến và đội ngũ tận tâm, chúng tôi cam kết xây dựng một cộng đồng giáo dục vững mạnh, 
                nơi kiến thức được truyền tải một cách hiệu quả nhất. Mỗi buổi học không chỉ là việc tiếp thu kiến thức, 
                mà còn là hành trình khám phá và phát triển bản thân.
            </p>
            <p class="text-gray-700 leading-relaxed max-w-3xl">
                Chúng tôi không ngừng cải tiến để đảm bảo trải nghiệm tốt nhất cho cả học sinh và gia sư, 
                tạo ra môi trường học tập năng động, sáng tạo và đầy cảm hứng.
            </p>
        </div>

        <div class="mt-10 flex justify-center fade-up fade-delay-1">
            <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1000&q=80"
                class="rounded-xl shadow-lg hover:scale-[1.02] transition duration-300">
        </div>

    </div>
</div>


{{-- ===========================
      TẦM NHÌN
=========================== --}}
<div class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        
        <div class="grid md:grid-cols-2 gap-12 items-center">
            
            <div class="fade-up">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1000&q=80"
                     class="rounded-xl shadow-lg hover:scale-[1.02] transition">
            </div>

            <div class="fade-up fade-delay-1">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Tầm nhìn của chúng tôi</h2>
                <p class="text-gray-700 mb-4 leading-relaxed">
                    Trở thành nền tảng kết nối gia sư hàng đầu Việt Nam, góp phần nâng cao chất lượng giáo dục 
                    và tạo cơ hội học tập bình đẳng cho mọi người.
                </p>
                <p class="text-gray-700 mb-4 leading-relaxed">
                    Chúng tôi hướng đến việc xây dựng một hệ sinh thái giáo dục hoàn chỉnh, nơi công nghệ 
                    và sự tận tâm của con người kết hợp để tạo ra những trải nghiệm học tập xuất sắc.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Trong tương lai, chúng tôi mong muốn mở rộng ra các quốc gia trong khu vực, 
                    mang đến giải pháp giáo dục tiên tiến cho hàng triệu học sinh trên toàn thế giới.
                </p>
            </div>

        </div>

    </div>
</div>


{{-- ===========================
      GIÁ TRỊ CỐT LÕI — ICON BOX
=========================== --}}
<div class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6 text-center">

        <h2 class="text-3xl font-bold text-gray-900 fade-up">Giá trị cốt lõi</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">

            <div class="bg-indigo-50 p-8 rounded-xl shadow-sm hover:shadow-md transition fade-up fade-delay-1">
                <div class="text-4xl text-indigo-600 mb-4">🎓</div>
                <h3 class="font-bold text-xl mb-2">Chất lượng</h3>
                <p class="text-gray-700">Gia sư được tuyển chọn nghiêm ngặt và đánh giá thường xuyên.</p>
            </div>

            <div class="bg-indigo-50 p-8 rounded-xl shadow-sm hover:shadow-md transition fade-up fade-delay-2">
                <div class="text-4xl text-indigo-600 mb-4">⚡</div>
                <h3 class="font-bold text-xl mb-2">Tiện lợi</h3>
                <p class="text-gray-700">Tìm kiếm, liên hệ và thanh toán nhanh chóng, dễ dàng.</p>
            </div>

            <div class="bg-indigo-50 p-8 rounded-xl shadow-sm hover:shadow-md transition fade-up fade-delay-3">
                <div class="text-4xl text-indigo-600 mb-4">🔒</div>
                <h3 class="font-bold text-xl mb-2">Tin cậy</h3>
                <p class="text-gray-700">Xác minh danh tính & bằng cấp giúp người học yên tâm.</p>
            </div>

        </div>

    </div>
</div>


{{-- ===========================
      CÂU CHUYỆN
=========================== --}}
<div class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">

        <div class="grid md:grid-cols-2 gap-12 items-center">
            
            <div class="fade-up">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Câu chuyện của chúng tôi</h2>
                <p class="text-gray-700 mb-4 leading-relaxed">
                    Nền tảng ra đời năm 2023 nhằm giải quyết nhu cầu ngày càng lớn về tìm kiếm gia sư uy tín.
                    Xuất phát từ những trải nghiệm thực tế của chính đội ngũ sáng lập, chúng tôi hiểu rõ những 
                    khó khăn mà học sinh và phụ huynh gặp phải khi tìm kiếm gia sư phù hợp.
                </p>
                <p class="text-gray-700 mb-4 leading-relaxed">
                    Chúng tôi phát triển một hệ thống thông minh giúp tìm gia sư phù hợp nhất, tiết kiệm thời gian
                    và đảm bảo hiệu quả học tập tối ưu. Công nghệ AI của chúng tôi phân tích nhu cầu học tập, 
                    phong cách học và mục tiêu của từng học sinh để gợi ý gia sư phù hợp nhất.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Sau hơn một năm hoạt động, chúng tôi tự hào đã kết nối hàng nghìn học sinh với gia sư xuất sắc, 
                    góp phần vào thành công học tập và phát triển của các em. Mỗi câu chuyện thành công là động lực 
                    để chúng tôi tiếp tục cải tiến và phát triển.
                </p>
            </div>

            <div class="fade-up fade-delay-2">
                <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1000&q=80"
                     class="rounded-xl shadow-lg hover:scale-[1.02] transition">
            </div>

        </div>

    </div>
</div>


{{-- ===========================
      CAM KẾT CỦA CHÚNG TÔI
=========================== --}}
<div class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6">
        
        <div class="text-center fade-up mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Cam kết của chúng tôi</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Chúng tôi không chỉ là nền tảng kết nối, mà còn là đối tác đồng hành trong hành trình học tập của bạn
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            
            <div class="bg-white p-8 rounded-xl shadow-sm fade-up fade-delay-1">
                <h3 class="text-xl font-bold text-gray-900 mb-3">🎯 Đảm bảo chất lượng</h3>
                <p class="text-gray-700 leading-relaxed mb-3">
                    Mỗi gia sư trên nền tảng đều trải qua quy trình tuyển chọn và kiểm định nghiêm ngặt. 
                    Chúng tôi xác minh bằng cấp, kinh nghiệm giảng dạy và đánh giá kỹ năng sư phạm trước khi 
                    cho phép tham gia hệ thống.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Hệ thống đánh giá và phản hồi liên tục giúp chúng tôi duy trì và nâng cao chất lượng dịch vụ, 
                    đảm bảo mỗi buổi học đều mang lại giá trị thực sự cho học sinh.
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm fade-up fade-delay-2">
                <h3 class="text-xl font-bold text-gray-900 mb-3">🛡️ Bảo mật thông tin</h3>
                <p class="text-gray-700 leading-relaxed mb-3">
                    An toàn và bảo mật thông tin cá nhân là ưu tiên hàng đầu của chúng tôi. Chúng tôi áp dụng 
                    các tiêu chuẩn bảo mật cao nhất để bảo vệ dữ liệu của bạn khỏi các truy cập trái phép.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Mọi giao dịch thanh toán đều được mã hóa và xử lý qua các cổng thanh toán uy tín, 
                    đảm bảo an toàn tuyệt đối cho thông tin tài chính của bạn.
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm fade-up fade-delay-3">
                <h3 class="text-xl font-bold text-gray-900 mb-3">💬 Hỗ trợ 24/7</h3>
                <p class="text-gray-700 leading-relaxed mb-3">
                    Đội ngũ chăm sóc khách hàng của chúng tôi luôn sẵn sàng hỗ trợ bạn mọi lúc, mọi nơi. 
                    Dù là câu hỏi về việc tìm gia sư, thanh toán hay bất kỳ vấn đề gì khác, chúng tôi đều 
                    sẵn lòng giúp đỡ.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Thời gian phản hồi trung bình của chúng tôi dưới 2 giờ, đảm bảo mọi thắc mắc của bạn 
                    được giải quyết nhanh chóng và hiệu quả.
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm fade-up fade-delay-4">
                <h3 class="text-xl font-bold text-gray-900 mb-3">📈 Theo dõi tiến độ</h3>
                <p class="text-gray-700 leading-relaxed mb-3">
                    Chúng tôi cung cấp công cụ theo dõi tiến độ học tập chi tiết, giúp học sinh và phụ huynh 
                    nắm rõ sự phát triển qua từng buổi học. Báo cáo định kỳ từ gia sư giúp điều chỉnh 
                    phương pháp học tập kịp thời.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Hệ thống phân tích thông minh của chúng tôi đưa ra các gợi ý cải thiện, giúp học sinh 
                    đạt mục tiêu học tập một cách hiệu quả nhất.
                </p>
            </div>

        </div>

    </div>
</div>


{{-- ===========================
      PHƯƠNG PHÁP HOẠT ĐỘNG
=========================== --}}
<div class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        
        <div class="text-center fade-up mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Phương pháp hoạt động</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Quy trình đơn giản, minh bạch giúp bạn dễ dàng tìm được gia sư phù hợp chỉ trong vài bước
            </p>
        </div>

        <div class="space-y-12">
            
            <div class="grid md:grid-cols-2 gap-8 items-center fade-up fade-delay-1">
                <div>
                    <div class="inline-block bg-indigo-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl mb-4">1</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Đăng ký tài khoản</h3>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        Tạo tài khoản miễn phí chỉ trong vài phút. Cung cấp thông tin cơ bản về nhu cầu học tập, 
                        môn học quan tâm và mục tiêu bạn muốn đạt được. Chúng tôi sử dụng thông tin này để 
                        gợi ý gia sư phù hợp nhất với bạn.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        Tài khoản của bạn được bảo mật tuyệt đối và bạn có thể cập nhật thông tin bất cứ lúc nào.
                    </p>
                </div>
                <div class="order-first md:order-last">
                    <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=800&q=80"
                         class="rounded-xl shadow-lg">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 items-center fade-up fade-delay-2">
                <div class="order-last md:order-first">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80"
                         class="rounded-xl shadow-lg">
                </div>
                <div>
                    <div class="inline-block bg-indigo-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl mb-4">2</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Tìm kiếm gia sư</h3>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        Sử dụng bộ lọc thông minh để tìm gia sư theo môn học, trình độ, khu vực và mức học phí. 
                        Xem hồ sơ chi tiết của gia sư bao gồm bằng cấp, kinh nghiệm, phương pháp giảng dạy và 
                        đánh giá từ học sinh trước đó.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        Hệ thống AI của chúng tôi cũng gợi ý các gia sư phù hợp nhất dựa trên profile và 
                        nhu cầu của bạn, giúp bạn tiết kiệm thời gian tìm kiếm.
                    </p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 items-center fade-up fade-delay-3">
                <div>
                    <div class="inline-block bg-indigo-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl mb-4">3</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Liên hệ và đặt lịch</h3>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        Gửi tin nhắn trực tiếp cho gia sư để trao đổi về phương pháp học, lịch học và mức học phí. 
                        Chúng tôi khuyến khích bạn đặt một buổi học thử miễn phí để đánh giá mức độ phù hợp 
                        trước khi cam kết dài hạn.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        Sau khi thống nhất, bạn có thể đặt lịch học thường xuyên thông qua hệ thống. 
                        Lịch học được đồng bộ và nhắc nhở tự động giúp bạn không bỏ lỡ buổi học nào.
                    </p>
                </div>
                <div class="order-first md:order-last">
                    <img src="https://images.unsplash.com/photo-1551836022-deb4988cc6c0?auto=format&fit=crop&w=800&q=80"
                         class="rounded-xl shadow-lg">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 items-center fade-up fade-delay-4">
                <div class="order-last md:order-first">
                    <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=800&q=80"
                         class="rounded-xl shadow-lg">
                </div>
                <div>
                    <div class="inline-block bg-indigo-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl mb-4">4</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Bắt đầu học tập</h3>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        Tham gia buổi học trực tuyến hoặc tại nhà tùy theo thỏa thuận. Sau mỗi buổi học, 
                        bạn có thể đánh giá và để lại phản hồi về gia sư. Điều này giúp cải thiện chất lượng 
                        dịch vụ và hỗ trợ các học sinh khác trong việc lựa chọn.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        Chúng tôi theo dõi tiến độ học tập của bạn và luôn sẵn sàng hỗ trợ nếu bạn muốn 
                        thay đổi gia sư hoặc điều chỉnh kế hoạch học tập.
                    </p>
                </div>
            </div>

        </div>

    </div>
</div>


{{-- ===========================
      ĐỘI NGŨ
=========================== --}}
<div class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6 text-center">

        <h2 class="text-3xl font-bold text-gray-900 fade-up mb-4">Đội ngũ của chúng tôi</h2>
        <p class="text-gray-600 max-w-2xl mx-auto mb-12 fade-up">
            Những con người đam mê giáo dục, tận tâm với sứ mệnh kết nối tri thức
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

            <div class="fade-up fade-delay-1">
                <img src="https://randomuser.me/api/portraits/men/32.jpg"
                     class="w-36 h-36 rounded-full mx-auto shadow" />
                <h3 class="mt-4 text-xl font-bold">Nguyễn Văn Tùng</h3>
                <p class="text-gray-600 mb-3">Đồng sáng lập & CEO</p>
                <p class="text-gray-700 text-sm leading-relaxed max-w-md mx-auto">
                    Với hơn 10 năm kinh nghiệm trong lĩnh vực công nghệ giáo dục, anh Tùng mang đến tầm nhìn 
                    chiến lược và sự đổi mới không ngừng cho nền tảng. Anh tin rằng công nghệ có thể 
                    biến đổi hoàn toàn cách chúng ta học và dạy.
                </p>
            </div>
            <div class="fade-up fade-delay-2">
                <img src="https://randomuser.me/api/portraits/women/44.jpg"
                     class="w-36 h-36 rounded-full mx-auto shadow" />
                <h3 class="mt-4 text-xl font-bold">Minh Ánh</h3>
                <p class="text-gray-600 mb-3">Đồng sáng lập & COO</p>
                <p class="text-gray-700 text-sm leading-relaxed max-w-md mx-auto">
                    Chị Minh Ánh có kinh nghiệm sâu rộng trong vận hành và phát triển cộng đồng giáo dục. 
                    Với sự tận tâm và nhiệt huyết, chị đảm bảo mọi hoạt động của nền tảng đều 
                    hướng đến lợi ích tốt nhất cho học sinh và gia sư.
                </p>
            </div>

        </div>

        <div class="mt-16 bg-white p-8 rounded-xl shadow-sm fade-up">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Tham gia cùng chúng tôi</h3>
            <p class="text-gray-700 leading-relaxed max-w-3xl mx-auto mb-6">
                Chúng tôi luôn tìm kiếm những người tài năng, đam mê giáo dục để cùng nhau xây dựng 
                một nền tảng giáo dục tốt hơn. Nếu bạn muốn tạo ra sự khác biệt trong lĩnh vực giáo dục, 
                hãy liên hệ với chúng tôi để khám phá các cơ hội nghề nghiệp.
            </p>
            <a href="mailto:careers@example.com" 
               class="inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                Xem vị trí tuyển dụng
            </a>
        </div>

    </div>
</div>


{{-- ===========================
      SỐ LIỆU THÀNH TỰU
=========================== --}}
<div class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        
        <div class="text-center fade-up mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Thành tựu của chúng tôi</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Những con số ấn tượng phản ánh sự tin tưởng và thành công mà chúng tôi đạt được
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            
            <div class="text-center fade-up fade-delay-1">
                <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">5,000+</div>
                <p class="text-gray-700 font-medium">Học sinh</p>
                <p class="text-gray-600 text-sm mt-1">Đã tin tưởng sử dụng</p>
            </div>

            <div class="text-center fade-up fade-delay-2">
                <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">1,200+</div>
                <p class="text-gray-700 font-medium">Gia sư</p>
                <p class="text-gray-600 text-sm mt-1">Chất lượng cao</p>
            </div>

            <div class="text-center fade-up fade-delay-3">
                <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">98%</div>
                <p class="text-gray-700 font-medium">Hài lòng</p>
                <p class="text-gray-600 text-sm mt-1">Đánh giá tích cực</p>
            </div>

            <div class="text-center fade-up fade-delay-4">
                <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">15,000+</div>
                <p class="text-gray-700 font-medium">Buổi học</p>
                <p class="text-gray-600 text-sm mt-1">Đã hoàn thành</p>
            </div>

        </div>

        <div class="mt-16 bg-gradient-to-r from-indigo-50 to-blue-50 p-8 rounded-xl fade-up">
            <div class="max-w-3xl mx-auto text-center">
                <p class="text-gray-700 leading-relaxed mb-4">
                    Sau hơn một năm hoạt động, chúng tôi tự hào đã kết nối hàng nghìn học sinh với 
                    những gia sư xuất sắc trên khắp cả nước. Mỗi ngày, chúng tôi chứng kiến sự tiến bộ 
                    vượt bậc của học sinh, từ việc cải thiện điểm số đến phát triển tư duy và kỹ năng học tập.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Những con số này không chỉ là thống kê, mà là minh chứng cho sự tận tâm và chất lượng 
                    mà chúng tôi mang đến. Chúng tôi cam kết sẽ tiếp tục nỗ lực để phục vụ cộng đồng học sinh 
                    và gia sư ngày càng tốt hơn.
                </p>
            </div>
        </div>

    </div>
</div>


{{-- ===========================
      PHẢN HỒI TỪ HỌC SINH
=========================== --}}
<div class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6">
        
        <div class="text-center fade-up mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Học sinh nói gì về chúng tôi</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Những chia sẻ chân thành từ học sinh và phụ huynh về trải nghiệm sử dụng dịch vụ
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            
            <div class="bg-white p-6 rounded-xl shadow-sm fade-up fade-delay-1">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/women/65.jpg" 
                         class="w-12 h-12 rounded-full mr-3">
                    <div>
                        <h4 class="font-bold text-gray-900">Nguyễn Thị Lan</h4>
                        <p class="text-sm text-gray-600">Học sinh lớp 12</p>
                    </div>
                </div>
                <div class="text-yellow-400 mb-2">★★★★★</div>
                <p class="text-gray-700 leading-relaxed">
                    "Tôi đã tìm được gia sư Toán hoàn hảo chỉ trong vài ngày. Cô giáo rất tận tâm và 
                    phương pháp giảng dạy dễ hiểu. Điểm số của tôi đã cải thiện đáng kể sau 2 tháng học."
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm fade-up fade-delay-2">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/men/75.jpg" 
                         class="w-12 h-12 rounded-full mr-3">
                    <div>
                        <h4 class="font-bold text-gray-900">Trần Văn Minh</h4>
                        <p class="text-sm text-gray-600">Phụ huynh</p>
                    </div>
                </div>
                <div class="text-yellow-400 mb-2">★★★★★</div>
                <p class="text-gray-700 leading-relaxed">
                    "Nền tảng rất chuyên nghiệp và dễ sử dụng. Tôi yên tâm vì mọi gia sư đều được xác minh 
                    kỹ lưỡng. Con tôi rất thích học với thầy và tiến bộ rõ rệt sau mỗi buổi học."
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm fade-up fade-delay-3">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/women/85.jpg" 
                         class="w-12 h-12 rounded-full mr-3">
                    <div>
                        <h4 class="font-bold text-gray-900">Lê Thị Hương</h4>
                        <p class="text-sm text-gray-600">Học sinh lớp 10</p>
                    </div>
                </div>
                <div class="text-yellow-400 mb-2">★★★★★</div>
                <p class="text-gray-700 leading-relaxed">
                    "Mình đã thử nhiều nền tảng khác nhưng đây là nơi tốt nhất. Gia sư nhiệt tình, 
                    giá cả hợp lý và hệ thống hỗ trợ rất nhanh chóng. Mình đã giới thiệu cho nhiều bạn!"
                </p>
            </div>

        </div>

        <div class="mt-12 text-center fade-up">
            <p class="text-gray-700 mb-4">
                Hàng trăm đánh giá 5 sao từ học sinh và phụ huynh khắp cả nước
            </p>
            <a href="#" class="text-indigo-600 font-medium hover:text-indigo-700 transition">
                Xem thêm đánh giá →
            </a>
        </div>

    </div>
</div>


{{-- ===========================
      CÁC CHƯƠNG TRÌNH ĐẶC BIỆT
=========================== --}}
<div class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        
        <div class="text-center fade-up mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Chương trình đặc biệt</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Các sáng kiến và chương trình hỗ trợ giáo dục của chúng tôi
            </p>
        </div>

        <div class="space-y-8">
            
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-xl fade-up fade-delay-1">
                <div class="flex items-start">
                    <div class="text-4xl mr-4">🎓</div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Học bổng cho học sinh xuất sắc</h3>
                        <p class="text-gray-700 leading-relaxed mb-3">
                            Mỗi năm, chúng tôi trao 100 suất học bổng toàn phần cho các học sinh có hoàn cảnh khó khăn 
                            nhưng có thành tích học tập xuất sắc. Chương trình này nhằm đảm bảo mọi học sinh đều có 
                            cơ hội tiếp cận với giáo dục chất lượng cao, bất kể hoàn cảnh kinh tế.
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            Đến nay, chúng tôi đã hỗ trợ hơn 200 học sinh thông qua chương trình học bổng, nhiều em 
                            đã đạt được những thành tích đáng tự hào trong học tập và thi cử.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-teal-50 p-8 rounded-xl fade-up fade-delay-2">
                <div class="flex items-start">
                    <div class="text-4xl mr-4">🌟</div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Đào tạo kỹ năng cho gia sư</h3>
                        <p class="text-gray-700 leading-relaxed mb-3">
                            Chúng tôi tổ chức các khóa đào tạo miễn phí về phương pháp sư phạm hiện đại, kỹ năng giao tiếp 
                            và quản lý lớp học cho gia sư trên nền tảng. Mục tiêu là nâng cao chất lượng giảng dạy và 
                            đảm bảo học sinh nhận được trải nghiệm học tập tốt nhất.
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            Các khóa học được thiết kế bởi các chuyên gia giáo dục hàng đầu và cập nhật thường xuyên 
                            theo xu hướng giáo dục mới nhất. Gia sư hoàn thành khóa đào tạo sẽ nhận được chứng chỉ 
                            và huy hiệu đặc biệt trên profile.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-8 rounded-xl fade-up fade-delay-3">
                <div class="flex items-start">
                    <div class="text-4xl mr-4">📚</div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Thư viện tài liệu miễn phí</h3>
                        <p class="text-gray-700 leading-relaxed mb-3">
                            Chúng tôi xây dựng một thư viện tài liệu học tập đồ sộ với hàng nghìn đề thi, bài giảng, 
                            video hướng dẫn và tài liệu tham khảo hoàn toàn miễn phí. Tất cả học sinh trên nền tảng 
                            đều có thể truy cập và sử dụng các tài liệu này.
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            Thư viện được cập nhật hàng tuần với nội dung mới từ các gia sư và chuyên gia giáo dục. 
                            Chúng tôi cũng khuyến khích cộng đồng đóng góp và chia sẻ tài liệu chất lượng để 
                            cùng nhau xây dựng một kho tàng kiến thức phong phú.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-orange-50 to-red-50 p-8 rounded-xl fade-up fade-delay-4">
                <div class="flex items-start">
                    <div class="text-4xl mr-4">🤝</div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Hợp tác với các trường học</h3>
                        <p class="text-gray-700 leading-relaxed mb-3">
                            Chúng tôi đang hợp tác với hơn 50 trường học trên cả nước để mang đến các chương trình 
                            hỗ trợ học tập chuyên biệt cho học sinh. Các trường đối tác có thể sử dụng nền tảng của 
                            chúng tôi với ưu đãi đặc biệt để tổ chức lớp học phụ đạo và bồi dưỡng học sinh.
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            Chúng tôi cũng tổ chức các hội thảo và workshop tại trường để chia sẻ phương pháp học tập 
                            hiệu quả và hướng nghiệp cho học sinh. Đây là cách chúng tôi đóng góp vào cộng đồng và 
                            nâng cao chất lượng giáo dục Việt Nam.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>


{{-- ===========================
      CAM KẾT PHÁT TRIỂN BỀN VỮNG
=========================== --}}
<div class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6">
        
        <div class="grid md:grid-cols-2 gap-12 items-center">
            
            <div class="fade-up">
                <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=1000&q=80"
                     class="rounded-xl shadow-lg hover:scale-[1.02] transition">
            </div>

            <div class="fade-up fade-delay-1">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Phát triển bền vững</h2>
                <p class="text-gray-700 mb-4 leading-relaxed">
                    Chúng tôi tin rằng giáo dục là nền tảng cho sự phát triển bền vững của xã hội. 
                    Vì vậy, chúng tôi cam kết không chỉ cung cấp dịch vụ kết nối gia sư, mà còn đóng góp 
                    tích cực vào cộng đồng và môi trường.
                </p>
                <p class="text-gray-700 mb-4 leading-relaxed">
                    Chúng tôi khuyến khích học trực tuyến để giảm thiểu lượng khí thải carbon từ việc di chuyển. 
                    Hệ thống của chúng tôi được vận hành trên các máy chủ xanh sử dụng năng lượng tái tạo, 
                    góp phần bảo vệ môi trường.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    10% lợi nhuận hàng năm của chúng tôi được dành cho các chương trình giáo dục cộng đồng 
                    và hỗ trợ các tổ chức phi lợi nhuận hoạt động trong lĩnh vực giáo dục. Chúng tôi tin rằng 
                    thành công thực sự đến từ việc chia sẻ và đóng góp cho cộng đồng.
                </p>
            </div>

        </div>

    </div>
</div>


{{-- ===========================
      CÔNG NGHỆ & BẢO MẬT
=========================== --}}
<div class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        
        <div class="text-center fade-up mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Công nghệ & Bảo mật</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Chúng tôi sử dụng công nghệ tiên tiến nhất để đảm bảo trải nghiệm an toàn và tiện lợi
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            
            <div class="fade-up fade-delay-1">
                <div class="bg-gray-50 p-8 rounded-xl">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">🔐 Bảo mật đa lớp</h3>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        Hệ thống của chúng tôi được bảo vệ bởi các biện pháp bảo mật tiên tiến nhất trong ngành. 
                        Tất cả dữ liệu được mã hóa end-to-end, đảm bảo thông tin cá nhân của bạn luôn được bảo vệ.
                    </p>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Mã hóa SSL/TLS cho mọi giao dịch</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Xác thực hai yếu tố (2FA)</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Giám sát bảo mật 24/7</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Tuân thủ GDPR và các tiêu chuẩn quốc tế</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="fade-up fade-delay-2">
                <div class="bg-gray-50 p-8 rounded-xl">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">🤖 Trí tuệ nhân tạo</h3>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        Công nghệ AI giúp chúng tôi kết nối bạn với gia sư phù hợp nhất dựa trên phong cách học, 
                        mục tiêu và nhu cầu cá nhân. Hệ thống học máy của chúng tôi ngày càng thông minh hơn 
                        theo thời gian.
                    </p>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Gợi ý gia sư thông minh</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Phân tích tiến độ học tập</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Dự đoán kết quả học tập</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-indigo-600 mr-2">✓</span>
                            <span>Tối ưu hóa lịch học tự động</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="mt-8 bg-gradient-to-r from-indigo-50 to-blue-50 p-8 rounded-xl fade-up fade-delay-3">
            <h3 class="text-xl font-bold text-gray-900 mb-3">📱 Đa nền tảng</h3>
            <p class="text-gray-700 leading-relaxed">
                Truy cập nền tảng của chúng tôi mọi lúc, mọi nơi trên web, iOS và Android. Dữ liệu được 
                đồng bộ tự động giữa các thiết bị, đảm bảo trải nghiệm liền mạch. Bạn có thể bắt đầu tìm gia sư 
                trên máy tính, tiếp tục trên điện thoại và hoàn tất đặt lịch trên tablet mà không gặp bất kỳ 
                trở ngại nào.
            </p>
        </div>

    </div>
</div>


{{-- ===========================
      BOX LIÊN HỆ
=========================== --}}
<div class="py-20 bg-gray-100 fade-up">
    <div class="max-w-4xl mx-auto px-6 text-center">
        
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Sẵn sàng bắt đầu hành trình học tập?</h2>
        <p class="text-gray-700 max-w-2xl mx-auto mb-6 leading-relaxed">
            Tham gia cùng hàng nghìn học sinh và gia sư trên nền tảng của chúng tôi. 
            Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ. Chúng tôi luôn hỗ trợ 24/7 
            để đảm bảo bạn có trải nghiệm tốt nhất.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('contact') }}"
               class="inline-block px-8 py-3 bg-indigo-600 text-white rounded-lg shadow-lg hover:bg-indigo-700 transition font-medium">
               Liên hệ ngay
            </a>
            <a href="{{ route('register') }}"
               class="inline-block px-8 py-3 bg-white text-indigo-600 border-2 border-indigo-600 rounded-lg hover:bg-indigo-50 transition font-medium">
               Đăng ký miễn phí
            </a>
        </div>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="text-2xl mb-2">📧</div>
                <h4 class="font-bold text-gray-900 mb-1">Email</h4>
                <p class="text-gray-600 text-sm">support@example.com</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="text-2xl mb-2">📞</div>
                <h4 class="font-bold text-gray-900 mb-1">Hotline</h4>
                <p class="text-gray-600 text-sm">1900 1234 (Miễn phí)</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="text-2xl mb-2">💬</div>
                <h4 class="font-bold text-gray-900 mb-1">Live Chat</h4>
                <p class="text-gray-600 text-sm">Hỗ trợ trực tuyến 24/7</p>
            </div>
        </div>

    </div>
</div>

@endsection