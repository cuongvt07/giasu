<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tutor_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_post_id')->constrained('tutor_posts')->cascadeOnDelete();
            $table->foreignId('tutor_id')->constrained('tutors')->cascadeOnDelete();
            $table->string('status', 20)->default('pending')->index();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->unique(['tutor_post_id', 'tutor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_applications');
    }
};
