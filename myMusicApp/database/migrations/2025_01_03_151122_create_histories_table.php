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
        Schema::create('histories', function (Blueprint $table) {
            $table->id(); // ID dòng lịch sử
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID người dùng
            $table->foreignId('song_id')->constrained()->onDelete('cascade'); // ID bài hát
            $table->timestamp('played_at')->useCurrent(); // Thời gian nghe
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
