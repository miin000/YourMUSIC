<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('songs', function (Blueprint $table) {
            // Xóa cột không còn dùng      // Xóa cột url cũ
            $table->dropColumn('play_count'); // Xóa cột playcount cũ

            // Thêm cột mới
            $table->string('file_path');     // Thêm cột file_path để lưu đường dẫn file
        });
    }

    public function down()
    {
        Schema::table('songs', function (Blueprint $table) {
            // Phục hồi các cột cũ nếu rollback
            $table->integer('play_count')->default(0);

            // Xóa cột mới khi rollback
            $table->dropColumn('file_path');
        });
    }

};
