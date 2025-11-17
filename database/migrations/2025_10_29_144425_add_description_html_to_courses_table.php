<?php

// database/migrations/xxxx_add_description_html_to_courses_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('courses', function (Blueprint $t) {
            $t->longText('description_html')->nullable()->after('description'); // hoặc sau cột nào cũng được
        });
    }
    public function down(): void {
        Schema::table('courses', function (Blueprint $t) {
            $t->dropColumn('description_html');
        });
    }
};

