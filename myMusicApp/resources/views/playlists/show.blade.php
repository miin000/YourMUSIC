@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Playlist: {{ $playlist->name }}</h1>

    <div class="row">
        <!-- Song List -->
        <div class="col-md-6">
            <h2>Songs in this playlist</h2>
            @if(session('success'))
                <div class="alert alert-success">
                    {{session('success')}}
                </div>
            @endif
            <ul class="list-group">
                @forelse ($playlist->songs as $song)
                    <li class="list-group-item d-flex justify-content-between align-items-center song-item"
                       data-song-id="{{ $song->id }}"
                       data-song-path="{{ asset($song->file_path) }}">
                        {{ $song->title }}
                        <form action="{{ route('playlists.removeSong',[$playlist->id,$song->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </li>
                @empty
                    <li class="list-group-item">No songs in this playlist yet.</li>
                @endforelse
            </ul>
        </div>
        <!-- Music Player  -->
        <div class="col-md-6">
            <h2>Music Player</h2>
            <div class="card">
                <div class="card-body d-flex justify-content-center align-items-center">
                    <audio id="playlistPlayer" controls>
                        {{-- Automatically populate first song from list for demo --}}
                        @if($playlist->songs->count() > 0)
                          <source src="{{ asset($playlist->songs->first()->file_path) }}" type="audio/mpeg">
                         @endif
                        Your browser does not support the audio element.
                    </audio>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const playlistPlayer = document.getElementById('playlistPlayer');
        const songItems = document.querySelectorAll('.song-item');

        songItems.forEach(item => {
            item.addEventListener('click', function() {
                const songPath = this.getAttribute('data-song-path');
                const newSource = document.createElement('source');
                newSource.setAttribute('src', songPath);
                newSource.setAttribute('type', 'audio/mpeg');

                playlistPlayer.innerHTML = ''; //remove all old source elements
                playlistPlayer.appendChild(newSource);
                playlistPlayer.load(); //refresh audio player
                playlistPlayer.play();
            });
        });
    });

</script>
@endsection