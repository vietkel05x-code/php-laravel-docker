<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->enum('provider', ['bank_transfer', 'vnpay', 'momo', 'paypal']);
            $table->string('transaction_id', 100)->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['initiated', 'succeeded', 'failed', 'refunded'])->default('initiated');
            $table->json('meta')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            
            $table->index('order_id', 'fk_payments_order');
            $table->index(['provider', 'status'], 'idx_payments_provider_status');
            
            $table->foreign('order_id', 'fk_payments_order')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
