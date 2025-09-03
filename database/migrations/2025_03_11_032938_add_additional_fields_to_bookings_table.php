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
            $table->text('completion_notes')->nullable()->after('notes');
            $table->text('cancelled_reason')->nullable()->after('completion_notes');
            $table->string('cancelled_by')->nullable()->after('cancelled_reason');
            $table->integer('refund_percentage')->nullable()->after('cancelled_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'completion_notes',
                'cancelled_reason',
                'cancelled_by',
                'refund_percentage'
            ]);
        });
    }
};
