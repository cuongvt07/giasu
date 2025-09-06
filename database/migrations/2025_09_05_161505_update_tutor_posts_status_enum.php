<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cách 1: Sử dụng raw SQL (Recommended cho MySQL)
        // DB::statement("ALTER TABLE tutor_posts MODIFY COLUMN status ENUM('draft', 'pending', 'published', 'closed') NOT NULL DEFAULT 'draft'");
        
        // Cách 2: Drop và recreate column (nếu cách 1 không work)
        Schema::table('tutor_posts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('tutor_posts', function (Blueprint $table) {
            $table->enum('status', ['draft', 'pending', 'published', 'closed'])->default('draft')->after('deadline_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback về enum cũ
        DB::statement("ALTER TABLE tutor_posts MODIFY COLUMN status ENUM('draft', 'published', 'closed') NOT NULL DEFAULT 'published'");
    }
};