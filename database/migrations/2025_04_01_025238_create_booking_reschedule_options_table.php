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
        Schema::create('booking_reschedule_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_reschedule_request_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->boolean('is_selected')->default(false);
            $table->timestamps();

            $table->foreign('booking_reschedule_request_id')
                ->references('id')
                ->on('booking_reschedule_requests')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_reschedule_options');
    }
};
