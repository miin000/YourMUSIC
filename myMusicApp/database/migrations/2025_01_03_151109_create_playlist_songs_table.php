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
        Schema::create('playlist_songs', function (Blueprint $table) {
            $table->id(); // ID dòng (không bắt buộc)
            $table->foreignId('playlist_id')->constrained()->onDelete('cascade'); // ID playlist
            $table->foreignId('song_id')->constrained()->onDelete('cascade'); // ID bài hát
            $table->timestamps(); // 'created_at' và 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlist_songs');
    }
};
