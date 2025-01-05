@extends('layouts.app') 
@section('content') 
<div class="container"> 
    <h1>Create Playlist</h1> 
    <form action="{{ route('playlists.store') }}" method="POST"> 
        @csrf 
        <div class="mb-3"> 
            <label for="name" class="form-label">Playlist Name</label> 
            <input type="text" name="name" id="name" class="form-control" required> 
            @error('name') 
            <div class="text-danger">{{$message}}</div> 
            @enderror 
        </div> 
        <button type="submit" class="btn btn-primary">Create Playlist</button> 
    </form> 
</div> 
@endsection