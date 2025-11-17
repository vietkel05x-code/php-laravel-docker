<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('course_id');
            $table->decimal('price', 10, 2);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            
            $table->unique(['order_id', 'course_id'], 'uq_item_order_course');
            $table->index('course_id', 'fk_items_course');
            
            $table->foreign('order_id', 'fk_items_order')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');
            
            $table->foreign('course_id', 'fk_items_course')
                  ->references('id')
                  ->on('courses');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
