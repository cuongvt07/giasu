<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('vnp_txn_ref')->unique(); // Mã giao dịch VNPAY
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->default('vnpay');
            $table->string('bank_code')->nullable();
            $table->string('bank_tran_no')->nullable();
            $table->string('card_type')->nullable();
            $table->string('status'); // pending, completed, failed, refunded
            $table->json('response_data')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}; 