@extends('layouts.admin')

@section('page_title', 'Quản Lý Gia Sư')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="p-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-900">Danh Sách Gia Sư</h2>
        <a href="{{ route('admin.tutors.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Thêm Gia Sư
        </a>
    </div>

    <div class="px-6 pb-4">
        <form action="{{ route('admin.tutors.index') }}" method="GET" class="flex items-center space-x-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm theo tên hoặc email gia sư</label>
                <div class="relative rounded-md shadow-sm">
                    <input type="text" name="search" id="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Nhập tên hoặc email gia sư..." value="{{ request('search') }}">
                    <div class="absolute inset-y-0 right-0 flex items-center">
                        <button type="submit" class="h-full px-3 py-0 border-transparent bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700 focus:outline-none">
                            Tìm
                        </button>
                    </div>
                </div>
            </div>
            @if(request('search'))
            <div>
                <a href="{{ route('admin.tutors.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Xóa tìm kiếm
                </a>
            </div>
            @endif
        </form>
        
        @if(request('search'))
        <div class="mt-2 text-sm text-gray-500">
            Kết quả tìm kiếm cho: <span class="font-medium text-indigo-600">{{ request('search') }}</span>
            <span class="ml-2">({{ $tutors->total() }} kết quả)</span>
        </div>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gia Sư</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trình Độ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Môn Dạy</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kinh Nghiệm</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tutors as $tutor)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="{{ $tutor->avatar ? Storage::url($tutor->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($tutor->user->name) }}" alt="{{ $tutor->user->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $tutor->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $tutor->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $tutor->education_level }}</div>
                        <div class="text-sm text-gray-500">{{ $tutor->university }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            @foreach($tutor->subjects as $subject)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1 mb-1">
                                {{ $subject->name }}
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $tutor->teaching_experience }} năm
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @switch($tutor->status)
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
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.tutors.show', $tutor) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            Chi Tiết
                        </a>
                        <form action="{{ route('admin.tutors.destroy', $tutor) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bạn có chắc chắn muốn xóa gia sư này?')">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Không có gia sư nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4">
        {{ $tutors->appends(request()->query())->links() }}
    </div>
</div>
@endsection 