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
        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('education_level');
            $table->string('university')->nullable();
            $table->string('major')->nullable();
            $table->text('teaching_experience');
            $table->text('bio');
            $table->string('avatar')->nullable();
            $table->string('certification_files')->nullable();
            $table->decimal('hourly_rate', 10, 2);
            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->json('teaching_locations')->nullable();
            $table->boolean('can_teach_online')->default(true);
            $table->integer('total_teaching_hours')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};
