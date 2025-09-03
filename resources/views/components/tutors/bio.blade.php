@props(['tutor'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">Giới thiệu</h3>
        </div>
        
        <div class="prose max-w-none">
            @if($tutor->bio)
                {!! nl2br(e($tutor->bio)) !!}
            @else
                <p class="text-gray-500 italic">Gia sư chưa cập nhật thông tin giới thiệu.</p>
            @endif
        </div>

        <div class="mt-8">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Thông tin chi tiết</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Thông tin học vấn và chuyên môn -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h5 class="font-medium text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Học vấn & Chuyên môn
                    </h5>
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="text-gray-600 w-32">Trình độ:</span>
                            <span class="text-gray-900 font-medium">{{ $tutor->education_level }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Trường học:</span>
                            <span class="text-gray-900 font-medium">{{ $tutor->university }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Chuyên ngành:</span>
                            <span class="text-gray-900 font-medium">{{ $tutor->major }}</span>
                        </div>
                    </div>
                </div>

                <!-- Kinh nghiệm giảng dạy -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h5 class="font-medium text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Kinh nghiệm giảng dạy
                    </h5>
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="text-gray-600 w-32">Kinh nghiệm:</span>
                            <span class="text-gray-900 font-medium">{{ $tutor->teaching_experience }} năm</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Số buổi đã dạy:</span>
                            <span class="text-gray-900 font-medium">{{ $tutor->bookings_count ?? 0 }} buổi</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Học sinh:</span>
                            <span class="text-gray-900 font-medium">{{ $tutor->students_count ?? 0 }} học sinh</span>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 