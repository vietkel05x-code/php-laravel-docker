<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('lesson_id');
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            
            $table->unique(['user_id', 'lesson_id'], 'uq_progress_user_lesson');
            $table->index('lesson_id', 'fk_progress_lesson');
            
            $table->foreign('user_id', 'fk_progress_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('lesson_id', 'fk_progress_lesson')
                  ->references('id')
                  ->on('lessons')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_progress');
    }
};
