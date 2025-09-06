@extends('layouts.app')

@section('content')
<div class="contract-wrapper py-10">
    <div class="contract-paper"> 
        {{-- 794x1123px ~ khổ A4 ở 96dpi --}}

        <!-- Quốc hiệu + tiêu đề -->
        <div class="text-center mb-10">
            <p class="uppercase text-sm">Cộng Hòa Xã Hội Chủ Nghĩa Việt Nam</p>
            <p class="font-bold text-sm mb-4">Độc lập - Tự do - Hạnh phúc</p>
            <h1 class="underline">HỢP ĐỒNG GIA SƯ</h1>
            <p class="mt-2">Số: HD-{{ $contract->id }}/{{ date('Y') }}</p>
        </div>

        <!-- Thông tin các bên -->
        <div class="mb-8 space-y-2 bg-gray-50 rounded-md p-4">
            <p><strong>Bên A (Học sinh/Phụ huynh):</strong> {{ $contract->student_name }}</p>
            <p><strong>Bên B (Gia sư):</strong> {{ $contract->tutor_name }}</p>
            <div class="border-b border-dotted border-gray-400 my-4"></div>
        </div>

        <!-- Thông tin công việc -->
        <h2>Điều 1. Thông tin công việc</h2>
        <ul>
            <li><strong>Môn học:</strong> {{ $contract->subject_name }}</li>
            <li><strong>Ngân sách:</strong> {{ number_format($contract->budget_min) }} - {{ number_format($contract->budget_max) }} {{ $contract->budget_unit }}</li>
            <li><strong>Địa điểm:</strong> {{ $contract->address_line }}</li>
            <li><strong>Mục tiêu:</strong> {{ $contract->goal }}</li>
        </ul>
        <div class="border-b border-dotted border-gray-400 my-4"></div>

        <!-- Điều khoản -->
        <h2>Điều 2. Quyền và nghĩa vụ các bên</h2>
        <div>
            {!! nl2br(e($contract->contract_terms ?? 
                "1. Bên A (Học sinh/Phụ huynh) có trách nhiệm cung cấp đầy đủ thông tin và điều kiện học tập.\n
2. Bên B (Gia sư) cam kết giảng dạy đúng nội dung, đảm bảo chất lượng.\n
3. Hai bên phối hợp để đảm bảo tiến độ và mục tiêu học tập.\n
4. Nếu có tranh chấp, hai bên liên hệ Admin để xử lý.\n
5. Trường hợp nghiêm trọng sẽ mời bên thứ 3 có trách nhiệm can thiệp."
            )) !!}
        </div>
        <div class="border-b border-dotted border-gray-400 my-4"></div>

        <!-- Hiệu lực -->
        <h2>Điều 3. Hiệu lực hợp đồng</h2>
        <p>
            Hợp đồng này có hiệu lực kể từ ngày cả hai bên ký xác nhận và được hệ thống xác thực. 
            Hợp đồng được lập thành văn bản điện tử, có giá trị pháp lý tương đương văn bản giấy.
        </p>
        <div class="border-b border-dotted border-gray-400 my-4"></div>

        <!-- Chữ ký -->
        <div class="grid grid-cols-2 gap-12 mt-12 text-center signature-block">
            <!-- Bên A -->
            <div>
                <p class="font-bold">Bên A (Học sinh/Phụ huynh)</p>
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
                <p class="font-bold">Bên B (Gia sư)</p>
                @if($contract->signed_tutor_at)
                    <p class="mt-2 text-green-700">✅ Đã ký lúc {{ $contract->signed_tutor_at }}</p>
                @else
                    @if(auth()->id() == $contract->tutor_id)
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
