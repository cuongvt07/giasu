@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <svg class="mx-auto h-20 w-20 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    
                    <h2 class="mt-4 text-2xl font-bold text-gray-900">Hồ sơ gia sư của bạn đang được xét duyệt</h2>
                    
                    <p class="mt-2 text-gray-600">
                        Cảm ơn bạn đã đăng ký làm gia sư trên nền tảng của chúng tôi. Chúng tôi đang xem xét hồ sơ của bạn và sẽ phản hồi trong thời gian sớm nhất.
                    </p>
                    
                    <div class="mt-8 bg-yellow-50 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Trạng thái hồ sơ</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Trạng thái: <span class="font-semibold">Đang chờ xét duyệt</span></p>
                                    <p class="mt-1">Thời gian nộp: {{ $tutor->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 bg-gray-50 p-4 rounded-md">
                        <h3 class="text-sm font-medium text-gray-900">Quá trình xét duyệt có thể bao gồm:</h3>
                        <ul class="mt-3 text-sm text-gray-600 text-left list-disc list-inside space-y-2">
                            <li>Xác minh thông tin cá nhân và học vấn</li>
                            <li>Kiểm tra các chứng chỉ và bằng cấp</li>
                            <li>Đánh giá kinh nghiệm giảng dạy</li>
                            <li>Xem xét các môn học và cấp học mà bạn đăng ký dạy</li>
                        </ul>
                    </div>
                    
                    <p class="mt-6 text-gray-600">
                        Nếu bạn có bất kỳ câu hỏi hoặc cần cập nhật thông tin trong hồ sơ của mình, vui lòng liên hệ với chúng tôi qua email: <a href="mailto:support@giasuconnect.vn" class="text-indigo-600 hover:text-indigo-500">support@giasuconnect.vn</a>
                    </p>
                    
                    <div class="mt-8">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Quay lại trang chủ
                        </a>
                        
                        <a href="{{ route('profile.edit') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cập nhật hồ sơ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 