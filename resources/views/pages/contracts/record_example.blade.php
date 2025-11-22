@extends('layouts.app')

@section('content')
<div class="contract-wrapper py-10">
    <div class="contract-paper"> 
        {{-- 794x1123px ~ khổ A4 ở 96dpi --}}

        <!-- Quốc hiệu + tiêu đề -->
        <div class="text-center mb-10">
<div class="flex justify-between items-start mb-4">
    
    <!-- Cột trái -->
    <div class="text-left flex flex-col items-center">
        <p class="font-bold text-sm">TRUNG TÂM GIA SƯ TRÍ TUỆ VIỆT</p>
        <img src="https://shop.noithatvinplus.vn/wp-content/uploads/2025/11/1197368731297420539.jpg" 
             alt="Logo" class="h-24 w-auto mt-2">
    </div>

    <!-- Cột phải -->
    <div class="text-right flex flex-col items-center">
        <p class="uppercase text-sm">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
        <p class="font-bold text-sm">Độc lập – Tự do – Hạnh phúc</p>
    </div>

</div>

            <h1 class="underline text-center">HỢP ĐỒNG GIA SƯ</h1>
            <p class="mt-2 text-center">Số: HD-{{ $contract->id }}/{{ date('Y') }}</p>
        </div>

        <!-- Thông tin các bên -->
        <div class="mb-8 space-y-4">
            <div>
                <p><strong>A/ BÊN A (Phụ huynh/Học sinh):</strong></p>
                <p>Ông/Bà: {{ $contract->student_name }}</p>
                <p>Email: {{ $contract->student_email ?? 'Chưa bổ sung' }}</p>
            </div>
            <div>
                <p><strong>B/ BÊN B (Gia sư):</strong></p>
                <p>Ông/Bà: {{ $contract->tutor_name }}</p>
                <p>Email: {{ $contract->tutor_email ?? 'Chưa bổ sung' }}</p>
            </div>
        </div>

        <p class="mb-6">
            Sau khi trao đổi, hai bên thống nhất lập hợp đồng giao dịch có sự chứng nhận của Trung tâm Gia sư Trí tuệ Việt với nội dung và những điều khoản sau:
        </p>

        <!-- Điều 1: Nội dung hợp đồng -->
        <h2>Điều 1: NỘI DUNG HỢP ĐỒNG</h2>
        <p>Bên A đồng ý để bên B giảng dạy, với các thông tin dưới đây:</p>
        <ul class="mb-4">
            <li><strong>Lớp học:</strong> Trung tâm gia sư trí tuê Việt</li>
            <li><strong>Môn học:</strong> {{ $contract->subject_name }}</li>
            <li><strong>Số buổi/tuần:</strong> {{ $contract->sessions_per_week ?? 'Chưa bổ sung' }}</li>
            <li><strong>Ngân sách:</strong> {{ number_format($contract->budget_min) }} - {{ number_format($contract->budget_max) }} {{ $contract->budget_unit }}</li>
            <li><strong>Địa điểm:</strong> {{ $contract->address_line }}</li>
        </ul>

        <!-- Điều 2: Nghĩa vụ của bên A -->
        <h2>Điều 2: NGHĨA VỤ CỦA BÊN A</h2>
        <ol class="mb-4">
            <li>Sau khi hợp đồng có hiệu lực, bên A phải cung cấp thông tin liên lạc và địa chỉ học cho Trung tâm Gia sư Trí tuệ Việt trong vòng 24h sau đó.</li>
            <li>Bên A không được phép thay đổi các thông tin lớp học như đã cung cấp.</li>
        </ol>

        <!-- Điều 3: Nghĩa vụ của bên B -->
        <h2>Điều 3: NGHĨA VỤ CỦA BÊN B</h2>
        <ol class="mb-4">
            <li>Sau khi hợp đồng có hiệu lực, bên B chủ động liên hệ với Trung tâm Gia sư Trí tuệ Việt qua số điện thoại 0967-888-280 hoặc hòm mail contact@giasuconnect.vn để nhận thông tin liên lạc và địa chỉ của bên A.</li>
            <li>Khi đến gặp tại buổi đầu tiên, Bên B phải xuất trình cho Bên A xem bản gốc (hoặc bản sao, ảnh chụp) các loại giấy tờ: Thẻ sinh viên (nếu là sinh viên), Thẻ giáo viên (nếu là giáo viên), Thẻ căn cước công dân.</li>
            <li>Bên B phải chuẩn bị giáo trình, giáo án trước khi giảng dạy. Đồng thời đảm bảo được lịch học và thời gian giảng dạy cho phụ huynh/học viên. (Nếu vì lý do bất khả kháng, Bên B phải báo trước cho phụ huynh/học viên ít nhất 2 giờ).</li>
            <li>Bên B phải thông báo cho Trung tâm trong thời gian tối đa 24h, kể từ thời điểm phát sinh các sự cố: không liên hệ được với phụ huynh/học viên; phụ huynh/học viên không sắp xếp được lịch học; phụ huynh/học viên thông báo giảm số buổi học/tuần, giảm học phí, tạm dừng lớp, hủy lớp; các sự cố nghiêm trọng khác.</li>
            <li>Bên B lưu số điện thoại của Trung tâm (0967-888-280) để Trung tâm tiện liên hệ nếu lớp xảy ra sự cố. Trường hợp Bên B không nghe điện thoại, Bên B phải gọi lại cho Trung tâm muộn nhất 4h kể từ khi có cuộc gọi nhỡ.</li>
            <li>Bên B không được chuyển giao lớp học cho người khác.</li>
        </ol>

        <!-- Điều 4: Chính sách học thử -->
        <h2>Điều 4: CHÍNH SÁCH HỌC THỬ CỦA HỌC SINH / HỌC VIÊN</h2>
        <p>Học sinh/học viên được quyền học thử với gia sư qua 2 buổi học đầu tiên. Sau 2 buổi này:</p>
        <ul class="mb-4">
            <li>Nếu không đồng ý nhận gia sư, Bên A thanh toán 70% học phí 2 buổi cho Bên B;</li>
            <li>Nếu đồng ý và tiếp tục học, Bên A sẽ thanh toán 100% học phí của cả 2 buổi này và những buổi sau cho Bên B theo sự thỏa thuận của 2 bên.</li>
        </ul>

        <!-- Điều 5: Phạt hợp đồng -->
        <h2>Điều 5: PHẠT HỢP ĐỒNG</h2>
        <p class="mb-4">
            Trong trường hợp Bên A hoặc Bên B có lý do không muốn tiếp tục hợp đồng hay các vấn đề tranh chấp giữa các bên thì cả 2 bên cần liên hệ đến Trung tâm Gia sư Trí tuệ Việt để được giải quyết.
        </p>

        <!-- Điều 6: Thời gian có hiệu lực -->
        <h2>Điều 6: THỜI GIAN CÓ HIỆU LỰC CỦA HỢP ĐỒNG</h2>
        <p class="mb-8">
            Hợp đồng có hiệu lực kể từ khi 2 bên ký và được Quản trị viên hệ thống xác nhận.
        </p>

        <!-- Chữ ký -->
        <div class="grid grid-cols-2 gap-12 mt-12 text-center signature-block">
            <!-- Bên A -->
            <div>
                <p class="font-bold">BÊN A</p>
                @if($contract->signed_student_at)
                    <p class="mt-2 text-green-700">✅ Đã ký lúc {{ $contract->signed_student_at }}</p>
                @else
                    @if(auth()->id() == $contract->student_id)
                        <form method="POST" action="{{ route('contracts.accept', $contract->id) }}" class="sign-form">
                            @csrf
                            <button type="submit" class="btn-sign">Ký hợp đồng</button>
                        </form>
                    @else
                        <p class="mt-2 text-gray-400 italic">Chờ bên A ký</p>
                    @endif
                @endif
                <div class="sign-line"></div>
            </div>

            <!-- Bên B -->
            <div>
                <p class="font-bold">BÊN B</p>
                @if($contract->signed_tutor_at)
                    <p class="mt-2 text-green-700">✅ Đã ký lúc {{ $contract->signed_tutor_at }}</p>
                @else
                    @if((auth()->user()->tutor->id ?? null) == $contract->tutor_id)
                        <form method="POST" action="{{ route('contracts.accept', $contract->id) }}" class="sign-form">
                            @csrf
                            <button type="submit" class="btn-sign">Ký hợp đồng</button>
                        </form>
                    @else
                        <p class="mt-2 text-gray-400 italic">Chờ bên B ký</p>
                    @endif
                @endif
                <div class="sign-line"></div>
            </div>
        </div>

        <!-- Xác nhận hệ thống -->
        <div class="text-center mt-12">
            <p class="font-bold">Xác nhận hệ thống</p>
            @if($contract->system_verified_at)
                <p class="text-green-800 mt-2">🔒 Đã xác thực</p>
            @else
                <p class="text-yellow-700 mt-2">⏳ Đang chờ admin xác thực</p>
            @endif
        </div>
    </div>
</div>
@endsection

<style>
    /* Khung giấy A4 */
    .contract-paper {
        width: 794px;
        min-height: 1123px;
        background: #fff;
        padding: 20px 30px;
        margin: 0 auto;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        border-radius: 6px;
        color: #000;
        font-family: "Times New Roman", Times, serif;
        font-size: 14pt;
        line-height: 1.6;
    }

    .contract-paper h1 {
        font-size: 22pt;
        color: #000;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .contract-paper h2 {
        font-size: 16pt;
        color: #000;
        margin-top: 24px;
        margin-bottom: 12px;
    }

    .contract-paper p,
    .contract-paper li {
        font-size: 14pt;
        color: #000;
        margin-bottom: 6px;
    }

    .contract-paper ul {
        margin-left: 24px;
        list-style-type: disc;
    }

    .btn-sign {
        margin-top: 12px;
        padding: 10px 20px;
        background: #0d6efd;
        color: #fff;
        border-radius: 4px;
        font-size: 14pt;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-sign:hover {
        background: #0a58ca;
    }

    .signature-block {
        margin-top: 60px;
    }
    .sign-line {
        margin-top: 60px;
        border-top: 1px dotted #000;
        width: 70%;
        margin-left: auto;
        margin-right: auto;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.sign-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // ngăn submit tạm thời
            const confirmSign = confirm("Bạn có chắc chắn muốn ký hợp đồng này không?");
            if (confirmSign) {
                form.submit(); // submit nếu người dùng xác nhận
            }
        });
    });
});
</script>
