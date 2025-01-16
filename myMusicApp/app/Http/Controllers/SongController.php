<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use Illuminate\Support\Facades\Storage;
use App\Models\Artist;
use App\Models\Album;


class SongController extends Controller
{
    
    public function index()
    {
        $songs = Song::with(['artist', 'album'])->get();
        return view('songs.index', compact('songs'));
    }
    
    public function create()
    {
        $artists = Artist::with('albums')->get();
        return view('songs.create', compact('artists'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'artist_name' => 'required|string|max:255',
            'album_name' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'song_file' => 'required|file|mimes:mp3|max:10240',
        ]);

        // Tìm hoặc tạo nghệ sĩ
        $artist = Artist::firstOrCreate(
            ['name' => $request->artist_name]
        );

        // Xử lý album nếu được cung cấp
        $album = null;
        if ($request->album_name) {
            $album = Album::firstOrCreate(
                [
                    'artist_id' => $artist->id,
                    'title' => $request->album_name  // Thay đổi từ 'name' thành 'title'
                ]
            );
        }

        // Xử lý file bài hát
        $path = null;
        if ($request->hasFile('song_file')) {
            $file = $request->file('song_file');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('songs', $fileName, 'public');
        }

        // Tạo bài hát
        Song::create([
            'title' => $request->title,
            'artist_id' => $artist->id,
            'album_id' => $album ? $album->id : null,
            'genre' => $request->genre,
            'file_path' => $path,
        ]);

        return redirect()->route('songs.index')
            ->with('success', 'Song uploaded successfully');
    }
    
    // Cập nhật phương thức checkArtist để trả về tên album chính xác
    public function checkArtist($artistName) 
    {
        $artist = Artist::where('name', $artistName)->first();
        if ($artist) {
            $albums = $artist->albums->map(function($album) {
                return [
                    'id' => $album->id,
                    'name' => $album->title  // Sử dụng title thay vì name
                ];
            });
            
            return response()->json([
                'exists' => true,
                'albums' => $albums
            ]);
        }
        return response()->json(['exists' => false]);
    }
    
    public function show(Song $song)
    {
        $song->load(['artist', 'album']);
        return view('songs.play', compact('song'));
    }
    
    public function destroy(Song $song)
    {
        // Xóa file
        if ($song->file_path) {
            Storage::disk('public')->delete($song->file_path);
        }
        
        $song->delete();
        return redirect()->route('songs.index')
        ->with('success', 'Song deleted successfully');
    }
    
    public function edit(Song $song)
    {
        $artists = Artist::with('albums')->get();
        $currentArtistAlbums = $song->artist ? $song->artist->albums : collect();
        
        return view('songs.edit', compact('song', 'artists', 'currentArtistAlbums'));
    }
    
    public function update(Request $request, Song $song)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'artist_id' => 'required|exists:artists,id',
            'album_id' => 'nullable|exists:albums,id',
            'genre' => 'nullable|string|max:255',
            'song_file' => 'nullable|file|mimes:mp3|max:10240',
        ]);
        
        // Kiểm tra album nếu được chọn
        if ($request->album_id) {
            $album = Album::findOrFail($request->album_id);
            if ($album->artist_id != $request->artist_id) {
                return back()->withErrors(['album_id' => 'Selected album does not belong to the selected artist']);
            }
        }
        
        // Xử lý file nếu có upload mới
        if ($request->hasFile('song_file')) {
            // Xóa file cũ
            if ($song->file_path) {
                Storage::disk('public')->delete($song->file_path);
            }
            
            $file = $request->file('song_file');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('songs', $fileName, 'public');
            
            $song->file_path = $path;
        }
        
        // Cập nhật thông tin
        $song->update([
            'title' => $request->title,
            'artist_id' => $request->artist_id,
            'album_id' => $request->album_id,
            'genre' => $request->genre,
        ]);
        
        return redirect()->route('songs.index')
        ->with('success', 'Song updated successfully');
    }
    
    public function next(Song $song)
    {
        // Nếu bài hát thuộc album, ưu tiên lấy bài tiếp theo trong album
        if ($song->album_id) {
            $nextSong = Song::where('album_id', $song->album_id)
            ->where('id', '>', $song->id)
            ->first();
            
            if (!$nextSong) {
                // Quay lại bài đầu tiên trong album
                $nextSong = Song::where('album_id', $song->album_id)
                ->orderBy('id')
                ->first();
            }
        } else {
            // Nếu không thuộc album, lấy bài tiếp theo trong list
            $nextSong = Song::where('id', '>', $song->id)->first();
            if (!$nextSong) {
                $nextSong = Song::first();
            }
        }
        
        return redirect()->route('songs.show', $nextSong);
    }
    
    public function prev(Song $song)
    {
        // Tương tự như next, ưu tiên bài trong cùng album
        if ($song->album_id) {
            $prevSong = Song::where('album_id', $song->album_id)
            ->where('id', '<', $song->id)
            ->orderBy('id', 'desc')
            ->first();
            
            if (!$prevSong) {
                // Quay lại bài cuối cùng trong album
                $prevSong = Song::where('album_id', $song->album_id)
                ->orderBy('id', 'desc')
                ->first();
            }
        } else {
            $prevSong = Song::where('id', '<', $song->id)
            ->orderBy('id', 'desc')
            ->first();
            if (!$prevSong) {
                $prevSong = Song::orderBy('id', 'desc')->first();
            }
        }
        
        return redirect()->route('songs.show', $prevSong);
    }
    
    public function admin()
    {
        $songs = Song::with(['artist', 'album'])->get();
        return view('songs.admin', compact('songs'));
    }
    
    // public function index()
    // {
    //     $songs = Song::all();
    //     return view('songs.index', compact('songs'));
    // }

    // public function create()
    // {
    //     return view('songs.create');
    // }
    
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'artist' => 'required|string|max:255',
    //         'song_file' => 'required|file|mimes:mp3|max:10240', // Max 10MB
    //     ]);

    //     $file = $request->file('song_file');
    //     $fileName = $file->getClientOriginalName();  // Luu ten file

    //     // Lưu vào database
    //     $song = Song::create([
    //         'title' => $request->title,
    //         'artist' => $request->artist,
    //         'album' => $request->album,
    //         'genre' => $request->genre,
    //         'file_path' => Storage::url('songs/' . $fileName), // Lưu URL
    //     ]);

    //     return redirect()->route('songs.index')
    //         ->with('success', 'Song uploaded successfully');
    // }

    // public function show(Song $song)
    // {
    //     return view('songs.play', compact('song'));
    // }
    // public function destroy(Song $song)
    // {
    //     Storage::disk('public')->delete($song->file_path);
    //     $song->delete();
    //     return redirect()->route('songs.index')->with('success', 'Song Deleted Successfully');
    // }


    // public function edit(Song $song)
    // {
    //     return view('songs.edit', compact('song'));
    // }

    // public function update(Request $request, Song $song)
    // {
    //     $request->validate([
    //         'title' => 'required',
    //         'artist' => 'required',
    //         'album' => 'nullable',
    //         'genre' => 'nullable',
    //         'file' => 'nullable|file|mimes:mp3,wav,ogg',

    //     ]);
    //     if ($request->hasFile('file')) {
    //         // Delete the old file if exists
    //         Storage::disk('public')->delete($song->file_path);
    //         $path = $request->file('file')->store('songs', 'public');
    //         $song->update([
    //             'title' => $request->title,
    //             'artist' => $request->artist,
    //             'album' => $request->album,
    //             'genre' => $request->genre,
    //             'file_path' => $path,
    //         ]);
    //     } else {
    //         $song->update([
    //             'title' => $request->title,
    //             'artist' => $request->artist,
    //             'album' => $request->album,
    //             'genre' => $request->genre,
    //         ]);
    //     }

    //     return redirect()->route('songs.index')->with('success', 'Song Updated Successfully');
    // }

    //test
    public function playTest()
    {
        // Đường dẫn đầy đủ file trên hệ thống
        $filePath = 'D:/PHP/myMUSIC/myMusicApp/public/storage/songs/matketnot_dongdomic.mp3';

        // Kiểm tra xem file có tồn tại không
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Trả về file dưới dạng response
        return response()->file($filePath);
    }

    // //view admin
    // public function admin()
    // {
    //     return view('songs.admin');
    // }

    // public function next(Song $song)
    // {
    //     $nextSong = Song::where('id', '>', $song->id)->first();
    //     if (!$nextSong) {
    //         $nextSong = Song::first(); // Loop back to the first song
    //     }
    //     return redirect()->route('songs.show', $nextSong);
    // }

    // public function prev(Song $song)
    // {
    //     $prevSong = Song::where('id', '<', $song->id)->orderBy('id', 'desc')->first();
    //     if (!$prevSong) {
    //         $prevSong = Song::orderBy('id', 'desc')->first(); // Loop back to the last song
    //     }
    //     return redirect()->route('songs.show', $prevSong);
    // }
}