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
        // Kiểm tra xem trường 'date' có tồn tại trong bảng không
        if (Schema::hasColumn('tutor_availabilities', 'date')) {
            // Thực hiện migration để làm cho trường date nullable
            Schema::table('tutor_availabilities', function (Blueprint $table) {
                $table->date('date')->nullable()->change();
            });
        } else {
            // Thêm trường date nếu không tồn tại
            Schema::table('tutor_availabilities', function (Blueprint $table) {
                $table->date('date')->nullable()->after('day_of_week');
            });
        }

        // Kiểm tra các trường khác mà schema hiện tại có nhưng migration không có
        if (!Schema::hasColumn('tutor_availabilities', 'is_recurring')) {
            Schema::table('tutor_availabilities', function (Blueprint $table) {
                $table->boolean('is_recurring')->default(0)->after('end_time');
            });
        }

        if (!Schema::hasColumn('tutor_availabilities', 'status')) {
            Schema::table('tutor_availabilities', function (Blueprint $table) {
                $table->string('status')->default('available')->after('is_recurring');
            });
        }

        // Đảm bảo day_of_week là chuỗi nếu cần
        if (Schema::hasColumn('tutor_availabilities', 'day_of_week')) {
            Schema::table('tutor_availabilities', function (Blueprint $table) {
                $table->string('day_of_week')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần xóa các trường nếu cần rollback vì chúng có thể cần thiết
    }
};
