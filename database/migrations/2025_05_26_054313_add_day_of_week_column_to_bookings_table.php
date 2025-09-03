<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('day_of_week')->nullable()->after('end_time')
                ->comment('0: Chủ Nhật, 1: Thứ Hai, ..., 6: Thứ Bảy');
        });

        // Cập nhật giá trị day_of_week cho các booking đã tồn tại
        $bookings = DB::table('bookings')->get();
        foreach ($bookings as $booking) {
            // Lấy ngày trong tuần từ start_time
            $dayOfWeek = Carbon::parse($booking->start_time)->dayOfWeek;
            
            // Carbon trả về 0 là Chủ Nhật, 1-6 là Thứ Hai đến Thứ Bảy
            DB::table('bookings')
                ->where('id', $booking->id)
                ->update(['day_of_week' => $dayOfWeek]);
        }

        // Đặt cột day_of_week thành not null sau khi đã cập nhật dữ liệu
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('day_of_week')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('day_of_week');
        });
    }
};
