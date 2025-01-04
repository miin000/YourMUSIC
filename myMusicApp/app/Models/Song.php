<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artist',
        'album',
        'genre',
        'duration',
        'file_path',
    ];

    // Quan hệ n-n: Một bài hát có thể nằm trong nhiều playlist
    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_songs');
    }

    // Quan hệ 1-n: Một bài hát có thể xuất hiện nhiều lần trong lịch sử
    public function histories()
    {
        return $this->hasMany(History::class);
    }
}

