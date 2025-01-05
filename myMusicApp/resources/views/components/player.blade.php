<div class="player">
    <div class="now-playing">
       <span id="now-playing-title"></span>
         <span id="now-playing-artist"></span>
 </div>
<audio id="audio-player" data-current-song="-1">
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