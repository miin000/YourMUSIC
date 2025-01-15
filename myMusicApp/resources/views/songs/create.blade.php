@extends('layouts.app') <!-- Kế thừa từ layout app -->

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Add New Song</h2>
    <!-- Hiển thị lỗi nếu có -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form nhập bài hát -->
    <form action="{{ route('songs.store') }}" method="POST" enctype="multipart/form-data" class="bg-light p-4 shadow rounded">
        @csrf
        <div class="form-group mb-3">
            <label for="title" class="form-label">Song Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Enter song title" value="{{ old('title') }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="artist" class="form-label">Artist</label>
            <input type="text" name="artist" id="artist" class="form-control" placeholder="Enter artist name" value="{{ old('artist') }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="album" class="form-label">Album</label>
            <input type="text" name="album" id="album" class="form-control" placeholder="Enter album name" value="{{ old('album') }}">
        </div>
        <div class="form-group mb-3">
            <label for="genre" class="form-label">Genre</label>
            <input type="text" name="genre" id="genre" class="form-control" placeholder="Enter genre" value="{{ old('genre') }}">
        </div>
        <div class="form-group mb-3">
            <label for="song_file" class="form-label">Upload Song File</label>
            <input type="file" name="song_file" id="song_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Upload Song</button>
    </form>

    <!-- Nút quay lại -->
    <a href="{{ route('songs.index') }}" class="btn btn-secondary mt-4">Back to Songs List</a>
</div>
@endsection
