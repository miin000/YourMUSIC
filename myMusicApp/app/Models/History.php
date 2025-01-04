<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'song_id',
        'played_at',
    ];

    // Quan hệ 1-n: Một lịch sử thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ 1-n: Một lịch sử thuộc về một bài hát
    public function song()
    {
        return $this->belongsTo(Song::class);
    }
}

