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
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->boolean('is_completed')->default(false)->after('lesson_id');
        });
        
        // Update existing records: mark as completed if completed_at is not null
        DB::table('lesson_progress')
            ->whereNotNull('completed_at')
            ->update(['is_completed' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropColumn('is_completed');
        });
    }
};
