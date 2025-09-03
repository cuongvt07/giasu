@extends('layouts.tutor')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Quản Lý Lịch Rảnh
        </h3>
    </div>
    <div class="px-4 py-5 sm:p-6">
        <!-- Form thêm lịch rảnh -->
        <form action="{{ route('tutor.schedule.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700">Thứ trong tuần</label>
                    <select id="day_of_week" name="day_of_week" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="0">Chủ nhật</option>
                        <option value="1">Thứ hai</option>
                        <option value="2">Thứ ba</option>
                        <option value="3">Thứ tư</option>
                        <option value="4">Thứ năm</option>
                        <option value="5">Thứ sáu</option>
                        <option value="6">Thứ bảy</option>
                    </select>
                </div>
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Giờ bắt đầu</label>
                    <input type="time" name="start_time" id="start_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700">Giờ kết thúc</label>
                    <input type="time" name="end_time" id="end_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>
            <div class="pt-4">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Thêm lịch rảnh
                </button>
            </div>
        </form>

        <!-- Hiển thị danh sách lịch rảnh -->
        <div class="mt-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thứ trong tuần
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Giờ bắt đầu
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Giờ kết thúc
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($schedules as $schedule)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @switch($schedule->day_of_week)
                                        @case(0) Chủ nhật @break
                                        @case(1) Thứ hai @break
                                        @case(2) Thứ ba @break
                                        @case(3) Thứ tư @break
                                        @case(4) Thứ năm @break
                                        @case(5) Thứ sáu @break
                                        @case(6) Thứ bảy @break
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $schedule->start_time->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $schedule->end_time->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('tutor.schedule.destroy', $schedule) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bạn có chắc chắn muốn xóa lịch rảnh này?')">
                                            Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Chưa có lịch rảnh nào được thiết lập
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 