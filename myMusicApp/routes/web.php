<?php

use App\Http\Controllers\SongController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;  

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [HomeController::class, 'index'])  
    ->middleware(['auth', 'verified'])  
    ->name('dashboard'); 

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Routes for Songs
Route::resource('songs',SongController::class);
Route::get('/songs', [SongController::class, 'index'])->name('songs.index');
Route::get('/admin', [SongController::class, 'admin'])->name('songs.admin');
Route::get('/songs/create', [SongController::class, 'create'])->name('songs.create');    
Route::post('/songs', [SongController::class, 'store'])->name('songs.store');
Route::get('/songs/{song}/next', [SongController::class, 'next'])->name('songs.next');
Route::get('/songs/{song}/prev', [SongController::class, 'prev'])->name('songs.prev');
// Route::post('/songs/{song}/add-to-library', [SongController::class, 'addToLibrary'])->name('library.add');

//test
Route::get('/play-test', [SongController::class, 'playTest']);

// Playlist Routes
Route::resource('playlists',PlaylistController::class);
Route::post('playlists/{playlist}/songs/{song}',[PlaylistController::class,'addSong'])->name('playlists.addSong');
Route::delete('playlists/{playlist}/songs/{song}',[PlaylistController::class,'removeSong'])->name('playlists.removeSong');

//album
Route::get('/check-artist/{artistName}', [SongController::class, 'checkArtist'])->name('songs.checkArtist');
