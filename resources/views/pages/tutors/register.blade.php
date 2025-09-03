@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Trở Thành Gia Sư</h1>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        Chia sẻ kiến thức của bạn, tạo thu nhập và giúp đỡ những người khác trong hành trình học tập của họ.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div class="bg-gray-50 p-6 rounded-lg text-center">
                        <div class="inline-flex items-center justify-center h-14 w-14 rounded-full bg-indigo-100 text-indigo-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Thu Nhập Linh Hoạt</h2>
                        <p class="text-gray-600">
                            Tự do lựa chọn mức phí dạy học và thời gian dạy phù hợp với bạn.
                        </p>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg text-center">
                        <div class="inline-flex items-center justify-center h-14 w-14 rounded-full bg-indigo-100 text-indigo-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Linh Hoạt Thời Gian</h2>
                        <p class="text-gray-600">
                            Sắp xếp lịch dạy học phù hợp với thời gian của bạn.
                        </p>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg text-center">
                        <div class="inline-flex items-center justify-center h-14 w-14 rounded-full bg-indigo-100 text-indigo-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Mở Rộng Mạng Lưới</h2>
                        <p class="text-gray-600">
                            Kết nối với học sinh từ khắp nơi và xây dựng danh tiếng của bạn.
                        </p>
                    </div>
                </div>

                <div class="bg-indigo-50 p-8 rounded-lg mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 text-center">Quy trình đăng ký</h2>
                    <div class="flex flex-col md:flex-row justify-center items-center space-y-6 md:space-y-0 md:space-x-6">
                        <div class="flex flex-col items-center max-w-xs text-center">
                            <div class="bg-indigo-100 rounded-full w-12 h-12 flex items-center justify-center mb-4 text-indigo-600 font-bold">1</div>
                            <h3 class="text-lg font-semibold mb-2">Đăng ký tài khoản</h3>
                            <p class="text-gray-600 text-sm">Tạo tài khoản người dùng trên hệ thống của chúng tôi</p>
                        </div>
                        <div class="hidden md:block text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </div>
                        <div class="flex flex-col items-center max-w-xs text-center">
                            <div class="bg-indigo-100 rounded-full w-12 h-12 flex items-center justify-center mb-4 text-indigo-600 font-bold">2</div>
                            <h3 class="text-lg font-semibold mb-2">Hoàn thiện hồ sơ</h3>
                            <p class="text-gray-600 text-sm">Cập nhật thông tin cá nhân, chuyên môn và tải lên các chứng chỉ</p>
                        </div>
                        <div class="hidden md:block text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </div>
                        <div class="flex flex-col items-center max-w-xs text-center">
                            <div class="bg-indigo-100 rounded-full w-12 h-12 flex items-center justify-center mb-4 text-indigo-600 font-bold">3</div>
                            <h3 class="text-lg font-semibold mb-2">Phê duyệt</h3>
                            <p class="text-gray-600 text-sm">Đợi quản trị viên xem xét và phê duyệt hồ sơ của bạn</p>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    @auth
                        <a href="{{ route('tutors.create') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Đăng ký làm gia sư ngay
                        </a>
                    @else
                        <div class="space-y-4">
                            <p class="text-gray-700 mb-4">Để bắt đầu, bạn cần đăng nhập hoặc đăng ký tài khoản</p>
                            <div class="flex flex-col md:flex-row justify-center space-y-3 md:space-y-0 md:space-x-4">
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Đăng nhập
                                </a>
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Đăng ký tài khoản
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 