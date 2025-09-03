<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Đặt giá trị mặc định và cho phép null
            $table->integer('day_of_week')->nullable()->default(null)->change();
            
            // Cập nhật giá trị day_of_week cho các bản ghi hiện có dựa vào start_time
            DB::statement('UPDATE bookings SET day_of_week = DAYOFWEEK(start_time) - 1 WHERE day_of_week IS NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Quay lại trạng thái trước - yêu cầu bắt buộc phải có giá trị
            $table->integer('day_of_week')->nullable(false)->change();
        });
    }
};
