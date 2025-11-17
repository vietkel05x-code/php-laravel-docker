<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id');
            $table->string('title', 255);
            $table->string('video_path', 500)->nullable();
            $table->string('hls_path', 500)->nullable();
            $table->string('cloudinary_id', 255)->nullable();
            $table->string('video_url', 500)->nullable();
            $table->string('attachment_path', 500)->nullable();
            $table->unsignedInteger('duration')->default(0);
            $table->boolean('is_preview')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            
            $table->index(['section_id', 'position'], 'idx_lessons_section_pos');
            
            $table->foreign('section_id', 'fk_lessons_section')
                  ->references('id')
                  ->on('sections')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
