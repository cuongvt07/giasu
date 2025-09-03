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
        Schema::create('learning_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('student_profiles')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->decimal('performance_score', 5, 2); // Điểm đánh giá (0-100)
            $table->json('strengths'); // Điểm mạnh
            $table->json('weaknesses'); // Điểm yếu
            $table->json('ai_recommendations'); // Đề xuất từ AI
            $table->json('progress_history')->nullable(); // Lịch sử tiến bộ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_analytics');
    }
};
