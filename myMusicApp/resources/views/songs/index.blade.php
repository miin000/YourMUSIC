@extends('layouts.app')

@section('content')
<div class="music-player-container">
    <div class="player">
        <div class="now-playing">
            <span id="now-playing-title"></span>
            <span id="now-playing-artist"></span>
        </div>
        <audio id="audio-player">
            <source id="audio-source" src="" type="audio/mpeg">
            Trình duyệt của bạn không hỗ trợ phát nhạc.
        </audio>
        <div id="audio-controls">
            <button id="prev-btn" class="control-btn"><i class="fas fa-backward"></i></button>
            <button id="play-pause-btn" class="control-btn"><i class="fas fa-play"></i></button>
            <button id="next-btn" class="control-btn"><i class="fas fa-forward"></i></button>
            <input type="range" id="progress-bar" value="0" max="100" step="1" />
            <div class="time-display">
                <span id="current-time">0:00</span> / <span id="duration">0:00</span>
            </div>
            <button id="loop-btn" class="control-btn"><i class="fas fa-sync"></i></button>
            <button id="shuffle-btn" class="control-btn"><i class="fas fa-random"></i></button>
            <input type="range" id="volume-control" value="100" max="100" step="1" />
        </div>
    </div>

    <div class="song-list">
        @foreach ($songs as $index => $song)
            @if (file_exists(public_path($song->file_path)))
                <button class="song-item" data-index="{{ $index }}" data-file="{{ asset($song->file_path) }}" data-title="{{ $song->title }}" data-artist="{{ $song->artist }}">
                    {{ $song->title }} - {{ $song->artist }}
                </button>
            @else
                <button class="song-item disabled" disabled>
                    {{ $song->title }} - {{ $song->artist }} (Không khả dụng)
                </button>
            @endif
        @endforeach
    </div>
</div>
<style>
    /* CSS */
    .music-player-container {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 20px;
        padding: 20px;
        max-width: 1000px;
        margin: 0 auto;
    }
    .player {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        background-color: #f8f9fa;
        display: flex;
        flex-direction: column;
    }
    .now-playing {
        text-align: center;
        margin-bottom: 15px;
        font-size: 1.2em;
    }
    .now-playing #now-playing-title {
        font-weight: bold;
    }
    #audio-controls {
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    #progress-bar {
        flex-grow: 1;
    }
    .time-display {
        white-space: nowrap;
    }
    .control-btn {
        background-color: transparent;
        border: none;
        cursor: pointer;
        font-size: 20px;
        padding: 5px;
    }
    .control-btn:hover {
        opacity: 0.8;
    }
    .song-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-height: 600px;
        overflow-y: auto;
    }
    .song-item {
        padding: 10px;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .song-item:hover {
        background-color: #e9ecef;
    }
    .song-item.disabled {
        cursor: not-allowed;
        color: gray;
    }
    .song-item.playing {
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }
    /* Thêm CSS để thay đổi icon play và pause */
    #play-pause-btn.playing i.fa-play {
        display: none;
    }
    #play-pause-btn.playing i.fa-pause {
        display: inline;
    }
    #play-pause-btn i.fa-play, #play-pause-btn i.fa-pause {
       display: inline;
    }
    #play-pause-btn i.fa-pause {
        display: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const audioPlayer = document.getElementById('audio-player');
    const audioSource = document.getElementById('audio-source');
    const songItems = document.querySelectorAll('.song-item');
    const playPauseButton = document.getElementById('play-pause-btn');
    const prevButton = document.getElementById('prev-btn');
    const nextButton = document.getElementById('next-btn');
    const progressBar = document.getElementById('progress-bar');
    const currentTimeElement = document.getElementById('current-time');
    const durationElement = document.getElementById('duration');
    const loopButton = document.getElementById('loop-btn');
    const shuffleButton = document.getElementById('shuffle-btn');
    const volumeControl = document.getElementById('volume-control');
    const nowPlayingTitle = document.getElementById('now-playing-title');
    const nowPlayingArtist = document.getElementById('now-playing-artist');


    let currentSongIndex = -1;
    let isPlaying = false;
    let isLooping = false;
    let isShuffling = false;
    let originalSongOrder = Array.from(songItems).map(item => item);
    let shuffledSongOrder = [];

    function setAudioSource(index) {
        if (index >= 0 && index < songItems.length) {
            const item = songItems[index];
            const file = item.getAttribute('data-file');
            const title = item.getAttribute('data-title');
            const artist = item.getAttribute('data-artist');

            audioSource.src = file;
            audioPlayer.load();
            nowPlayingTitle.textContent = title;
            nowPlayingArtist.textContent = artist;
            currentSongIndex = index;
        }
    }

    function playSong() {
        audioPlayer.play();
        isPlaying = true;
        playPauseButton.innerHTML = '<i class="fas fa-pause"></i>';
        playPauseButton.classList.add('playing');
        highlightCurrentSong();
    }

    function pauseSong() {
        audioPlayer.pause();
        isPlaying = false;
        playPauseButton.innerHTML = '<i class="fas fa-play"></i>';
        playPauseButton.classList.remove('playing');
    }

    function highlightCurrentSong() {
        songItems.forEach(item => item.classList.remove('playing'));
        if (currentSongIndex >= 0) {
            songItems[currentSongIndex].classList.add('playing');
        }
    }

    songItems.forEach((item, index) => {
        item.addEventListener('click', () => {
            setAudioSource(index);
            playSong();
        });
    });

    // Phát/tạm dừng nhạc
    playPauseButton.addEventListener('click', () => {
        if (isPlaying) {
            pauseSong();
        } else {
           if (currentSongIndex < 0) {
                 setAudioSource(0);
             }
             playSong();
        }
    });

    // Chuyển bài hát trước
    prevButton.addEventListener('click', () => {
            let index = isShuffling
            ? shuffledSongOrder.findIndex(item => item === songItems[currentSongIndex])
            : currentSongIndex;
        index = (index - 1 + songItems.length) % songItems.length;
        currentSongIndex = isShuffling
            ? shuffledSongOrder.findIndex(item => item === songItems[index])
            : index;
        setAudioSource(currentSongIndex);
        playSong();
    });

    // Chuyển bài hát sau
    nextButton.addEventListener('click', () => {
        let index = isShuffling
            ? shuffledSongOrder.findIndex(item => item === songItems[currentSongIndex])
            : currentSongIndex;
        index = (index + 1) % songItems.length;
        currentSongIndex = isShuffling
            ? shuffledSongOrder.findIndex(item => item === songItems[index])
            : index;
        setAudioSource(currentSongIndex);
        playSong();
    });

        // Update progress bar and time display
        audioPlayer.addEventListener('timeupdate', () => {
            const currentTime = audioPlayer.currentTime;
            const duration = audioPlayer.duration;
            const progress = (currentTime / duration) * 100;

            progressBar.value = progress;
            currentTimeElement.textContent = formatTime(currentTime);
            durationElement.textContent = formatTime(duration);
        });

        // Sync progress bar with audio
        progressBar.addEventListener('input', () => {
            const value = progressBar.value;
            const duration = audioPlayer.duration;
            audioPlayer.currentTime = (value / 100) * duration;
        });

        // Format time in mm:ss format
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = Math.floor(seconds % 60);
            return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
        }
            // Phát bài tiếp theo khi kết thúc
         audioPlayer.addEventListener('ended', () => {
             if (isLooping) {
                 audioPlayer.currentTime = 0;
                 playSong();
             } else {
                 nextButton.click();
             }
         });
         loopButton.addEventListener('click', () => {
                isLooping = !isLooping;
                loopButton.classList.toggle('active', isLooping);
        });
       shuffleButton.addEventListener('click', () => {
           isShuffling = !isShuffling;
           shuffleButton.classList.toggle('active', isShuffling);
           if (isShuffling) {
               shuffledSongOrder = Array.from(songItems).sort(() => Math.random() - 0.5);
           } else {
               shuffledSongOrder = [];
               currentSongIndex = originalSongOrder.findIndex(item => item === songItems[currentSongIndex]);
           }
       });

          volumeControl.addEventListener('input', () => {
           audioPlayer.volume = volumeControl.value / 100;
       });

        // Handle error when playing audio
        audioPlayer.addEventListener('error', () => {
            alert('Không thể phát nhạc. Vui lòng thử lại.');
        });
    });
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection