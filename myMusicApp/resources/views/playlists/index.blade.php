@extends('layouts.app')

@section('content')
<div class="container">
    @if(Auth::user()->isAdmin())
       <h1>Library of Playlists</h1>
    @else
       <h1>My Playlists</h1>
    @endif

    @if(!Auth::user()->isAdmin())
       <a href="{{ route('playlists.create') }}" class="btn btn-primary mb-2">Create New Playlist</a>
    @endif


    @if(session('success'))
        <div class="alert alert-success">
            {{session('success')}}
        </div>
    @endif
    <ul class="list-group">
        @foreach ($playlists as $playlist)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('playlists.show', $playlist->id) }}">{{ $playlist->name }}</a>
                   <div>
                    @if(!Auth::user()->isAdmin() || Auth::user()->id == $playlist->user_id)
                       <a href="{{ route('playlists.edit',$playlist->id) }}" class="btn btn-secondary btn-sm me-2">Edit</a>
                       <form method="POST" action="{{ route('playlists.destroy', $playlist->id) }}" style="display: inline-block;">
                           @csrf
                           @method('DELETE')
                           <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                       </form>
                     @endif
                    </div>
            </li>
        @endforeach
    </ul>
</div>
@endsection