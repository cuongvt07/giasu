<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người đăng tin</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Môn học</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày đăng</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($bookings as $booking)
                <!-- Main Row -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $booking->poster_name }}</div>
                        <div class="text-sm text-gray-500">{{ $booking->poster_email }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $booking->subject_grade }}</td>
                    <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4">
                        @if($statusLabel === 'Published')
                            @php
                                $studentSigned = $booking->signed_student_at ?? false;
                                $tutorSigned = $booking->signed_tutor_at ?? false;
                                $systemVerified = $booking->system_verified_at ?? false;
                            @endphp
                            @if(!$studentSigned && !$tutorSigned)
                                <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800">Chưa ký</span>
                            @elseif($studentSigned xor $tutorSigned)
                                <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Một bên ký</span>
                            @elseif($studentSigned && $tutorSigned && !$systemVerified)
                                <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">Hai bên ký đầy đủ</span>
                            @elseif($studentSigned && $tutorSigned && $systemVerified)
                                <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Đã ký & Admin chốt</span>
                            @endif
                        @else
                            <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $statusColor }}">{{ $statusLabel }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right text-sm">
                        <button @click="openDropdowns[{{ $booking->id }}] = !openDropdowns[{{ $booking->id }}]" class="text-indigo-600 hover:text-indigo-900">
                            Xem
                        </button>
                    </td>
                </tr>

                <!-- Details Row -->
                <tr x-show="openDropdowns[{{ $booking->id }}]" x-transition>
                    <td colspan="5" class="bg-gray-50 px-6 py-6 text-sm text-gray-700">
                        <div class="grid sm:grid-cols-2 gap-4 border-b pb-4 mb-4">
                            <div><strong>Ngân sách:</strong> {{ number_format($booking->budget_min) }} - {{ number_format($booking->budget_max) }} {{ $booking->budget_unit }}</div>
                            <div><strong>Mục tiêu:</strong> {{ $booking->goal ?? '—' }}</div>
                            <div><strong>Mô tả:</strong> {{ $booking->description ?? '—' }}</div>
                            <div><strong>Địa chỉ:</strong> {{ $booking->address_line ?? '—' }}</div>

                            @if($statusLabel === 'Published')
                                <div>
                                    <strong>Ký bên Student:</strong>
                                    @if($studentSigned)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">✅</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">❌</span>
                                    @endif
                                </div>
                                <div>
                                    <strong>Ký bên Tutor:</strong>
                                    @if($tutorSigned)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">✅</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">❌</span>
                                    @endif
                                </div>
                                @if($systemVerified)
                                    <div>
                                        <strong>Admin chốt:</strong>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">✅</span>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Danh sách ứng tuyển -->
                        <div>
                            <h3 class="font-semibold mb-2 text-gray-800">Danh sách ứng tuyển:</h3>
                            <ul class="space-y-4">
                                @forelse($booking->applications as $application)
                                    <li class="border-t pt-2">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p><strong>Gia sư:</strong> {{ $application->applicant_name }} ({{ $application->applicant_email }})</p>
                                                <p><strong>Trạng thái:</strong> {{ ucfirst($application->status) }}</p>
                                            </div>
                                            <div>
                                                @if($statusLabel === 'Pending' && $application->status === 'requested')
                                                    <button
                                                        @click.prevent="acceptAndComplete({{ $booking->id }}, 'published', true, {{ $application->id }}, {{ $application->tutor_id }})"
                                                        :disabled="accepting"
                                                        class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                                        ✔ Chọn gia sư
                                                    </button>
                                                @elseif($application->status === 'accepted')
                                                    <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded">✅ Đã chọn</span>
                                                @elseif($application->status === 'rejected')
                                                    <span class="px-3 py-1 text-sm bg-gray-200 text-gray-600 rounded">❌ Từ chối</span>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li>Không có gia sư nào ứng tuyển.</li>
                                @endforelse
                            </ul>
                        </div>
                        <!-- Actions admin -->
                        <div class="mt-4 space-x-2">
                            @if($statusLabel === 'Draft')
                                <button 
                                    id="btn-{{ $booking->id }}"
                                    @click.prevent="acceptAndComplete({{ $booking->id }}, 'pending')"
                                    :disabled="accepting"
                                    class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
                                    ⬆ Đẩy tin
                                </button>
                            @elseif($statusLabel === 'Published' && $studentSigned && $tutorSigned && !$systemVerified)
                                <button 
                                    id="btn-{{ $booking->id }}"
                                    @click.prevent="acceptAndComplete({{ $booking->id }}, null, true)"
                                    :disabled="accepting"
                                    class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-50">
                                    ✅ Hoàn tất (Closed)
                                </button>
                            @endif

                            @if($statusLabel === 'Published' || $statusLabel === 'Closed')
                                <button 
                                    @click.prevent="acceptAndComplete(
                                        {{ $booking->id }},
                                        'reset',
                                        true,
                                        {{ optional($booking->applications->firstWhere('status','accepted'))->id ?? 'undefined' }},
                                        {{ optional($booking->applications->firstWhere('status','accepted'))->tutor_id ?? 'undefined' }}
                                    )"
                                    :disabled="accepting"
                                    class="px-3 py-1 bg-orange-500 text-white text-sm rounded hover:bg-orange-600">
                                    🔄 Reset tin đăng
                                </button>

                                <button 
                                    @click.prevent="acceptAndComplete({{ $booking->id }}, 'delete', true)"
                                    :disabled="accepting"
                                    class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                    ❌ Huỷ toàn bộ
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Không có tin tuyển nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $bookings->links() }}</div>
</div>
