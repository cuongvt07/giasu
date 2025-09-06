<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->timestamp('signed_student_at')->nullable()->after('status');
            $table->timestamp('signed_tutor_at')->nullable()->after('signed_student_at');
            $table->timestamp('system_verified_at')->nullable()->after('signed_tutor_at');
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn(['signed_student_at', 'signed_tutor_at']);
        });
    }
};
