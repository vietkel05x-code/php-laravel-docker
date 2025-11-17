<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('code', 40)->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            
            $table->index('user_id', 'fk_orders_user');
            $table->index(['status', 'created_at'], 'idx_orders_status_created');
            
            $table->foreign('user_id', 'fk_orders_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
