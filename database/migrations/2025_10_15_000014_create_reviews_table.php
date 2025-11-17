<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedTinyInteger('rating');
            $table->text('content')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            
            $table->unique(['user_id', 'course_id'], 'uq_review_user_course');
            $table->index('course_id', 'idx_reviews_course');
            
            $table->foreign('user_id', 'fk_reviews_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('course_id', 'fk_reviews_course')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
