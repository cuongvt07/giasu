@extends('layouts.admin')

@section('page_title', 'Chi Tiết Người Dùng')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Chi Tiết Người Dùng</h2>
            <div class="flex space-x-3">
                @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Xóa
                    </button>
                </form>
                @endif
            </div>
        </div>

        <div class="p-6">
            <div class="flex items-center mb-6">
                <div class="flex-shrink-0 h-24 w-24">
                    <img class="h-24 w-24 rounded-full" src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" alt="{{ $user->name }}">
                </div>
                <div class="ml-6">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <div class="flex mt-2 space-x-2">
                        @if($user->is_admin)
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Admin
                        </span>
                        @endif
                        @if($user->tutor)
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Gia Sư
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            Học Sinh
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-t-lg">
                        <dt class="text-sm font-medium text-gray-500">ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->id }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Ngày Tạo</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->created_at->format('d/m/Y H:i:s') }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Cập Nhật Lần Cuối</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->updated_at->format('d/m/Y H:i:s') }}</dd>
                    </div>
                    @if($user->tutor)
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Vai Trò Gia Sư</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <a href="{{ route('admin.tutors.show', $user->tutor) }}" class="text-indigo-600 hover:text-indigo-900">
                                Xem thông tin gia sư
                            </a>
                        </dd>
                    </div>

                    <!-- Thông tin gia sư -->
                    <div class="bg-blue-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 mt-4 rounded-lg">
                        <dt class="text-sm font-medium text-blue-800 col-span-3 mb-2">Thông Tin Gia Sư</dt>
                        
                        <dt class="text-sm font-medium text-gray-500">Trình Độ Học Vấn</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->tutor->education_level }}</dd>
                        
                        <dt class="text-sm font-medium text-gray-500">Trường Đại Học</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->tutor->university }}</dd>
                        
                        <dt class="text-sm font-medium text-gray-500">Chuyên Ngành</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->tutor->major }}</dd>
                        
                        <dt class="text-sm font-medium text-gray-500">Kinh Nghiệm Giảng Dạy</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->tutor->teaching_experience }}</dd>
                        
                        <dt class="text-sm font-medium text-gray-500">Giá Theo Giờ</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ number_format($user->tutor->hourly_rate, 0, ',', '.') }}đ</dd>
                        
                        <dt class="text-sm font-medium text-gray-500">Trạng Thái</dt>
                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                            @switch($user->tutor->status)
                                @case('active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Hoạt Động
                                    </span>
                                    @break
                                @case('inactive')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Ngừng Hoạt Động
                                    </span>
                                    @break
                                @default
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Chờ Duyệt
                                    </span>
                            @endswitch
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection 