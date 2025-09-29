<div class="p-4" style="max-height: 480px; overflow-y: auto;">
    <h3 class="text-base font-semibold mb-2">{{ $label }}</h3>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mã HĐ</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tin Đăng</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Phụ Huynh</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Gia Sư</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Trạng Thái</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ngày Tạo</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Thời Gian Bên A ký</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Thời Gian Bên B ký</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Thời Gian QTV ký</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($contracts as $contract)
            @php
                $signedStudent = $contract->signed_student_at ? ( $contract->signed_student_at instanceof \Carbon\Carbon ? $contract->signed_student_at : (\Illuminate\Support\Str::contains($contract->signed_student_at, '-') ? \Carbon\Carbon::parse($contract->signed_student_at) : null ) ) : null;
                $signedTutor = $contract->signed_tutor_at ? ( $contract->signed_tutor_at instanceof \Carbon\Carbon ? $contract->signed_tutor_at : (\Illuminate\Support\Str::contains($contract->signed_tutor_at, '-') ? \Carbon\Carbon::parse($contract->signed_tutor_at) : null ) ) : null;
                $systemVerified = $contract->system_verified_at ? ( $contract->system_verified_at instanceof \Carbon\Carbon ? $contract->system_verified_at : (\Illuminate\Support\Str::contains($contract->system_verified_at, '-') ? \Carbon\Carbon::parse($contract->system_verified_at) : null ) ) : null;
            @endphp
            <tr>
                <td class="px-4 py-2 whitespace-nowrap">
                    @if($contract->id)
                        <a href="{{ route('contracts.show', $contract->id) }}" class="text-blue-600 hover:underline">#{{ $contract->id }}</a>
                    @else
                        -
                    @endif
                </td>
                <td class="px-4 py-2 whitespace-nowrap">
                    @if($contract->tutorPost && $contract->tutorPost->id)
                        <a href="{{ route('tutors.jobs.post') }}" class="text-blue-600 hover:underline">{{ $contract->tutorPost->id }}</a>
                    @else
                        -
                    @endif
                </td>
                <td class="px-4 py-2 whitespace-nowrap">{{ $contract->student->name ?? '-' }}</td>
                <td class="px-4 py-2 whitespace-nowrap">{{ $contract->tutor->name ?? '-' }}</td>
                <td class="px-4 py-2 whitespace-nowrap">
                    @php
                        $statusMap = [
                            'pending' => 'Chờ các bên ký',
                            'student_accepted' => 'Phụ huynh đã ký',
                            'tutor_accepted' => 'Gia sư đã ký',
                            'both_accepted' => 'Cả 2 bên đã ký',
                            'completed' => 'Hợp đồng có hiệu lực',
                            'rejected' => 'Từ chối',
                        ];
                    @endphp
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($contract->status === 'completed') bg-green-100 text-green-800
                        @elseif($contract->status === 'rejected') bg-red-100 text-red-800
                        @elseif($contract->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $statusMap[$contract->status] ?? $contract->status }}
                    </span>
                </td>
                <td class="px-4 py-2 whitespace-nowrap">
                    @php
                        $createdAt = $contract->created_at instanceof \Carbon\Carbon ? $contract->created_at : (\Illuminate\Support\Str::contains($contract->created_at, '-') ? \Carbon\Carbon::parse($contract->created_at) : null);
                    @endphp
                    {{ $createdAt ? $createdAt->format('d/m/Y H:i') : '-' }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap">
                    {{ $signedStudent ? $signedStudent->format('d/m/Y H:i') : '-' }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap">
                    {{ $signedTutor ? $signedTutor->format('d/m/Y H:i') : '-' }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap">
                    {{ $systemVerified ? $systemVerified->format('d/m/Y H:i') : '-' }}
                </td>   
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-2 text-center text-gray-500">Không có hợp đồng nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
