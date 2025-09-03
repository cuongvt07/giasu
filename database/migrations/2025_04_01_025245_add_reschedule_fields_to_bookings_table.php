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
        Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('reschedule_requested')->default(false)->after('status');
            $table->timestamp('rescheduled_at')->nullable()->after('completed_at');
            $table->text('rescheduled_reason')->nullable()->after('rescheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('reschedule_requested');
            $table->dropColumn('rescheduled_at');
            $table->dropColumn('rescheduled_reason');
        });
    }
};
