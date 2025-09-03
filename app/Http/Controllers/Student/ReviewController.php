<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Hiển thị form đánh giá gia sư
     */
    public function create(Tutor $tutor)
    {
        // Kiểm tra xem học sinh đã từng học với gia sư này chưa
        $completedBookings = Booking::where('student_id', Auth::id())
            ->where('tutor_id', $tutor->id)
            ->where('status', 'completed')
            ->with('subject')
            ->get();
            
        if ($completedBookings->isEmpty()) {
            return redirect()->back()->with('error', 'Bạn chưa hoàn thành buổi học nào với gia sư này');
        }
        
        // Kiểm tra xem đã đánh giá chưa
        $hasReviewed = Review::where('student_id', Auth::id())
            ->where('tutor_id', $tutor->id)
            ->exists();
            
        if ($hasReviewed) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá gia sư này rồi');
        }
        
        return view('student.reviews.create', compact('tutor', 'completedBookings'));
    }
    
    /**
     * Lưu đánh giá gia sư
     */
    public function store(Request $request, Tutor $tutor)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
            'is_anonymous' => 'sometimes|boolean'
        ], [
            'booking_id.required' => 'Vui lòng chọn buổi học để đánh giá',
            'booking_id.exists' => 'Buổi học đã chọn không hợp lệ',
            'rating.required' => 'Vui lòng chọn số sao đánh giá',
            'rating.integer' => 'Số sao đánh giá phải là số nguyên',
            'rating.min' => 'Số sao đánh giá phải từ 1 đến 5',
            'rating.max' => 'Số sao đánh giá phải từ 1 đến 5',
            'comment.required' => 'Vui lòng nhập nội dung đánh giá',
            'comment.max' => 'Nội dung đánh giá không được vượt quá 500 ký tự',
        ]);
        
        // Kiểm tra booking thuộc về học sinh này và gia sư này
        $booking = Booking::where('id', $validated['booking_id'])
            ->where('student_id', Auth::id())
            ->where('tutor_id', $tutor->id)
            ->where('status', 'completed')
            ->firstOrFail();
        
        // Kiểm tra xem đã đánh giá booking này chưa
        $existingReview = Review::where('booking_id', $booking->id)->exists();
        if ($existingReview) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá buổi học này rồi');
        }
        
        // Tạo đánh giá
        $review = Review::create([
            'student_id' => Auth::id(),
            'tutor_id' => $tutor->id,
            'booking_id' => $validated['booking_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
        ]);
        
        // Cập nhật rating trung bình của gia sư
        $avgRating = $tutor->reviews()->avg('rating');
        $tutor->update(['rating' => $avgRating]);
        
        return redirect()->route('student.bookings.tutors')
            ->with('success', 'Cảm ơn bạn đã đánh giá gia sư!');
    }
} 