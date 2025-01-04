
<form action="{{ route('songs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label>Title:</label>
        <input type="text" name="title" required>
    </div>
    <div>
        <label>Artist:</label>
        <input type="text" name="artist" required>
    </div>
    <div>
        <label>Album:</label>
        <input type="text" name="album">
    </div>
    <div>
        <label>Genre:</label>
        <input type="text" name="genre">
    </div>
    <div>
        <label>Song File:</label>
        <input type="file" name="song_file" accept="audio/mp3" required>
    </div>
    <button type="submit">Upload</button>
</form>