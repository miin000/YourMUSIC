<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
  public function index()
  {
    $playlists = Playlist::where('user_id', Auth::id())->get();
    return view('playlists.index', compact('playlists'));
  }

  public function create()
  {
    return view('playlists.create');
  }

  public function store(Request $request)
  {
    $request->validate(['name' => 'required']);
    Playlist::create(['name' => $request->name, 'user_id' => Auth::id()]);
    return redirect()->route('playlists.index')->with('success', 'Playlist created successfully!');
  }

  public function show(Playlist $playlist)
  {
      $playlist->load('songs');
    return view('playlists.show', compact('playlist'));
  }

  public function destroy(Playlist $playlist)
  {
    $playlist->delete();
    return redirect()->route('playlists.index')->with('success', 'Playlist deleted successfully!');
  }
  public function edit(Playlist $playlist)
  {
    return view('playlists.edit', compact('playlist'));
  }

  public function update(Request $request, Playlist $playlist)
  {
    $request->validate(['name' => 'required']);
    $playlist->update([
      'name' => $request->name,
    ]);
    return redirect()->route('playlists.index')->with('success', 'Playlist Updated Successfully');
  }

  public function addSong(Playlist $playlist, Song $song)
  {
    $playlist->songs()->attach($song->id);
    return redirect()->back()->with('success', 'Song added to playlist');
  }

  public function removeSong(Playlist $playlist, Song $song)
  {
    $playlist->songs()->detach($song->id);
    return redirect()->back()->with('success', 'Song removed from playlist');
  }
}