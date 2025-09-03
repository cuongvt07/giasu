<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\TutorEarning;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TutorEarningService
{
    /**
     * Tạo bản ghi thu nhập cho gia sư khi buổi học hoàn thành
     *
     * @param Booking $booking
     * @return TutorEarning|null
     */
    public function createEarningRecord(Booking $booking)
    {
        try {
            // Kiểm tra xem buổi học đã hoàn thành chưa
            if ($booking->status !== 'completed') {
                return null;
            }

            // Kiểm tra xem đã tạo bản ghi thu nhập cho buổi học này chưa
            if ($booking->tutorEarning()->exists()) {
                return $booking->tutorEarning;
            }

            // Tính toán phí và số tiền gia sư nhận được
            $totalAmount = $booking->total_amount;
            $fees = TutorEarning::calculateFees($totalAmount);

            // Tạo bản ghi thu nhập mới
            return DB::transaction(function () use ($booking, $totalAmount, $fees) {
                return TutorEarning::create([
                    'tutor_id' => $booking->tutor_id,
                    'booking_id' => $booking->id,
                    'amount' => $fees['amount'],
                    'platform_fee' => $fees['platform_fee'],
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'notes' => 'Tự động tạo khi buổi học hoàn thành'
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo bản ghi thu nhập cho gia sư', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Cập nhật trạng thái thanh toán cho gia sư
     *
     * @param TutorEarning $earning
     * @param string $status
     * @param string|null $transactionReference
     * @param string|null $notes
     * @return bool
     */
    public function updateEarningStatus(TutorEarning $earning, string $status, ?string $transactionReference = null, ?string $notes = null)
    {
        try {
            $data = ['status' => $status];
            
            if ($status === 'completed') {
                $data['paid_at'] = Carbon::now();
            }
            
            if ($transactionReference) {
                $data['transaction_reference'] = $transactionReference;
            }
            
            if ($notes) {
                $data['notes'] = $notes;
            }
            
            return $earning->update($data);
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật trạng thái thanh toán cho gia sư', [
                'earning_id' => $earning->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Tạo tự động các bản ghi thu nhập cho các buổi học đã hoàn thành nhưng chưa có bản ghi
     *
     * @return array [success_count, failed_count]
     */
    public function processCompletedBookings()
    {
        $successCount = 0;
        $failedCount = 0;

        $completedBookings = Booking::where('status', 'completed')
            ->whereDoesntHave('tutorEarning')
            ->get();

        foreach ($completedBookings as $booking) {
            $result = $this->createEarningRecord($booking);
            if ($result) {
                $successCount++;
            } else {
                $failedCount++;
            }
        }

        return [$successCount, $failedCount];
    }
} 