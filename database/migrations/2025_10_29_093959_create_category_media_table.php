<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('category_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('type')->default('thumbnail'); // thumbnail, banner, icon...
            $table->string('path'); // đường dẫn ảnh
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_media');
    }
};
