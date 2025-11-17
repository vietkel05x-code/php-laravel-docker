<?php

// database/migrations/xxxx_make_instructor_id_nullable_on_courses_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('courses', function (Blueprint $t) {
            // Nếu cột đã tồn tại kiểu BIGINT UNSIGNED:
            $t->unsignedBigInteger('instructor_id')->nullable()->change();
        });
    }
    public function down(): void {
        Schema::table('courses', function (Blueprint $t) {
            $t->unsignedBigInteger('instructor_id')->nullable(false)->change();
        });
    }
};
