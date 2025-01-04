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
        Schema::table('songs', function (Blueprint $table) {
            $table->string('duration')->change();  // Đổi kiểu dữ liệu từ int thành string
            // Hoặc nếu bạn muốn dùng kiểu time:
            // $table->time('duration')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->integer('duration')->change();  // Quay lại kiểu int nếu cần
        });
    }
};
