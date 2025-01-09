@extends('layouts.app')

@section('content')
    <div class="container">
         <h1>Song List</h1>
         <a href="{{ route('songs.create')}}" class="btn btn-primary mb-2">Create your playlist</a>
        @if(session('success'))
         <div class="alert alert-success">
             {{session('success')}}
         </div>
        @endif
        <ul class="list-group">
            @foreach ($songs as $song)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="song-info">
                        <h5 class="mb-0"> {{ $song->title }}</h5>
                        <p class="mb-0">Artist : {{ $song->artist }}</p>
                        <p class="mb-0">Album: {{ $song->album }}</p>
                        <p class="mb-0">Genre: {{ $song->genre }}</p>
                    </div>
                    <div class="d-flex">
                      <a href="{{route('songs.show',$song->id)}}" class="btn btn-primary btn-sm me-2">Play</a>
                     <a href="{{route('songs.edit',$song->id)}}" class="btn btn-secondary btn-sm me-2">Edit</a>
                        <form method="post" action="{{ route('songs.destroy', $song->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                         </form>
                    </div>


                </li>
            @endforeach
        </ul>
    </div>
@endsection