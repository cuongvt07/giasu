@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Hướng Dẫn Sử Dụng Dịch Vụ</h1>
                    
                    <div class="prose prose-indigo max-w-none">
                        <div class="flex flex-col items-center justify-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-indigo-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h2 class="text-xl font-semibold text-center mb-2">Đang cập nhật nội dung</h2>
                            <p class="text-gray-600 text-center max-w-xl">
                                Chúng tôi đang hoàn thiện hướng dẫn sử dụng chi tiết để giúp bạn tận dụng tối đa các tính năng 
                                của nền tảng Kết nối Gia sư. Vui lòng quay lại sau.
                            </p>
                            <p class="text-gray-600 text-center mt-4">
                                Trong thời gian chờ đợi, nếu bạn có câu hỏi về cách sử dụng nền tảng, đừng ngần ngại 
                                <a href="{{ route('contact') }}" class="text-indigo-600 hover:text-indigo-700">liên hệ với chúng tôi</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 