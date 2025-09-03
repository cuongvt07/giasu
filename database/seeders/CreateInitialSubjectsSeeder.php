<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateInitialSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Toán học',
                'description' => 'Môn học Toán từ cơ bản đến nâng cao',
                'category' => 'Khoa học tự nhiên',
                'is_active' => true,
            ],
            [
                'name' => 'Vật lý',
                'description' => 'Môn học Vật lý từ cơ bản đến nâng cao',
                'category' => 'Khoa học tự nhiên',
                'is_active' => true,
            ],
            [
                'name' => 'Hóa học',
                'description' => 'Môn học Hóa học từ cơ bản đến nâng cao',
                'category' => 'Khoa học tự nhiên',
                'is_active' => true,
            ],
            [
                'name' => 'Ngữ văn',
                'description' => 'Môn học Ngữ văn từ cơ bản đến nâng cao',
                'category' => 'Khoa học xã hội',
                'is_active' => true,
            ],
            [
                'name' => 'Tiếng Anh',
                'description' => 'Môn học Tiếng Anh từ cơ bản đến nâng cao',
                'category' => 'Ngoại ngữ',
                'is_active' => true,
            ],
        ];
        
        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
