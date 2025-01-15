<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use Illuminate\Support\Facades\Storage;

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
        $fileName = $file->getClientOriginalName();  // Luu ten file

        // Lưu vào database
        $song = Song::create([
            'title' => $request->title,
            'artist' => $request->artist,
            'album' => $request->album,
            'genre' => $request->genre,
            'file_path' => Storage::url('songs/' . $fileName), // Lưu URL
        ]);

        return redirect()->route('songs.index')
            ->with('success', 'Song uploaded successfully');
    }

    public function show(Song $song)
    {
        return view('songs.play', compact('song'));
    }
    public function destroy(Song $song)
    {
        Storage::disk('public')->delete($song->file_path);
        $song->delete();
        return redirect()->route('songs.index')->with('success', 'Song Deleted Successfully');
    }


    public function edit(Song $song)
    {
        return view('songs.edit', compact('song'));
    }

    public function update(Request $request, Song $song)
    {
        $request->validate([
            'title' => 'required',
            'artist' => 'required',
            'album' => 'nullable',
            'genre' => 'nullable',
            'file' => 'nullable|file|mimes:mp3,wav,ogg',

        ]);
        if ($request->hasFile('file')) {
            // Delete the old file if exists
            Storage::disk('public')->delete($song->file_path);
            $path = $request->file('file')->store('songs', 'public');
            $song->update([
                'title' => $request->title,
                'artist' => $request->artist,
                'album' => $request->album,
                'genre' => $request->genre,
                'file_path' => $path,
            ]);
        } else {
            $song->update([
                'title' => $request->title,
                'artist' => $request->artist,
                'album' => $request->album,
                'genre' => $request->genre,
            ]);
        }

        return redirect()->route('songs.index')->with('success', 'Song Updated Successfully');
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

    //view admin
    public function admin()
    {
        return view('songs.admin');
    }
    public function next(Song $song)
{
    $nextSong = Song::where('id', '>', $song->id)->first();
    if (!$nextSong) {
        $nextSong = Song::first(); // Loop back to the first song
    }
    return redirect()->route('songs.show', $nextSong);
}

public function prev(Song $song)
{
    $prevSong = Song::where('id', '<', $song->id)->orderBy('id', 'desc')->first();
    if (!$prevSong) {
        $prevSong = Song::orderBy('id', 'desc')->first(); // Loop back to the last song
    }
    return redirect()->route('songs.show', $prevSong);
}

}
