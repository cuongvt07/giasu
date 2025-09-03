<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tutor;
use App\Models\Subject;
use App\Models\ClassLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateInitialTutorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo một user mẫu và gia sư tương ứng
        $user = User::create([
            'name' => 'Trần Văn A',
            'email' => 'tutor'.time().'@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Tạo gia sư
        $tutor = Tutor::create([
            'user_id' => $user->id,
            'education_level' => 'Thạc sĩ',
            'university' => 'Đại học Sư phạm Hà Nội',
            'major' => 'Toán học',
            'teaching_experience' => 'Có 5 năm kinh nghiệm giảng dạy Toán',
            'bio' => 'Tôi là giáo viên Toán với hơn 5 năm kinh nghiệm. Tôi đã từng dạy tại các trường THPT và có nhiều học sinh đạt kết quả cao trong các kỳ thi quốc gia.',
            'hourly_rate' => 200000,
            'is_verified' => true,
            'status' => 'active',
            'total_teaching_hours' => 120,
            'rating' => 4.8,
        ]);

        // Thêm môn học
        $subjects = Subject::take(3)->get();
        if ($subjects->count() > 0) {
            foreach ($subjects as $subject) {
                $tutor->subjects()->attach($subject->id, [
                    'price_per_hour' => 200000,
                    'experience_details' => 'Kinh nghiệm giảng dạy ' . $subject->name
                ]);
            }
        }

        // Thêm cấp học
        $classLevels = ClassLevel::take(3)->get();
        if ($classLevels->count() > 0) {
            foreach ($classLevels as $level) {
                $tutor->classLevels()->attach($level->id, [
                    'price_per_hour' => 200000,
                    'experience_details' => 'Kinh nghiệm giảng dạy ' . $level->name
                ]);
            }
        }

        // Tạo thêm gia sư thứ 2
        $user2 = User::create([
            'name' => 'Nguyễn Thị B',
            'email' => 'tutor'.(time()+1).'@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Tạo gia sư
        $tutor2 = Tutor::create([
            'user_id' => $user2->id,
            'education_level' => 'Cử nhân',
            'university' => 'Đại học Ngoại ngữ Hà Nội',
            'major' => 'Tiếng Anh',
            'teaching_experience' => 'Có 3 năm kinh nghiệm giảng dạy Tiếng Anh',
            'bio' => 'Tôi là giáo viên Tiếng Anh với nhiều năm kinh nghiệm. Tôi đã từng học tập và làm việc tại Anh, có chứng chỉ IELTS 8.0.',
            'hourly_rate' => 180000,
            'is_verified' => true,
            'status' => 'active',
            'total_teaching_hours' => 80,
            'rating' => 4.5,
        ]);

        // Thêm môn học cho gia sư 2
        if ($subjects->count() > 0) {
            foreach ($subjects->take(2) as $subject) {
                $tutor2->subjects()->attach($subject->id, [
                    'price_per_hour' => 180000,
                    'experience_details' => 'Kinh nghiệm giảng dạy ' . $subject->name
                ]);
            }
        }

        // Thêm cấp học cho gia sư 2
        if ($classLevels->count() > 0) {
            foreach ($classLevels->take(2) as $level) {
                $tutor2->classLevels()->attach($level->id, [
                    'price_per_hour' => 180000,
                    'experience_details' => 'Kinh nghiệm giảng dạy ' . $level->name
                ]);
            }
        }
    }
}
