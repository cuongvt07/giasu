@props(['tutor', 'subjects'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Môn học giảng dạy</h3>
        </div>
        
        @if($subjects && count($subjects) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($subjects->groupBy('level') as $level => $levelSubjects)
                    <div class="bg-indigo-50 rounded-lg overflow-hidden">
                        <div class="bg-indigo-100 px-4 py-3">
                            <h4 class="font-medium text-indigo-800">{{ $level }}</h4>
                        </div>
                        <div class="p-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach($levelSubjects as $subject)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white text-indigo-700 border border-indigo-200">
                                        {{ $subject->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có môn học</h3>
                <p class="mt-1 text-sm text-gray-500">Gia sư chưa cập nhật thông tin môn học giảng dạy.</p>
            </div>
        @endif

        <div class="mt-8">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Phương pháp giảng dạy</h4>
            
            <div class="bg-gray-50 p-5 rounded-lg">
                @if($tutor->teaching_method)
                    <div class="prose max-w-none">
                        {!! nl2br(e($tutor->teaching_method)) !!}
                    </div>
                @else
                    <p class="text-gray-500 italic">Gia sư chưa cập nhật phương pháp giảng dạy.</p>
                @endif
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-md bg-indigo-100 text-indigo-600">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h5 class="text-lg font-medium text-gray-900">Linh hoạt</h5>
                            <p class="mt-2 text-sm text-gray-500">Điều chỉnh phương pháp giảng dạy phù hợp với từng học sinh</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-md bg-indigo-100 text-indigo-600">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h5 class="text-lg font-medium text-gray-900">Hiệu quả</h5>
                            <p class="mt-2 text-sm text-gray-500">Tập trung vào mục tiêu và kết quả học tập cụ thể</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-md bg-indigo-100 text-indigo-600">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h5 class="text-lg font-medium text-gray-900">Tài liệu phong phú</h5>
                            <p class="mt-2 text-sm text-gray-500">Biên soạn và cung cấp tài liệu học tập phù hợp</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 