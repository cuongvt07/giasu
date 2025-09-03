<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Toán',
                'category' => 'Khoa học tự nhiên',
                'description' => 'Toán học cơ bản và nâng cao'
            ],
            [
                'name' => 'Vật lý',
                'category' => 'Khoa học tự nhiên',
                'description' => 'Vật lý từ cơ bản đến nâng cao'
            ],
            [
                'name' => 'Hóa học',
                'category' => 'Khoa học tự nhiên',
                'description' => 'Hóa học cơ bản và nâng cao'
            ],
            [
                'name' => 'Sinh học',
                'category' => 'Khoa học tự nhiên',
                'description' => 'Sinh học từ cơ bản đến nâng cao'
            ],
            [
                'name' => 'Ngữ văn',
                'category' => 'Khoa học xã hội',
                'description' => 'Văn học Việt Nam và thế giới'
            ],
            [
                'name' => 'Lịch sử',
                'category' => 'Khoa học xã hội',
                'description' => 'Lịch sử Việt Nam và thế giới'
            ],
            [
                'name' => 'Địa lý',
                'category' => 'Khoa học xã hội',
                'description' => 'Địa lý tự nhiên và kinh tế xã hội'
            ],
            [
                'name' => 'Tiếng Anh',
                'category' => 'Ngoại ngữ',
                'description' => 'Tiếng Anh giao tiếp và học thuật'
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
} 