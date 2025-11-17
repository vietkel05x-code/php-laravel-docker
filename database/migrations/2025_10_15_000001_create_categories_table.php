<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 160)->unique();
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            
            $table->foreign('parent_id', 'fk_categories_parent')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
