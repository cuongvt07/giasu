<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tutor;
use App\Models\Subject;
use App\Models\ClassLevel;
use Illuminate\Database\Seeder;

class TutorSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy 10 user đầu tiên để tạo gia sư
        $users = User::where('is_admin', false)
            ->take(10)
            ->get();

        // Tạo hồ sơ gia sư cho mỗi user
        foreach ($users as $user) {
            $tutor = Tutor::create([
                'user_id' => $user->id,
                'education_level' => $this->getRandomEducationLevel(),
                'university' => 'Đại học ' . $this->getRandomUniversity(),
                'major' => $this->getRandomMajor(),
                'teaching_experience' => 'Có ' . rand(1, 5) . ' năm kinh nghiệm giảng dạy',
                'bio' => 'Là một giáo viên nhiệt tình, tâm huyết với nghề giảng dạy. Luôn cố gắng truyền đạt kiến thức một cách dễ hiểu nhất cho học sinh.',
                'hourly_rate' => rand(150000, 300000),
                'is_verified' => true,
                'status' => 'active',
            ]);
        }

        // Gán môn học và cấp học cho các gia sư
        $tutors = Tutor::all();
        $subjects = Subject::all();
        $classLevels = ClassLevel::all();

        foreach ($tutors as $tutor) {
            // Gán môn học ngẫu nhiên cho gia sư
            $randomSubjects = $subjects->random(rand(2, 4));
            foreach ($randomSubjects as $subject) {
                $tutor->subjects()->attach($subject->id, [
                    'price_per_hour' => rand(150000, 300000),
                    'experience_details' => 'Có kinh nghiệm giảng dạy ' . $subject->name . ' cho học sinh các cấp'
                ]);
            }

            // Gán cấp học ngẫu nhiên cho gia sư
            $randomClassLevels = $classLevels->random(rand(3, 6));
            foreach ($randomClassLevels as $level) {
                $tutor->classLevels()->attach($level->id, [
                    'price_per_hour' => rand(150000, 300000),
                    'experience_details' => 'Kinh nghiệm giảng dạy ' . $level->name
                ]);
            }
        }
    }

    private function getRandomEducationLevel()
    {
        $levels = ['Đại học', 'Thạc sĩ', 'Tiến sĩ', 'Giáo viên', 'Sinh viên năm cuối'];
        return $levels[array_rand($levels)];
    }

    private function getRandomUniversity()
    {
        $universities = [
            'Quốc gia Hà Nội',
            'Bách khoa Hà Nội',
            'Sư phạm Hà Nội',
            'Ngoại thương',
            'Kinh tế Quốc dân'
        ];
        return $universities[array_rand($universities)];
    }

    private function getRandomMajor()
    {
        $majors = [
            'Công nghệ thông tin',
            'Toán học',
            'Vật lý',
            'Hóa học',
            'Ngôn ngữ Anh',
            'Sư phạm Toán',
            'Sư phạm Văn',
            'Sư phạm Lý'
        ];
        return $majors[array_rand($majors)];
    }
} 