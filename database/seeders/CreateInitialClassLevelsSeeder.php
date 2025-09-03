<?php

namespace Database\Seeders;

use App\Models\ClassLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateInitialClassLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classLevels = [
            [
                'name' => 'Tiểu học',
                'description' => 'Cấp học dành cho học sinh từ lớp 1 đến lớp 5',
                'education_level' => 'Tiểu học',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'THCS',
                'description' => 'Cấp học dành cho học sinh từ lớp 6 đến lớp 9',
                'education_level' => 'Trung học cơ sở',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'THPT',
                'description' => 'Cấp học dành cho học sinh từ lớp 10 đến lớp 12',
                'education_level' => 'Trung học phổ thông',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Đại học',
                'description' => 'Cấp học dành cho sinh viên đại học',
                'education_level' => 'Đại học',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Sau đại học',
                'description' => 'Cấp học dành cho học viên cao học và nghiên cứu sinh',
                'education_level' => 'Sau đại học',
                'order' => 5,
                'is_active' => true,
            ],
        ];
        
        foreach ($classLevels as $level) {
            ClassLevel::create($level);
        }
    }
}
