<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('avatar', 255)->nullable();
            $table->string('password', 255);
            $table->enum('role', ['student', 'admin'])->default('student');
            $table->dateTime('email_verified_at')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            
            $table->index('role', 'idx_users_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
