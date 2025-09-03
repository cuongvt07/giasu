@extends('layouts.admin')

@section('page_title', 'Quản Lý Người Dùng')

@section('content')
<div class="bg-white shadow rounded-lg mb-6">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thống Kê</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="text-blue-800 text-xl font-bold">{{ $userCount }}</div>
                <div class="text-blue-600">Tổng người dùng</div>
            </div>
            <div class="bg-green-50 rounded-lg p-4">
                <div class="text-green-800 text-xl font-bold">{{ $tutorCount }}</div>
                <div class="text-green-600">Gia sư</div>
            </div>
            <div class="bg-purple-50 rounded-lg p-4">
                <div class="text-purple-800 text-xl font-bold">{{ $normalUserCount }}</div>
                <div class="text-purple-600">Học sinh</div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white shadow rounded-lg">
    <div class="p-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-900">Danh Sách Người Dùng</h2>
        <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
            Thêm Người Dùng
        </a>
    </div>

    <div class="px-6 pb-4">
        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Lọc theo vai trò:</h4>
            <div class="flex space-x-2">
                <a href="{{ route('admin.users.index', array_merge(request()->except('role', 'page'), ['role' => 'all'])) }}" 
                   class="px-4 py-2 rounded-md text-sm {{ !request('role') || request('role') === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Tất cả
                </a>
                <a href="{{ route('admin.users.index', array_merge(request()->except('role', 'page'), ['role' => 'admin'])) }}"
                   class="px-4 py-2 rounded-md text-sm {{ request('role') === 'admin' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Admin
                </a>
                <a href="{{ route('admin.users.index', array_merge(request()->except('role', 'page'), ['role' => 'student'])) }}"
                   class="px-4 py-2 rounded-md text-sm {{ request('role') === 'student' || (!request('role') && !isset($role)) ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Học sinh
                </a>
                <a href="{{ route('admin.users.index', array_merge(request()->except('role', 'page'), ['role' => 'tutor'])) }}"
                   class="px-4 py-2 rounded-md text-sm {{ request('role') === 'tutor' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Gia sư
                </a>
            </div>
        </div>

        <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center space-x-4">
            <input type="hidden" name="role" value="{{ request('role', 'student') }}">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm theo tên hoặc email</label>
                <div class="relative rounded-md shadow-sm">
                    <input type="text" name="search" id="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Nhập tên hoặc email..." value="{{ request('search') }}">
                    <div class="absolute inset-y-0 right-0 flex items-center">
                        <button type="submit" class="h-full px-3 py-0 border-transparent bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700 focus:outline-none">
                            Tìm
                        </button>
                    </div>
                </div>
            </div>
            @if(request('search'))
            <div>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Xóa tìm kiếm
                </a>
            </div>
            @endif
        </form>
        
        @if(request('search'))
        <div class="mt-2 text-sm text-gray-500">
            Kết quả tìm kiếm cho: <span class="font-medium text-indigo-600">{{ request('search') }}</span>
            <span class="ml-2">({{ $users->total() }} kết quả)</span>
        </div>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vai Trò</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thông Tin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày Tạo</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" alt="{{ $user->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->is_admin)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Admin
                        </span>
                        @elseif($user->tutor)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Gia Sư
                        </span>
                        @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            Học Sinh
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($user->tutor)
                            <div>{{ $user->tutor->education_level }}</div>
                            <div>{{ $user->tutor->teaching_experience }}</div>
                        @else
                            <div>-</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            Chi Tiết
                        </a>
                        
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                Xóa
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Không có người dùng nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4">
        {{ $users->appends(request()->query())->links() }}
    </div>
</div>
@endsection 