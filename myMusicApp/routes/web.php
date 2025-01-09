<?php

use App\Http\Controllers\SongController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Routes for Songs
Route::resource('songs',SongController::class);
Route::get('/songs', [SongController::class, 'index'])->name('songs.index');
Route::get('/songs/create', [SongController::class, 'create'])->name('songs.create');    
Route::post('/songs', [SongController::class, 'store'])->name('songs.store');

//test
Route::get('/play-test', [SongController::class, 'playTest']);

// Playlist Routes
Route::resource('playlists',PlaylistController::class);
Route::post('playlists/{playlist}/songs/{song}',[PlaylistController::class,'addSong'])->name('playlists.addSong');
Route::delete('playlists/{playlist}/songs/{song}',[PlaylistController::class,'removeSong'])->name('playlists.removeSong');
