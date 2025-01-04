<!-- resources/views/songs/admin.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Manage Songs</h2>
    <a href="{{ route('songs.create') }}" class="btn btn-primary">Add Song</a>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Artist</th>
                <th>Album</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($songs as $song)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $song->title }}</td>
                <td>{{ $song->artist }}</td>
                <td>{{ $song->album }}</td>
                <td>
                    <a href="{{ route('songs.edit', $song->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('songs.destroy', $song->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
