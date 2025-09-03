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
        Schema::create('tutor_class_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_level_id')->constrained()->onDelete('cascade');
            $table->decimal('price_per_hour', 10, 2)->nullable(); // Giá riêng cho từng cấp học nếu có
            $table->text('experience_details')->nullable(); // Chi tiết kinh nghiệm dạy ở cấp học này
            $table->timestamps();
            
            $table->unique(['tutor_id', 'class_level_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_class_levels');
    }
};
