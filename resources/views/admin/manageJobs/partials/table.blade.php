<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người đăng tin</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Môn học</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thời gian yêu cầu</th>
                @if($showStatus)
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                @endif
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($bookings as $booking)
                <tr>
                    {{-- Người đăng tin --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $booking->poster_name ?? 'Không có tên' }}</div>
                        <div class="text-sm text-gray-500">{{ $booking->poster_email ?? 'Không có email' }}</div>
                    </td>

                    {{-- Môn học --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $booking->subject_grade ?? 'Không có môn học' }}</div>
                    </td>

                    {{-- Thời gian yêu cầu --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y H:i') }}</div>
                    </td>

                    {{-- Trạng thái --}}
                    @if($showStatus)
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    @endif

                    {{-- Thao tác --}}
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button type="button" class="text-indigo-600 hover:text-indigo-900 mr-3" onclick="document.getElementById('modal-{{ $booking->id }}').classList.toggle('hidden')">
                            Xem
                        </button>
                        @if($booking->status === 'pending')
                            <form action="" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="published">
                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3"
                                        onclick="return confirm('Bạn có chắc chắn muốn phê duyệt tin tuyển này?')">
                                    Phê duyệt
                                </button>
                            </form>
                            <form action="" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Bạn có chắc chắn muốn từ chối tin tuyển này?')">
                                    Từ chối
                                </button>
                            </form>
                        @elseif($booking->status === 'published')
                            <form action="" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="closed">
                                <button type="submit" class="text-blue-600 hover:text-blue-900"
                                        onclick="return confirm('Bạn có chắc chắn muốn đánh dấu tin tuyển này là hoàn thành?')">
                                    Hoàn thành
                                </button>
                            </form>
                        @endif
                        @if($canDelete)
                            <form action="" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa tin tuyển này?')">
                                    Xóa
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>

                {{-- Modal for Job Details --}}
                <div id="modal-{{ $booking->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
                    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-6 max-h-[80vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Chi tiết tin tuyển</h3>
                            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="document.getElementById('modal-{{ $booking->id }}').classList.add('hidden')">
                                ✕
                            </button>
                        </div>
                        <div class="space-y-4">
                            <p><strong>Môn học:</strong> {{ $booking->subject_grade ?? 'N/A' }}</p>
                            <p><strong>Người đăng:</strong> {{ $booking->poster_name ?? 'N/A' }} ({{ $booking->poster_email ?? 'N/A' }})</p>
                            <p><strong>Mục tiêu:</strong> {{ $booking->goal ?? 'N/A' }}</p>
                            <p><strong>Mô tả:</strong> {{ $booking->description ?? 'N/A' }}</p>
                            <p><strong>Ngân sách:</strong> {{ number_format($booking->budget_min, 0, ',', '.') }} - {{ number_format($booking->budget_max, 0, ',', '.') }} VNĐ/{{ $booking->budget_unit ?? 'buổi' }}</p>
                            <p><strong>Địa chỉ:</strong> {{ $booking->address_line ?? 'N/A' }}</p>
                            <p><strong>Thời gian yêu cầu:</strong> {{ \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y H:i') }}</p>
                            <p><strong>Hạn chót:</strong> {{ \Carbon\Carbon::parse($booking->deadline_at)->format('d/m/Y') }}</p>
                            <p><strong>Trạng thái:</strong> {{ $booking->status }}</p>
                            @if($booking->applications && $booking->applications->isNotEmpty())
                                <div>
                                    <strong>Danh sách ứng tuyển:</strong>
                                    <ul class="mt-2 space-y-2">
                                        @foreach($booking->applications as $application)
                                            <li class="border-t pt-2">
                                                <p><strong>Gia sư:</strong> {{ $application->applicant_name ?? 'N/A' }} ({{ $application->applicant_email ?? 'N/A' }})</p>
                                                <p><strong>Trạng thái:</strong> {{ $application->status }}</p>
                                                <p><strong>Ghi chú:</strong> {{ $application->note ?? 'N/A' }}</p>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <p><strong>Danh sách ứng tuyển:</strong> Chưa có ứng tuyển</p>
                            @endif
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
                                    onclick="document.getElementById('modal-{{ $booking->id }}').classList.add('hidden')">
                                Đóng
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="{{ $showStatus ? 5 : 4 }}" class="px-6 py-4 text-center text-sm text-gray-500">
                        Không có tin tuyển nào
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $bookings->links() }}
</div>