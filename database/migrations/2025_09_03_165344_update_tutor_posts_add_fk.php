<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tutor_posts', function (Blueprint $table) {
            // Xóa cột text cũ
            if (Schema::hasColumn('tutor_posts', 'subject')) {
                $table->dropColumn('subject');
            }
            if (Schema::hasColumn('tutor_posts', 'grade_level')) {
                $table->dropColumn('grade_level');
            }

            // Thêm khóa ngoại mới
            $table->foreignId('subject_id')
                ->nullable()
                ->after('user_id')
                ->constrained('subjects')
                ->cascadeOnDelete()
                ->restrictOnUpdate();

            // Thêm class_level_id (cũng nullable)
            $table->foreignId('class_level_id')
                ->nullable()
                ->after('subject_id')
                ->constrained('class_levels')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
            });
    }

    public function down(): void
    {
        Schema::table('tutor_posts', function (Blueprint $table) {
            // Xóa khóa ngoại
            $table->dropConstrainedForeignId('subject_id');
            $table->dropConstrainedForeignId('class_level_id');

            // Thêm lại cột text cũ
            $table->string('subject', 100)->after('user_id');
            $table->string('grade_level', 100)->nullable()->after('subject');
        });
    }
};
