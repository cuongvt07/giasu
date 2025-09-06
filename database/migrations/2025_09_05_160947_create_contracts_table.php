<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tutor_post_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('tutor_id');

            // trạng thái hợp đồng
            $table->enum('status', [
                'pending',          // cả 2 bên chưa đồng ý
                'student_accepted', // học sinh đồng ý
                'tutor_accepted',   // gia sư đồng ý
                'both_accepted',    // cả 2 đồng ý, chờ admin duyệt
                'completed',        // admin duyệt xong
                'rejected'          // từ chối
            ])->default('pending');

            $table->timestamps();

            // Khóa ngoại
            $table->foreign('tutor_post_id')->references('id')->on('tutor_posts')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tutor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
