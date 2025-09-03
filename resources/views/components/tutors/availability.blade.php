@props(['tutor', 'schedules' => null, 'availabilities' => null])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Lịch dạy</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Lịch dạy -->
            <div>
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Lịch dạy thường xuyên
                </h4>
                
                @if($schedules && count($schedules) > 0)
                    <div class="overflow-hidden bg-gray-50 border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thứ</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $schedulesByDay = $schedules->groupBy('day_of_week');
                                    $dayNames = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
                                @endphp
                                
                                @for($day = 0; $day <= 6; $day++)
                                    @php
                                        $daySchedules = $schedulesByDay->get($day);
                                        $dayName = $dayNames[$day];
                                    @endphp
                                    
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $dayName }}
                                        </td>
                                        
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            @if($daySchedules && count($daySchedules) > 0)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($daySchedules as $schedule)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400">Không có lịch</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-6 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-10 w-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có lịch dạy</h3>
                        <p class="mt-1 text-sm text-gray-500">Gia sư chưa cập nhật lịch dạy thường xuyên.</p>
                    </div>
                @endif

                <!-- Lịch rảnh sắp tới -->
                <h4 class="text-lg font-semibold text-gray-800 mt-8 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Lịch rảnh sắp tới
                </h4>

                @if($availabilities && count($availabilities) > 0)
                    <div class="overflow-hidden bg-gray-50 border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $dayNamesFullEng = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                    $dayNamesFull = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
                                    
                                    // Sắp xếp lịch theo ngày
                                    $groupedAvailabilities = [];
                                    
                                    foreach ($availabilities as $availability) {
                                        if ($availability->date) {
                                            // Sử dụng ngày cụ thể
                                            $dateKey = $availability->date->format('Y-m-d');
                                            
                                            if (!isset($groupedAvailabilities[$dateKey])) {
                                                $groupedAvailabilities[$dateKey] = [
                                                    'date' => $availability->date,
                                                    'day_of_week' => $availability->day_of_week,
                                                    'items' => []
                                                ];
                                            }
                                            
                                            $groupedAvailabilities[$dateKey]['items'][] = $availability;
                                        } else {
                                            // Ngày hiện tại + lặp qua 7 ngày tới để tìm ngày thích hợp
                                            $today = \Carbon\Carbon::now();
                                            $dayOfWeek = $availability->day_of_week;
                                            
                                            // Tìm ngày tiếp theo có thứ tương ứng
                                            $daysToAdd = ($dayOfWeek - $today->dayOfWeek + 7) % 7;
                                            if ($daysToAdd == 0) $daysToAdd = 7; // Nếu là thứ của hôm nay, lấy ngày của tuần sau
                                            
                                            $nextDate = $today->copy()->addDays($daysToAdd);
                                            $dateKey = $nextDate->format('Y-m-d');
                                            
                                            if (!isset($groupedAvailabilities[$dateKey])) {
                                                $groupedAvailabilities[$dateKey] = [
                                                    'date' => $nextDate,
                                                    'day_of_week' => $dayOfWeek,
                                                    'items' => []
                                                ];
                                            }
                                            
                                            $groupedAvailabilities[$dateKey]['items'][] = $availability;
                                        }
                                    }
                                    
                                    // Sắp xếp theo ngày
                                    ksort($groupedAvailabilities);
                                @endphp
                                
                                @foreach($groupedAvailabilities as $dateKey => $group)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $group['date']->format('d/m/Y') }}
                                            <div class="text-xs text-gray-500">
                                                {{ $dayNamesFull[$group['day_of_week']] }}
                                            </div>
                                        </td>
                                        
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($group['items'] as $availability)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $availability->start_time->format('H:i') }} - {{ $availability->end_time->format('H:i') }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-6 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-10 w-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có lịch rảnh</h3>
                        <p class="mt-1 text-sm text-gray-500">Gia sư chưa cập nhật lịch rảnh sắp tới.</p>
                    </div>
                @endif

                <div class="mt-3 text-sm text-gray-500 italic">
                    * Lịch có thể thay đổi, vui lòng liên hệ gia sư để xác nhận lịch trước khi đặt.
                </div>
            </div>
          
        </div>
    </div>
</div> 