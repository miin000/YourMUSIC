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
            // Chỉ xóa cột artist nếu nó tồn tại  
            if (Schema::hasColumn('songs', 'artist')) {  
                $table->dropColumn('artist');  
            }  

            // Chỉ xóa cột album nếu nó tồn tại  
            if (Schema::hasColumn('songs', 'album')) {  
                $table->dropColumn('album');  
            }  

            // Thêm mới artist_id và album_id  
            if (!Schema::hasColumn('songs', 'artist_id')) {  
                $table->foreignId('artist_id')->nullable()->constrained()->onDelete('cascade');  
            }  

            // Tương tự với album_id  
            if (!Schema::hasColumn('songs', 'album_id')) {  
                $table->foreignId('album_id')->nullable()->constrained()->onDelete('set null');  
            }  
        });  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('songs', function (Blueprint $table) {  
            // Quay lại các thay đổi: xóa khóa ngoại và thêm lại cột string  
            if (Schema::hasColumn('songs', 'artist_id')) {  
                $table->dropForeign(['artist_id']);  
                $table->dropColumn('artist_id');  
            }  

            if (Schema::hasColumn('songs', 'album_id')) {  
                $table->dropForeign(['album_id']);  
                $table->dropColumn('album_id');  
            }  

            // Thêm lại cột artist và album kiểu string  
            $table->string('artist')->nullable();  
            $table->string('album')->nullable();  

        });  
    }
};
