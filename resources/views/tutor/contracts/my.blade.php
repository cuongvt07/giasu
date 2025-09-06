@extends('layouts.tutor')

@section('content')
<div class="bg-gray-50 min-h-screen">

    <!-- Hero Section -->
    <div class="bg-white border-b border-gray-200 py-10">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 font-serif">Hợp Đồng & Tin Đăng Tôi Đã Apply</h1>
            <p class="mt-3 text-gray-600 max-w-2xl mx-auto">
                Danh sách các hợp đồng bạn tham gia và các tin đăng bạn đã apply nhưng chưa được chọn.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

        <!-- Hợp đồng -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-file-contract text-indigo-500"></i> Danh sách hợp đồng
                </h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full border rounded-lg text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 border text-left">Thông tin tin</th>
                                <th class="px-4 py-2 border text-left">Người học</th>
                                <th class="px-4 py-2 border text-left">Gia sư</th>
                                <th class="px-4 py-2 border text-left">Trạng thái</th>
                                <th class="px-4 py-2 border text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($contracts as $c)
                                <tr @class([
                                    'bg-yellow-50' => !in_array($c->status, ['completed','rejected']),
                                ])>
                                    <td class="px-4 py-2 border">
                                        {{ $c->subject_name ?? '—' }}
                                        @if($c->class_level_name) - {{ $c->class_level_name }} @endif
                                        <div class="text-xs text-gray-500 mt-1">
                                            @if($c->budget_min && $c->budget_max)
                                                {{ number_format($c->budget_min) }} - {{ number_format($c->budget_max) }} / {{ $c->budget_unit }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 border">{{ $c->student_name }}</td>
                                    <td class="px-4 py-2 border">{{ $c->tutor_name }}</td>
                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 rounded text-xs font-medium
                                            @switch($c->status)
                                                @case('pending') bg-gray-100 text-gray-700 @break
                                                @case('student_accepted') bg-blue-100 text-blue-700 @break
                                                @case('tutor_accepted') bg-purple-100 text-purple-700 @break
                                                @case('both_accepted') bg-green-100 text-green-700 @break
                                                @case('completed') bg-green-200 text-green-900 @break
                                                @case('rejected') bg-red-100 text-red-700 @break
                                            @endswitch
                                        ">
                                            {{ ucfirst(str_replace('_', ' ', $c->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border text-center">
                                        <a href="{{ route('contracts.show', $c->id) }}" target="_blank" rel="noopener"
                                           class="text-indigo-600 hover:underline font-medium">Xem chi tiết</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        Bạn chưa có hợp đồng nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tin đã apply nhưng chưa được chọn hoặc bị từ chối -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-file-alt text-indigo-500"></i> Danh sách tin đã apply
                </h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full border rounded-lg text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 border text-left">Thông tin tin</th>
                                <th class="px-4 py-2 border text-left">Người tuyển</th>
                                <th class="px-4 py-2 border text-left">Trạng thái apply</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($applications as $a)
                                <tr @class(['bg-yellow-50' => $a->status=='pending'])>
                                    <td class="px-4 py-2 border">
                                        {{ $a->subject_name ?? '—' }}
                                        @if($a->class_level_name) - {{ $a->class_level_name }} @endif
                                        <div class="text-xs text-gray-500 mt-1">
                                            @if($a->budget_min && $a->budget_max)
                                                {{ number_format($a->budget_min) }} - {{ number_format($a->budget_max) }} / {{ $a->budget_unit }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 border">{{ $a->student_name }}</td>
                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 rounded text-xs font-medium
                                            @switch($a->status)
                                                @case('pending') bg-gray-100 text-gray-700 @break
                                                @case('rejected') bg-red-100 text-red-700 @break
                                            @endswitch
                                        ">
                                            {{ ucfirst($a->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                        Bạn chưa apply tin nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
