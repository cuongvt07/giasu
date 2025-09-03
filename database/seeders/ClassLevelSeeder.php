<?php

namespace Database\Seeders;

use App\Models\ClassLevel;
use Illuminate\Database\Seeder;

class ClassLevelSeeder extends Seeder
{
    public function run(): void
    {
        $classLevels = [
            // Tiểu học
            [
                'name' => 'Lớp 1',
                'education_level' => 'Tiểu học',
                'description' => 'Chương trình lớp 1 theo chuẩn Bộ Giáo dục',
                'order' => 1
            ],
            [
                'name' => 'Lớp 2',
                'education_level' => 'Tiểu học',
                'description' => 'Chương trình lớp 2 theo chuẩn Bộ Giáo dục',
                'order' => 2
            ],
            [
                'name' => 'Lớp 3',
                'education_level' => 'Tiểu học',
                'description' => 'Chương trình lớp 3 theo chuẩn Bộ Giáo dục',
                'order' => 3
            ],
            [
                'name' => 'Lớp 4',
                'education_level' => 'Tiểu học',
                'description' => 'Chương trình lớp 4 theo chuẩn Bộ Giáo dục',
                'order' => 4
            ],
            [
                'name' => 'Lớp 5',
                'education_level' => 'Tiểu học',
                'description' => 'Chương trình lớp 5 theo chuẩn Bộ Giáo dục',
                'order' => 5
            ],
            
            // THCS
            [
                'name' => 'Lớp 6',
                'education_level' => 'THCS',
                'description' => 'Chương trình lớp 6 theo chuẩn Bộ Giáo dục',
                'order' => 6
            ],
            [
                'name' => 'Lớp 7',
                'education_level' => 'THCS',
                'description' => 'Chương trình lớp 7 theo chuẩn Bộ Giáo dục',
                'order' => 7
            ],
            [
                'name' => 'Lớp 8',
                'education_level' => 'THCS',
                'description' => 'Chương trình lớp 8 theo chuẩn Bộ Giáo dục',
                'order' => 8
            ],
            [
                'name' => 'Lớp 9',
                'education_level' => 'THCS',
                'description' => 'Chương trình lớp 9 theo chuẩn Bộ Giáo dục',
                'order' => 9
            ],
            
            // THPT
            [
                'name' => 'Lớp 10',
                'education_level' => 'THPT',
                'description' => 'Chương trình lớp 10 theo chuẩn Bộ Giáo dục',
                'order' => 10
            ],
            [
                'name' => 'Lớp 11',
                'education_level' => 'THPT',
                'description' => 'Chương trình lớp 11 theo chuẩn Bộ Giáo dục',
                'order' => 11
            ],
            [
                'name' => 'Lớp 12',
                'education_level' => 'THPT',
                'description' => 'Chương trình lớp 12 theo chuẩn Bộ Giáo dục',
                'order' => 12
            ],
        ];

        foreach ($classLevels as $level) {
            ClassLevel::create($level);
        }
    }
} 