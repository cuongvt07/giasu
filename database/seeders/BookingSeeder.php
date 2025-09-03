<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Tutor;
use App\Models\Subject;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $students = User::whereDoesntHave('tutor')->get();
        $tutors = Tutor::all();
        $subjects = Subject::all();

        // Tạo booking cho mỗi gia sư
        foreach ($tutors as $tutor) {
            // Booking đã hoàn thành
            for ($i = 0; $i < 5; $i++) {
                $startTime = Carbon::now()->subDays(rand(1, 30))->setHour(rand(8, 20))->setMinute(0);
                $endTime = (clone $startTime)->addHours(2);
                $pricePerHour = $tutor->hourly_rate;
                $totalAmount = $pricePerHour * 2; // 2 giờ

                Booking::create([
                    'student_id' => $students->random()->id,
                    'tutor_id' => $tutor->id,
                    'subject_id' => $subjects->random()->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'day_of_week' => $startTime->dayOfWeek,
                    'status' => 'completed',
                    'notes' => 'Buổi học mẫu đã hoàn thành',
                    'price_per_hour' => $pricePerHour,
                    'total_amount' => $totalAmount,
                    'completed_at' => $endTime
                ]);
            }

            // Booking sắp tới
            for ($i = 0; $i < 3; $i++) {
                $startTime = Carbon::now()->addDays(rand(1, 14))->setHour(rand(8, 20))->setMinute(0);
                $endTime = (clone $startTime)->addHours(2);
                $pricePerHour = $tutor->hourly_rate;
                $totalAmount = $pricePerHour * 2; // 2 giờ

                Booking::create([
                    'student_id' => $students->random()->id,
                    'tutor_id' => $tutor->id,
                    'subject_id' => $subjects->random()->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'day_of_week' => $startTime->dayOfWeek,
                    'status' => rand(0, 1) ? 'pending' : 'confirmed',
                    'notes' => 'Buổi học mẫu sắp tới',
                    'price_per_hour' => $pricePerHour,
                    'total_amount' => $totalAmount
                ]);
            }
        }
    }
} 