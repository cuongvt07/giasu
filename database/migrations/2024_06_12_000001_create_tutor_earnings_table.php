<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tutor_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Số tiền gia sư nhận được (90%)
            $table->decimal('platform_fee', 10, 2); // Phí nền tảng (10%)
            $table->decimal('total_amount', 10, 2); // Tổng tiền buổi học (100%)
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('paid_at')->nullable(); // Thời gian thanh toán cho gia sư
            $table->string('transaction_reference')->nullable(); // Mã tham chiếu giao dịch
            $table->text('notes')->nullable(); // Ghi chú về thanh toán
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_earnings');
    }
}; 