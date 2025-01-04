<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class SongController extends Controller
{
    public function index()
    {
        $songs = Song::all();
        return view('songs.index', compact('songs'));
    }

    public function create()
    {
        return view('songs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'song_file' => 'required|file|mimes:mp3|max:10240', // Max 10MB
        ]);

        $file = $request->file('song_file');
        $fileName = time() . '_' . $file->getClientOriginalName();  // Tên file gốc và thời gian
        $filePath = $file->storeAs('public/songs', $fileName);

        // Lưu vào database
        $song = Song::create([
            'title' => $request->title,
            'artist' => $request->artist,
            'album' => $request->album,
            'genre' => $request->genre,
            'duration' => '0:00', // có thể bỏ qua nếu không cần
            'file_path' => Storage::url('songs/' . $fileName), // Lưu URL chính xác
        ]);

        return redirect()->route('songs.index')
            ->with('success', 'Song uploaded successfully');
    }

    public function show(Song $song)
    {
        return view('songs.show', compact('song'));
    }

    //test
    public function playTest()
    {
        // Đường dẫn đầy đủ file trên hệ thống
        $filePath = 'D:/PHP/myMUSIC/myMusicApp/public/storage/songs/1735982823_matketnot_dongdomic.mp3';

        // Kiểm tra xem file có tồn tại không
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Trả về file dưới dạng response
        return response()->file($filePath);
    }
}
