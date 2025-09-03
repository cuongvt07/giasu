@extends('layouts.admin')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="p-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-900">Danh Sách Môn Học</h2>
        <a href="{{ route('admin.subjects.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Thêm Môn Học
        </a>
    </div>

    <div class="px-6 pb-4">
        <form action="{{ route('admin.subjects.index') }}" method="GET" class="flex items-center space-x-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm theo tên môn học</label>
                <div class="relative rounded-md shadow-sm">
                    <input type="text" name="search" id="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Nhập tên môn học..." value="{{ request('search') }}">
                    <div class="absolute inset-y-0 right-0 flex items-center">
                        <button type="submit" class="h-full px-3 py-0 border-transparent bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700 focus:outline-none">
                            Tìm
                        </button>
                    </div>
                </div>
            </div>
            @if(request('search'))
            <div>
                <a href="{{ route('admin.subjects.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Xóa tìm kiếm
                </a>
            </div>
            @endif
        </form>
        
        @if(request('search'))
        <div class="mt-2 text-sm text-gray-500">
            Kết quả tìm kiếm cho: <span class="font-medium text-indigo-600">{{ request('search') }}</span>
            <span class="ml-2">({{ $subjects->total() }} kết quả)</span>
        </div>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Môn Học</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh Mục</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số Gia Sư</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số Đặt Lịch</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($subjects as $subject)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $subject->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $subject->category }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $subject->tutors_count ?? 0 }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $subject->bookings_count ?? 0 }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $subject->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $subject->is_active ? 'Hoạt Động' : 'Ngừng Hoạt Động' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.subjects.show', $subject) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Xem</a>
                        <a href="{{ route('admin.subjects.edit', $subject) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Sửa</a>
                        <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bạn có chắc chắn muốn xóa môn học này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                        Không có môn học nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4">
        {{ $subjects->appends(request()->query())->links() }}
    </div>
</div>
@endsection