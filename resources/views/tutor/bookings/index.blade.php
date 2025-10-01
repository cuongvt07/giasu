@extends('layouts.tutor')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Danh Sách Lịch Dạy
        </h3>
    </div>
    
    <!-- Upcoming Bookings -->
    <div class="px-4 py-5 sm:p-6">
        <h4 class="text-md font-medium text-gray-800 mb-3">Lịch dạy sắp tới</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Học sinh
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Môn học
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thời gian
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($upcomingBookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $booking->student->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $booking->subject->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $booking->start_time->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status == 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status == 'completed') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800 @endif">
                                    @if($booking->status == 'pending') Chờ xác nhận
                                    @elseif($booking->status == 'confirmed') Đã xác nhận
                                    @elseif($booking->status == 'completed') Hoàn thành
                                    @else Đã hủy @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('tutor.bookings.show', $booking) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Chi tiết
                                </a>
                                @if($booking->status == 'pending')
                                    <form action="{{ route('tutor.bookings.update-status', $booking) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                            Xác nhận
                                        </button>
                                    </form>
                                @endif
                                @if($booking->status == 'confirmed')
                                    <form action="{{ route('tutor.bookings.confirm-completion', $booking) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900 mr-3">
                                            Hoàn thành
                                        </button>
                                    </form>
                                @endif
                                
                                {{-- Nút lịch sử Lịch sử thay đổi --}}
                                <button type="button" onclick="showReasonHistory({{ $booking->id }})" class="text-red-600 hover:text-white hover:bg-red-600 border border-red-600 font-semibold rounded px-3 py-1 mr-3 transition-colors duration-150">
                                    Lịch sử thay đổi ({{ $booking->reasons->count() }})
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Không có lịch dạy sắp tới nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $upcomingBookings->links() }}
        </div>
    </div>
    
    <!-- Pending Completion Bookings -->
    <div class="px-4 py-5 sm:p-6 border-t border-gray-200">
        <h4 class="text-md font-medium text-gray-800 mb-3">Lịch dạy đã kết thúc cần xác nhận</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Học sinh
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Môn học
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thời gian
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pendingCompletionBookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $booking->student->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $booking->subject->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $booking->start_time->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Đã kết thúc, cần xác nhận
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('tutor.bookings.show', $booking) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Chi tiết
                                </a>
                                <form action="{{ route('tutor.bookings.confirm-completion', $booking) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                        Xác nhận hoàn thành
                                    </button>
                                </form>
                                <button type="button" onclick="showReasonHistory({{ $booking->id }})" class="text-red-600 hover:text-white hover:bg-red-600 border border-red-600 font-semibold rounded px-3 py-1 mr-3 transition-colors duration-150">
                                    Lịch sử thay đổi ({{ $booking->reasons->count() }})
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Không có lịch dạy cần xác nhận hoàn thành
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $pendingCompletionBookings->links() }}
        </div>
    </div>
    
    <!-- Past Bookings -->
    <div class="px-4 py-5 sm:p-6 border-t border-gray-200">
        <h4 class="text-md font-medium text-gray-800 mb-3">Lịch sử buổi dạy</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Học sinh
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Môn học
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thời gian
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pastBookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $booking->student->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $booking->subject->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $booking->start_time->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($booking->status == 'completed') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800 @endif">
                                    @if($booking->status == 'completed') Hoàn thành
                                    @else Đã hủy @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('tutor.bookings.show', $booking) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Chi tiết
                                </a>
                                <button type="button" onclick="showReasonHistory({{ $booking->id }})" class="text-red-600 hover:text-white hover:bg-red-600 border border-red-600 font-semibold rounded px-3 py-1 mr-3 transition-colors duration-150">
                                    Lịch sử thay đổi ({{ $booking->reasons->count() }})
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Không có lịch sử buổi dạy
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $pastBookings->links() }}
        </div>
    </div>
</div>

<div id="reason-history-modal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full z-50">
            <div class="px-4 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Lịch sử Lịch sử thay đổi</h3>
                <button onclick="closeReasonHistory()" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <div class="px-4 py-4" id="reason-history-content" style="max-height:500px; overflow-y:auto;">
            </div>
        </div>
    </div>
</div>

<script>
function showReasonHistory(bookingId) {
    fetch(`/tutor/bookings/${bookingId}/reasons`)
        .then(res => res.json())
        .then(data => {
            let html = '';
            if (data.length === 0) {
                html = '<div class="text-gray-500">Không có lịch sử thay đổi.</div>';
            } else {
                html = '<ul class="divide-y divide-gray-200">';
                data.forEach(item => {
                    let isNew = false;
                    if (item.created_at_iso) {
                        const created = new Date(item.created_at_iso);
                        const now = new Date();
                        isNew = (now - created) < 24*60*60*1000; // 1 ngày

                        console.log({created, now, diff: now - created, isNew});
                    }
                    html += `<li class='py-2'>
                        <span class='font-semibold'>${item.created_at}</span>
                        ${isNew ? "<span class='ml-2 px-2 py-0.5 bg-red-500 text-red-600 text-xs rounded align-middle'>Mới</span>" : ""}
                        <br><span class='text-gray-700'>Trạng thái: <b>${item.status_vi || item.status || ''}</b></span>
                        <br><span class='text-gray-700'>Lý do: ${item.reason}</span>
                        ${item.notes ? `<br><span class='text-gray-700'>Ghi chú: ${item.notes}</span>` : ''}
                        ${item.response_note ? `<br><span class='text-gray-700'>Phản hồi: ${item.response_note}</span>` : ''}
                    </li>`;
                });
                html += '</ul>';
            }
            document.getElementById('reason-history-content').innerHTML = html;
            document.getElementById('reason-history-modal').classList.remove('hidden');
        });
}
function closeReasonHistory() {
    document.getElementById('reason-history-modal').classList.add('hidden');
}
</script>
@endsection