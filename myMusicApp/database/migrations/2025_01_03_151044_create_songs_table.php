<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tên bài hát
            $table->string('artist')->nullable(); // Ca sĩ/ban nhạc
            $table->string('album')->nullable(); // Album (có thể để trống)
            $table->string('genre')->nullable(); // Thể loại
            $table->integer('duration')->nullable(); // Thời lượng (giây)
            $table->string('url'); // Đường dẫn bài hát
            $table->integer('play_count')->default(0); // Lượt nghe
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
