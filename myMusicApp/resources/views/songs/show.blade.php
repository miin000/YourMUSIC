@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $song->title }}</h1>
        <p>Ca sĩ: {{ $song->artist }}</p>
        <audio controls>
            <source src="{{ $song->file_path }}" type="audio/mpeg">
            Trình duyệt của bạn không hỗ trợ HTML5 Audio.
        </audio>
    </div>
@endsection
