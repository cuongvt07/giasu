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
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_level_id')->constrained()->onDelete('cascade');
            $table->enum('learning_style', ['visual', 'auditory', 'kinesthetic', 'reading_writing']);
            $table->text('learning_goals');
            $table->json('subjects_need_help'); // Danh sách các môn cần hỗ trợ
            $table->json('preferred_schedule'); // Thời gian học mong muốn
            $table->json('learning_history')->nullable(); // Lịch sử học tập
            $table->text('special_requirements')->nullable(); // Yêu cầu đặc biệt
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
