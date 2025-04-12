class BRMediaPlayer {
  constructor() {
    this.players = new Map();
    this.currentTrack = null;
    this.init();
  }

  init() {
    this.bindEvents();
    this.initPlyr();
  }

  initPlyr() {
    this.players = Array.from(document.querySelectorAll('.brmedia-player')).map(player => {
      const instance = new Plyr(player.querySelector('audio'), {
        controls: [],
        volume: brmediaConfig.defaultVolume,
        listeners: {
          play: () => this.handlePlay(player.dataset.trackId),
          pause: () => this.handlePause(),
          ended: () => this.handleEnd()
        }
      });
      
      return {
        element: player,
        instance,
        trackId: player.dataset.trackId
      };
    });
  }

  bindEvents() {
    document.addEventListener('click', e => {
      const playButton = e.target.closest('.brmedia-play');
      if (playButton) this.handlePlayButton(playButton);
    });
    
    document.addEventListener('brmedia:volumechange', e => {
      this.setGlobalVolume(e.detail.volume);
    });
  }

  handlePlayButton(button) {
    const player = button.closest('.brmedia-player');
    const trackId = player.dataset.trackId;
    const instance = this.players.find(p => p.trackId === trackId)?.instance;
    
    if (instance) {
      if (instance.playing) {
        instance.pause();
      } else {
        this.pauseAll();
        instance.play();
        this.currentTrack = trackId;
      }
    }
  }

  pauseAll() {
    this.players.forEach(p => p.instance.pause());
  }

  setGlobalVolume(volume) {
    this.players.forEach(p => {
      p.instance.volume = volume;
    });
  }

  handlePlay(trackId) {
    wp.data.dispatch('brmedia/player').playTrack(trackId);
    this.trackPlayback(trackId);
  }

  async trackPlayback(trackId) {
    try {
      const response = await fetch(brmediaConfig.ajaxUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          action: 'brmedia_track_play',
          track_id: trackId,
          nonce: brmediaConfig.nonce
        })
      });
      
      if (!response.ok) throw new Error('Tracking failed');
    } catch (error) {
      console.error('Playback tracking error:', error);
    }
  }
}

document.addEventListener('DOMContentLoaded', () => {
  window.BRMedia = new BRMediaPlayer();
});