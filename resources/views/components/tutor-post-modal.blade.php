{{-- Modal Đăng tin tìm gia sư --}}
@php
    use App\Models\Subject;
    use App\Models\ClassLevel;

    $subjects = Subject::where('is_active', true)->get();
    $classLevels = ClassLevel::where('is_active', true)->get();
@endphp
<div x-show="openPost"
     x-transition:enter="transition-all ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-all ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-md p-4"
     @keydown.escape.window="openPost=false"
     @click.self="openPost=false"
     style="display: none; backdrop-filter: blur(5px);"
     x-data="{
         currentStep: 1,
         totalSteps: 5,
         formData: {
             subject: '',
             grade_level: '',
             goal: '',
             description: '',
             budget_min: '',
             budget_max: '',
             budget_unit: 'buoi',
             sessions_per_week: '',
             session_length_min: '',
             schedule_notes: '',
             mode: 'offline',
             address_line: '',
             student_count: 1,
             student_age: '',
             special_notes: '',
             qualifications: '',
             min_experience_yr: '',
             contact_phone: '',
             contact_email: '',
             deadline_at: ''
         },
         nextStep() {
             if(this.currentStep < this.totalSteps) this.currentStep++;
         },
         prevStep() {
             if(this.currentStep > 1) this.currentStep--;
         },
         getStepTitle(step) {
             const titles = {
                 1: 'Thông tin môn học',
                 2: 'Lịch học & Địa điểm',
                 3: 'Thông tin học sinh',
                 4: 'Liên hệ & Yêu cầu',
                 5: 'Xem trước & Hoàn tất'
             };
             return titles[step];
         }
     }">

    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden"
         x-transition:enter="transition-all ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         style="max-height: 90vh;">

        {{-- Header với Progress Bar --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold flex items-center gap-3">
                    <i class="fas fa-chalkboard-teacher"></i>
                    Đăng tin tìm gia sư
                </h3>
                <button @click="openPost=false"
                        class="text-white/80 hover:text-white hover:bg-white/20 rounded-full w-8 h-8 flex items-center justify-center transition-all">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            {{-- Progress Bar --}}
            <div class="flex items-center justify-between mb-2">
                <template x-for="step in totalSteps" :key="step">
                    <div class="flex items-center" :class="step < totalSteps ? 'flex-1' : ''">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-medium transition-all duration-300"
                             :class="step <= currentStep ? 'bg-white text-indigo-600' : 'bg-white/20 text-white/70'">
                            <span x-text="step"></span>
                        </div>
                        <div x-show="step < totalSteps" 
                             class="h-1 flex-1 mx-2 rounded-full transition-all duration-300"
                             :class="step < currentStep ? 'bg-white' : 'bg-white/20'"></div>
                    </div>
                </template>
            </div>
            <p class="text-white/90 text-sm" x-text="getStepTitle(currentStep)"></p>
        </div>

        {{-- Form Content --}}
        <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 200px);">
            <form method="POST" action="{{ route('student.jobs.store') }}" class="space-y-6">
                @csrf

                {{-- Bước 1: Thông tin môn học --}}
                <div x-show="currentStep === 1" 
                    x-transition:enter="transition-all ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Môn học --}}
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-book text-indigo-500 mr-2"></i>Môn học *
                            </label>
                        <select name="subject_id"
                                x-model="formData.subject_id"
                                @change="formData.subject_name = $event.target.options[$event.target.selectedIndex].text"
                                required
                                class="w-full border-2 border-gray-200 rounded-lg px-4 py-3">
                            <option value="">-- Chọn môn học --</option>
                            @foreach ($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                        </div>

                        {{-- Lớp/Trình độ --}}
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-graduation-cap text-indigo-500 mr-2"></i>Lớp/Trình độ
                            </label>
                                <select name="class_level_id"
                                        x-model="formData.class_level_id"
                                        @change="formData.class_level_name = $event.target.options[$event.target.selectedIndex].text"
                                        class="w-full border-2 border-gray-200 rounded-lg px-4 py-3">
                                    <option value="">-- Chọn lớp/trình độ --</option>
                                    @foreach ($classLevels as $cl)
                                        <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>

                    {{-- Mục tiêu học tập --}}
                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-target text-indigo-500 mr-2"></i>Mục tiêu học tập
                        </label>
                        <input name="goal" 
                            x-model="formData.goal"
                            class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 
                                    focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            placeholder="VD: Thi vào 10, IELTS 6.5, Nâng cao điểm số..." />
                    </div>

                    {{-- Mô tả --}}
                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left text-indigo-500 mr-2"></i>Mô tả chi tiết
                        </label>
                        <textarea name="description" 
                                x-model="formData.description"
                                rows="4"
                                class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 
                                        focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"
                                placeholder="Mô tả về trình độ hiện tại của học sinh, mong muốn cụ thể..."></textarea>
                    </div>

                    {{-- Ngân sách --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-dollar-sign text-green-500 mr-2"></i>Ngân sách
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Tối thiểu</label>
                                <input type="number" 
                                    name="budget_min" 
                                    x-model="formData.budget_min"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 
                                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="VD: 150000" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Tối đa</label>
                                <input type="number" 
                                    name="budget_max" 
                                    x-model="formData.budget_max"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 
                                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="VD: 200000" />
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Đơn vị</label>
                                <select name="budget_unit" 
                                        x-model="formData.budget_unit"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 
                                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="buoi">/ buổi học</option>
                                    <option value="gio">/ giờ học</option>
                                    <option value="thang">/ tháng</option>
                                    <option value="khoa">/ khóa học</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bước 2: Lịch học & Địa điểm --}}
                <div x-show="currentStep === 2" 
                     x-transition:enter="transition-all ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-4"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-week text-indigo-500 mr-2"></i>Số buổi/tuần
                            </label>
                            <input type="number" 
                                   name="sessions_per_week" 
                                   x-model="formData.sessions_per_week"
                                   min="1" max="7"
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                   placeholder="VD: 2" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-clock text-indigo-500 mr-2"></i>Thời lượng (phút/buổi)
                            </label>
                            <select name="session_length_min" 
                                    x-model="formData.session_length_min"
                                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 
                                        focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <option value="">-- Chọn thời lượng --</option>
                                <option value="30">30 phút</option>
                                <option value="45">45 phút</option>
                                <option value="60">60 phút (1 giờ)</option>
                                <option value="90">90 phút (1 giờ 30 phút)</option>
                                <option value="120">120 phút (2 giờ)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-schedule text-indigo-500 mr-2"></i>Ghi chú về lịch học
                        </label>
                        <textarea name="schedule_notes" 
                                  x-model="formData.schedule_notes"
                                  rows="3"
                                  class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"
                                  placeholder="VD: Tối thứ 2, 4, 6 từ 19h-20h30..."></textarea>
                    </div>

                    {{-- Hình thức học --}}
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-laptop text-blue-500 mr-2"></i>Hình thức học
                        </h4>
                        <div class="flex gap-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" 
                                       name="mode" 
                                       value="offline" 
                                       x-model="formData.mode"
                                       class="text-indigo-600 focus:ring-indigo-500" />
                                <span class="ml-2 text-gray-700">
                                    <i class="fas fa-home mr-1"></i>Học tại nhà
                                </span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" 
                                       name="mode" 
                                       value="online" 
                                       x-model="formData.mode"
                                       class="text-indigo-600 focus:ring-indigo-500" />
                                <span class="ml-2 text-gray-700">
                                    <i class="fas fa-video mr-1"></i>Học online
                                </span>
                            </label>
                        </div>
                    </div>

                    <div x-show="formData.mode === 'offline'">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>Địa chỉ học
                        </label>
                        <input name="address_line" 
                               x-model="formData.address_line"
                               class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                               placeholder="Địa chỉ cụ thể..." />
                    </div>
                </div>

                {{-- Bước 3: Thông tin học sinh --}}
                <div x-show="currentStep === 3" 
                     x-transition:enter="transition-all ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-4"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-users text-indigo-500 mr-2"></i>Số học sinh
                            </label>
                            <input type="number" 
                                   name="student_count" 
                                   x-model="formData.student_count"
                                   min="1"
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-child text-indigo-500 mr-2"></i>Tuổi học sinh
                            </label>
                            <input name="student_age" 
                                   x-model="formData.student_age"
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                   placeholder="VD: 12 hoặc 10-12" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sticky-note text-indigo-500 mr-2"></i>Ghi chú đặc biệt về học sinh
                        </label>
                        <textarea name="special_notes" 
                                  x-model="formData.special_notes"
                                  rows="3"
                                  class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"
                                  placeholder="VD: Học sinh hơi nhút nhát, cần gia sư kiên nhẫn..."></textarea>
                    </div>
                </div>

                {{-- Bước 4: Liên hệ & Yêu cầu --}}
                <div x-show="currentStep === 4" 
                     x-transition:enter="transition-all ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-4"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone text-green-500 mr-2"></i>Số điện thoại *
                            </label>
                            <input name="contact_phone" 
                                   x-model="formData.contact_phone"
                                   required
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                   placeholder="0987654321" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-blue-500 mr-2"></i>Email
                            </label>
                            <input type="email" 
                                   name="contact_email" 
                                   x-model="formData.contact_email"
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                   placeholder="email@example.com" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-times text-red-500 mr-2"></i>Hạn chót nhận hồ sơ
                        </label>
                        <input type="date" 
                               name="deadline_at" 
                               x-model="formData.deadline_at"
                               class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
                    </div>

                    {{-- Yêu cầu gia sư --}}
                    <div class="bg-amber-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-tie text-amber-600 mr-2"></i>Yêu cầu với gia sư
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Bằng cấp/Chứng chỉ</label>
                                <input name="qualifications" 
                                       x-model="formData.qualifications"
                                       class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="VD: Sinh viên, Cử nhân, Thạc sĩ..." />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Kinh nghiệm tối thiểu (năm)</label>
                                <input type="number" 
                                       name="min_experience_yr" 
                                       x-model="formData.min_experience_yr"
                                       min="0"
                                       class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="VD:1" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bước 5: Xem trước & Hoàn tất --}}
                <div x-show="currentStep === 5" 
                     x-transition:enter="transition-all ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-4"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="space-y-6">

                    {{-- Xem trước tin đăng --}}
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-6 border-2 border-indigo-100">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                            <i class="fas fa-eye text-indigo-600 mr-2"></i>Xem trước tin đăng
                        </h4>
                        <div class="space-y-4 text-sm">
                            <div class="border-b pb-2">
                                <h5 class="font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-book text-indigo-500 mr-2"></i>Thông tin môn học
                                </h5>
                                <p><strong>Môn học:</strong> <span x-text="formData.subject_name || 'Chưa nhập'"></span></p>
                                <p><strong>Lớp/Trình độ:</strong> <span x-text="formData.class_level_name || 'Chưa nhập'"></span></p>
                                <p><strong>Mục tiêu học tập:</strong> <span x-text="formData.goal || 'Chưa nhập'"></span></p>
                                <p><strong>Mô tả chi tiết:</strong> <span x-text="formData.description || 'Chưa nhập'"></span></p>
                                <p><strong>Ngân sách:</strong> 
                                    <span x-text="(formData.budget_min ? formData.budget_min + ' - ' : '') + (formData.budget_max || 'Chưa nhập') + ' /' + (formData.budget_unit === 'buổi' ? 'buổi' : formData.budget_unit === 'giờ' ? 'giờ' : formData.budget_unit === 'tháng' ? 'tháng' : 'khóa')"></span>
                                </p>
                            </div>

                            <div class="border-b pb-2">
                                <h5 class="font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-calendar-week text-indigo-500 mr-2"></i>Lịch học & Địa điểm
                                </h5>
                                <p><strong>Số buổi/tuần:</strong> <span x-text="formData.sessions_per_week || 'Chưa nhập'"></span></p>
                                <p><strong>Thời lượng (phút/buổi):</strong> <span x-text="formData.session_length_min || 'Chưa nhập'"></span></p>
                                <p><strong>Ghi chú lịch học:</strong> <span x-text="formData.schedule_notes || 'Chưa nhập'"></span></p>
                                <p><strong>Hình thức học:</strong> <span x-text="formData.mode === 'offline' ? 'Học tại nhà' : 'Học online'"></span></p>
                                <p x-show="formData.mode === 'offline'"><strong>Địa chỉ học:</strong> <span x-text="formData.address_line || 'Chưa nhập'"></span></p>
                            </div>

                            <div class="border-b pb-2">
                                <h5 class="font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-users text-indigo-500 mr-2"></i>Thông tin học sinh
                                </h5>
                                <p><strong>Số học sinh:</strong> <span x-text="formData.student_count || 'Chưa nhập'"></span></p>
                                <p><strong>Tuổi học sinh:</strong> <span x-text="formData.student_age || 'Chưa nhập'"></span></p>
                                <p><strong>Ghi chú đặc biệt:</strong> <span x-text="formData.special_notes || 'Chưa nhập'"></span></p>
                            </div>

                            <div class="border-b pb-2">
                                <h5 class="font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-user-tie text-amber-600 mr-2"></i>Yêu cầu gia sư
                                </h5>
                                <p><strong>Bằng cấp/Chứng chỉ:</strong> <span x-text="formData.qualifications || 'Chưa nhập'"></span></p>
                                <p><strong>Kinh nghiệm tối thiểu (năm):</strong> <span x-text="formData.min_experience_yr || 'Chưa nhập'"></span></p>
                            </div>

                            <div>
                                <h5 class="font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-phone text-green-500 mr-2"></i>Thông tin liên hệ
                                </h5>
                                <p><strong>Số điện thoại:</strong> <span x-text="formData.contact_phone || 'Chưa nhập'"></span></p>
                                <p><strong>Email:</strong> <span x-text="formData.contact_email || 'Chưa nhập'"></span></p>
                                <p><strong>Hạn chót nhận hồ sơ:</strong> <span x-text="formData.deadline_at || 'Chưa nhập'"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="status" value="draft">

                {{-- Error messages --}}
                @if ($errors->any())
                    <div class="rounded-lg bg-red-50 p-4 border-2 border-red-200">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-red-400 mr-3 mt-1"></i>
                            <div class="text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Navigation buttons --}}
                <div class="flex items-center justify-between pt-6 border-t-2 border-gray-100">
                    <button type="button"
                            @click="prevStep()"
                            x-show="currentStep > 1"
                            class="inline-flex items-center px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-all font-medium">
                        <i class="fas fa-chevron-left mr-2"></i>
                        Quay lại
                    </button>

                    <div class="flex items-center gap-3">
                        <button type="button"
                                @click="openPost=false"
                                class="px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-all font-medium">
                            Hủy
                        </button>

                        <button type="button"
                                @click="nextStep()"
                                x-show="currentStep < totalSteps"
                                class="inline-flex items-center px-6 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 transition-all font-medium shadow-lg">
                            Tiếp tục
                            <i class="fas fa-chevron-right ml-2"></i>
                        </button>

                        <button type="submit"
                                x-show="currentStep === totalSteps"
                                class="inline-flex items-center px-6 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 transition-all font-medium shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Đăng tin
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- CSS cho animation và styling --}}
<style>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.modal-backdrop {
    backdrop-filter: blur(8px);
}

/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>