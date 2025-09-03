<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Booking;
use App\Models\Tutor;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách booking đã hoàn thành
        $completedBookings = Booking::where('status', 'completed')->get();

        foreach ($completedBookings as $booking) {
            // Tạo đánh giá cho mỗi booking đã hoàn thành
            Review::create([
                'student_id' => $booking->student_id,
                'tutor_id' => $booking->tutor_id,
                'booking_id' => $booking->id,
                'rating' => rand(3, 5), // Đánh giá từ 3-5 sao
                'comment' => $this->getRandomComment(),
                'is_anonymous' => rand(0, 1) // Ngẫu nhiên ẩn danh hoặc không
            ]);
        }
    }

    private function getRandomComment()
    {
        $comments = [
            'Gia sư rất nhiệt tình và giảng dạy dễ hiểu.',
            'Tôi đã hiểu bài hơn rất nhiều sau buổi học.',
            'Phương pháp giảng dạy rất hiệu quả.',
            'Gia sư có kiến thức chuyên môn tốt và biết cách truyền đạt.',
            'Con tôi rất thích cách dạy của thầy/cô.',
            'Buổi học rất bổ ích, sẽ học tiếp với gia sư này.',
            'Gia sư rất đúng giờ và chuyên nghiệp.',
            'Kiến thức được truyền đạt một cách logic và dễ hiểu.',
            'Rất hài lòng với chất lượng dạy học.',
            'Gia sư rất kiên nhẫn và tận tâm với học sinh.'
        ];

        return $comments[array_rand($comments)];
    }
} 