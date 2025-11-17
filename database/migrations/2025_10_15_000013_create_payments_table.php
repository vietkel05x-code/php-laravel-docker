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
            $table->string('provider', 30);
            $table->string('transaction_id', 100)->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('status', 20)->default('initiated');
            $table->json('meta')->nullable();
            $table->timestamps();
            
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
