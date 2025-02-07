
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Song List</h1>
        {{-- <a href="{{ route('songs.create')}}" class="btn btn-primary">Upload New Song</a> --}}
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{session('success')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @foreach ($songs as $song)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $song->title }}</h5>
                        <div class="text-muted mb-3">
                            <p class="mb-1"><i class="bi bi-person"></i> Artist: {{ $song->artist->name }}</p>
                            {{-- @if($song->album)
                                <p class="mb-1"><i class="bi bi-disc"></i> Album: {{ $song->album->title }}</p>
                            @endif --}}
                            <p class="mb-1"><i class="bi bi-disc"></i> Album: {{ $song->album ? $song->album->title : 'Không có' }}</p>
                            @if($song->genre)
                                <p class="mb-1"><i class="bi bi-music-note"></i> Genre: {{ $song->genre }}</p>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{route('songs.show', $song->id)}}" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-play-fill"></i> Play
                            </a>
                            {{-- <a href="{{route('songs.edit', $song->id)}}" class="btn btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="post" action="{{ route('songs.destroy', $song->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this song?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form> --}}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection