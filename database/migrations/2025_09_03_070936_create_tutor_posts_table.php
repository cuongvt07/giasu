<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutor_posts', function (Blueprint $table) {
            $table->id();

            // Ai đăng tin
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete()
                  ->restrictOnUpdate();

            // Thông tin nhu cầu
            $table->string('subject', 100);
            $table->string('grade_level', 100)->nullable();
            $table->string('goal', 255)->nullable();
            $table->text('description')->nullable();

            // Ngân sách
            $table->decimal('budget_min', 12, 2)->nullable();
            $table->decimal('budget_max', 12, 2)->nullable();
            $table->enum('budget_unit', ['buổi','giờ','tháng','khóa'])->default('buổi');

            // Lịch học
            $table->tinyInteger('sessions_per_week')->unsigned()->nullable();
            $table->smallInteger('session_length_min')->unsigned()->nullable();
            $table->string('schedule_notes', 255)->nullable();

            // Hình thức học & địa điểm
            $table->enum('mode', ['offline', 'online'])->default('offline');
            $table->string('address_line', 255)->nullable();

            // Thông tin học sinh
            $table->tinyInteger('student_count')->unsigned()->nullable();
            $table->string('student_age', 50)->nullable();
            $table->string('special_notes', 255)->nullable();

            // Yêu cầu gia sư
            $table->string('qualifications', 255)->nullable();
            $table->tinyInteger('min_experience_yr')->unsigned()->nullable();

            // Liên hệ & thời hạn
            $table->string('contact_phone', 30)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->dateTime('deadline_at')->nullable();

            // Trạng thái tin
            $table->enum('status', ['draft', 'published', 'closed'])->default('published');
            $table->dateTime('published_at')->nullable();

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('status', 'idx_tp_status');
            $table->index('subject', 'idx_tp_subject');
            $table->index('mode', 'idx_tp_mode');
            $table->index(['budget_min', 'budget_max'], 'idx_tp_budget');
            $table->index('published_at', 'idx_tp_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_posts');
    }
};
