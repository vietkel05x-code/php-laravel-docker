<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->enum('status', ['active', 'revoked'])->default('active');
            $table->dateTime('enrolled_at');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            
            $table->unique(['user_id', 'course_id'], 'uq_enroll_user_course');
            $table->index('course_id', 'idx_enroll_course');
            
            $table->foreign('user_id', 'fk_enroll_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('course_id', 'fk_enroll_course')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
