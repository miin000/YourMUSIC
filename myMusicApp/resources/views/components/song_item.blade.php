<div class="song-item-wrap">
    <button class="song-item" data-index="{{ $index ?? '' }}" data-file="{{ $file }}" data-title="{{ $title }}"
     data-artist="{{ $artist }}" data-song_id="{{$song_id}}">
         {{ $title }} - {{ $artist }}
    </button>
  <button class="add-playlist-btn"><i class="fas fa-plus"></i></button>
</div>

 <div class="playlist-modal hide">
     <div class="modal-content">
      <span class="close">×</span>
          <form action="" method="post">
           <h4>Thêm bài hát vào playlist</h4>
                <select name="playlist_id" id="" class="playlist-list-select">
                  <option disabled selected>-- Chọn playlist ---</option>
                  </select>
                <button type="submit">Thêm</button>
           </form>
       <p class="mt-2 create-playlist">Bạn chưa có playlist? <a href="{{ route('playlists.create') }}">Tạo ngay!</a></p>
     </div>
  </div>
<style>

  </style>
  <script>

       document.addEventListener('DOMContentLoaded', () => {
  const audioPlayer = document.getElementById('audio-player');
   const audioSource = document.getElementById('audio-source');
   const songItems = document.querySelectorAll('.song-item');
       const addPlaylistBtns = document.querySelectorAll('.add-playlist-btn');
 const playlistModal = document.querySelector('.playlist-modal');
     const modalclosebtn =  playlistModal.querySelector('.close');


// Get playlist cho modal add song

      function  updatePlaylists() {
           const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

     fetch('/playlists/get-playlist',{
            method: 'POST',
            headers: {
           'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
          },
 })
  .then(response => response.json())
    .then(data => {

     const selectElem = playlistModal.querySelector('.playlist-list-select');

        if (selectElem) {
       selectElem.innerHTML = "<option disabled selected>-- Chọn playlist --</option>";
      data.playlists.forEach( playlist => {
        const option = document.createElement('option');
      option.value = playlist.id;
            option.text = playlist.name;
          selectElem.appendChild(option)
         })
    }

     })
    .catch(error => {
           console.error('Lỗi khi get data playlist:', error);
      });
   }

  updatePlaylists();


     let currentSongId =0;
       addPlaylistBtns.forEach((button, index) => {
            button.addEventListener('click', (e) => {
            currentSongId =   songItems[index].getAttribute('data-song_id');
               playlistModal.style.display ="block";
             playlistModal.querySelector('form').action ="/playlists/"+currentSongId+"/add-song";
       e.stopPropagation();
     });
});

  // Thêm su kien khi click close ở playlistModal
modalclosebtn.addEventListener('click', () =>{
     playlistModal.classList.add('hide');
      playlistModal.style.display ='none';
    });
    // Tắt modal khi click ra ngoài

    window.addEventListener('click',(e) =>{
            if (e.target == playlistModal){
          playlistModal.classList.add('hide');
           playlistModal.style.display ="none";
        }

})

       function setAudioSource(index) {
         if (index >= 0 && index < songItems.length) {

    const item = songItems[index];

   const file = item.getAttribute('data-file');
     const title = item.getAttribute('data-title');
      const artist = item.getAttribute('data-artist');
        const songId = item.getAttribute('data-song_id');
          audioSource.src = file;
    audioPlayer.load();
  document.querySelector('.now-playing #now-playing-title').textContent = title;
           document.querySelector('.now-playing #now-playing-artist').textContent = artist;
              audioPlayer.dataset.currentSong = index
       currentSongIndex = index;
        saveHistory(songId);

     }
    }
       function saveHistory(songId){
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  fetch('/songs/save-history',{
      method:'POST',
     headers:{
       'Content-Type':'application/json',
           'X-CSRF-TOKEN' : csrfToken
 },
       body:JSON.stringify({songId})

         }).then(response => {
           console.log("History success!", response);
      }).catch(error => {
          console.log('history save fail', error)
  });
 }

function playSong() {
 audioPlayer.play();
    isPlaying = true;
  document.getElementById('play-pause-btn').innerHTML = '<i class="fas fa-pause"></i>';
        document.getElementById('play-pause-btn').classList.add('playing');
     highlightCurrentSong();
}
    function pauseSong() {
       audioPlayer.pause();
          isPlaying = false;
       document.getElementById('play-pause-btn').innerHTML = '<i class="fas fa-play"></i>';
    document.getElementById('play-pause-btn').classList.remove('playing');
      }

   function highlightCurrentSong() {
     songItems.forEach(item => item.classList.remove('playing'));
    if (currentSongIndex >= 0) {
      songItems[currentSongIndex].classList.add('playing');
  }
    }
       let isPlaying = false;
   let currentSongIndex =-1
     songItems.forEach((item, index) => {
    item.addEventListener('click', () => {
      setAudioSource(index);
         playSong();
         });
  });
    // Phát/tạm dừng nhạc
     document.getElementById('play-pause-btn').addEventListener('click', () => {

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
document.getElementById('prev-btn').addEventListener('click', () => {
          let index = document.getElementById('shuffle-btn').classList.contains('active')
     ? shuffledSongOrder.findIndex(item => item === songItems[currentSongIndex])
           : currentSongIndex;
        index = (index - 1 + songItems.length) % songItems.length;
  currentSongIndex = document.getElementById('shuffle-btn').classList.contains('active')
    ? shuffledSongOrder.findIndex(item => item === songItems[index])
          : index;

       setAudioSource(currentSongIndex);
          playSong();
});

  // Chuyển bài hát sau
 document.getElementById('next-btn').addEventListener('click', () => {

      let index = document.getElementById('shuffle-btn').classList.contains('active')
        ? shuffledSongOrder.findIndex(item => item === songItems[currentSongIndex])
    : currentSongIndex;

          index = (index + 1) % songItems.length;
           currentSongIndex =  document.getElementById('shuffle-btn').classList.contains('active')
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

      document.getElementById('progress-bar').value = progress;
        document.getElementById('current-time').textContent = formatTime(currentTime);
      document.getElementById('duration').textContent = formatTime(duration);
 });

   // Sync progress bar with audio
document.getElementById('progress-bar').addEventListener('input', () => {
  const value = document.getElementById('progress-bar').value;
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
if ( document.getElementById('loop-btn').classList.contains('active')) {
         audioPlayer.currentTime = 0;
             playSong();
  } else {
           document.getElementById('next-btn').click();
         }
      });

     document.getElementById('loop-btn').addEventListener('click', () => {
             document.getElementById('loop-btn').classList.toggle('active');
  });

       let  shuffledSongOrder = [];
 document.getElementById('shuffle-btn').addEventListener('click', () => {
     document.getElementById('shuffle-btn').classList.toggle('active');
       if (  document.getElementById('shuffle-btn').classList.contains('active')) {
          shuffledSongOrder = Array.from(songItems).sort(() => Math.random() - 0.5);
       } else {
     shuffledSongOrder = [];

    }
   });

document.getElementById('volume-control').addEventListener('input', () => {
    audioPlayer.volume = document.getElementById('volume-control').value / 100;
  });

     // Handle error when playing audio
   audioPlayer.addEventListener('error', () => {
        alert('Không thể phát nhạc. Vui lòng thử lại.');
     });
});
</script>