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
        'file_path',
    ];

    // Quan hệ n-n: Một bài hát có thể nằm trong nhiều playlist
    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_songs');
    }
}

