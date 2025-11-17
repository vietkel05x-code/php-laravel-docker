<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('read_at')->nullable();
            
            $table->primary(['notification_id', 'user_id']);
            $table->index('user_id', 'fk_un_user');
            
            $table->foreign('notification_id', 'fk_un_notification')
                  ->references('id')
                  ->on('notifications')
                  ->onDelete('cascade');
            
            $table->foreign('user_id', 'fk_un_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
