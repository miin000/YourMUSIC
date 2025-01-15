@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Playing: {{ $song->title }}</h1>
    <div class="card">
        <div class="card-body d-flex justify-content-center align-items-center">
            <audio id="audioPlayer" controls>
                <source src="{{ asset($song->file_path) }}" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('songs.prev', $song->id) }}" class="btn btn-secondary">Previous</a>
        <a href="{{ route('songs.next', $song->id) }}" class="btn btn-secondary">Next</a>
        {{-- <form action="{{ route('library.add', $song->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Add to Library</button>
        </form> --}}
    </div>

    <div class="mt-3">
        <h3 class="mt-3 mb-2">Add to Playlist</h3>
        <div class="row">
            @foreach(Auth::user()->playlists as $playlist)
                <div class="col-md-4 mb-2">
                    <div class="card p-2">
                        <form action="{{ route('playlists.addSong', [$playlist->id, $song->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Add to: {{ $playlist->name }}</button>
                        </form>
                    </div>
                </div>
            @endforeach
            <div class="col-md-4 mb-2">
                <div class="card p-2">
                    <a href="{{ route('playlists.create') }}" class="btn btn-primary">Create New Playlist</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
