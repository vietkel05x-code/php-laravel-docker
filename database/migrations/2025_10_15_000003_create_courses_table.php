<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->string('title', 255);
            $table->string('slug', 260)->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_html')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->string('thumbnail_path', 500)->nullable();
            $table->enum('status', ['draft', 'published', 'hidden', 'archived'])->default('draft');
            $table->unsignedInteger('total_duration')->default(0);
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->string('language', 50)->default('Vietnamese');
            $table->integer('enrolled_students')->default(0);
            $table->decimal('rating', 2, 1)->default(0.0);
            $table->integer('rating_count')->default(0);
            $table->integer('video_count')->default(0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            
            $table->foreign('instructor_id', 'fk_courses_instructor')
                  ->references('id')
                  ->on('users');
            
            $table->index('status', 'idx_courses_status');
        });
        
        // Add fulltext index
        DB::statement('ALTER TABLE courses ADD FULLTEXT ftx_courses (title, short_description, description)');
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
