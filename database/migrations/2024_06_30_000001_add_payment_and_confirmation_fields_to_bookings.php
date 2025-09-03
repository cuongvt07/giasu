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

            $table->string('admin_notes')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'refunded', 'partial_refunded'])
                ->default('pending')
                ->after('status');
            $table->boolean('student_confirmed')->default(false)->after('admin_notes');
            $table->boolean('tutor_confirmed')->default(false)->after('student_confirmed');
        });

        // Cập nhật enum status để thêm các trạng thái mới
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'completed', 'cancelled', 'in_progress', 'pending_completion') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'student_confirmed', 'tutor_confirmed']);
        });
        
        // Khôi phục enum status về các trạng thái cũ
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending'");
    }
}; 