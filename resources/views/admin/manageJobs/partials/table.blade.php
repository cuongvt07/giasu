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
                <!-- Main Row -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $booking->poster_name }}</div>
                        <div class="text-sm text-gray-500">{{ $booking->poster_email }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $booking->subject_grade }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $booking->created_at instanceof \DateTime ? $booking->created_at->format('d/m/Y H:i') : $booking->created_at }}
                    </td>
                    @if($showStatus)
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    @endif
                    <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                        <button @click="openDropdowns[{{ $booking->id }}] = !openDropdowns[{{ $booking->id }}]"
                                class="text-indigo-600 hover:text-indigo-900">
                            Xem
                        </button>
                    </td>
                </tr>

                <!-- Details Row -->
                <tr x-show="openDropdowns[{{ $booking->id }}]" x-transition>
                    <td colspan="{{ $showStatus ? 5 : 4 }}" class="bg-gray-50 px-6 py-6 text-sm text-gray-700 rounded-b-xl shadow-inner">
                        <div class="grid sm:grid-cols-2 gap-4 border-b pb-4 mb-4">
                            <div><strong>Mục tiêu:</strong> {{ $booking->goal ?? '—' }}</div>
                            <div><strong>Ngân sách:</strong> {{ number_format($booking->budget_min) }} - {{ number_format($booking->budget_max) }} {{ $booking->budget_unit }}</div>
                            <div><strong>Mô tả:</strong> {{ $booking->description ?? '—' }}</div>
                            <div><strong>Địa chỉ:</strong> {{ $booking->address_line ?? '—' }}</div>
                            <div><strong>Thời gian yêu cầu:</strong> {{ $booking->created_at instanceof \DateTime ? $booking->created_at->format('d/m/Y H:i') : $booking->created_at }}</div>
                            <div><strong>Hạn chót:</strong> {{ $booking->deadline_at instanceof \DateTime ? $booking->deadline_at->format('d/m/Y') : ($booking->deadline_at ?? '—') }}</div>
                            <div><strong>Trạng thái:</strong> {{ $statusLabel }}</div>
                        </div>

                        <!-- Applicants -->
                        <div>
                            <h3 class="font-semibold mb-2 text-gray-800">Danh sách ứng tuyển:</h3>
                            <ul class="space-y-4">
                                @forelse($booking->applications as $application)
                                    <li class="border-t pt-2">
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                                            <div>
                                                <p><strong>Gia sư:</strong> {{ $application->applicant_name }} ({{ $application->applicant_email }})</p>
                                                <p><strong>Trạng thái:</strong> {{ ucfirst($application->status) }}</p>
                                                <p><strong>Ghi chú:</strong> {{ $application->note ?? '—' }}</p>
                                            </div>
                                            <div class="mt-2 sm:mt-0">
                                                @if($application->status === 'requested')
                                                    <button
                                                        id="btn-{{ $booking->id }}-{{ $application->id }}"
                                                        @click="acceptAndComplete({{ $booking->id }}, {{ $application->id }}, {{ $application->tutor_id }})"
                                                        class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700"
                                                        :disabled="accepting">
                                                        ✔ Xác nhận và hoàn tất
                                                    </button>
                                                @elseif($application->status === 'accepted')
                                                    <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded">
                                                        ✅ Đã xác nhận
                                                    </span>
                                                @elseif($application->status === 'rejected')
                                                    <span class="px-3 py-1 text-sm bg-gray-200 text-gray-600 rounded">
                                                        ❌ Đã từ chối
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li>Không có gia sư nào ứng tuyển.</li>
                                @endforelse
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $showStatus ? 5 : 4 }}" class="px-6 py-4 text-center text-sm text-gray-500">
                        Không có tin tuyển nào
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
</div>