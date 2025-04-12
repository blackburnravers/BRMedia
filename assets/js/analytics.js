// assets/js/analytics.js
class BRMediaAnalytics {
  constructor() {
    this.trackedEvents = [];
    this.initialized = false;
    this.init();
  }

  init() {
    if (typeof window.ga === 'function') {
      this.initialized = true;
    }
    this.bindPlayerEvents();
  }

  bindPlayerEvents() {
    document.addEventListener('brmedia:play', e => this.trackPlayStart(e));
    document.addEventListener('brmedia:pause', e => this.trackPlayPause(e));
    document.addEventListener('brmedia:ended', e => this.trackPlayComplete(e));
  }

  trackPlayStart(event) {
    const { trackId, playerId } = event.detail;
    this.sendEvent('play_start', {
      track_id: trackId,
      player_instance: playerId,
      timestamp: Date.now()
    });
  }

  trackPlayPause(event) {
    this.sendEvent('play_pause', {
      duration: event.detail.playbackPosition
    });
  }

  async sendEvent(eventType, eventData) {
    try {
      const response = await fetch(brmedia.ajax_url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': brmedia.rest_nonce
        },
        body: JSON.stringify({
          action: 'brmedia_track_event',
          event_type: eventType,
          event_data: eventData
        })
      });

      if (!response.ok) throw new Error('Tracking failed');
      
      const result = await response.json();
      if (result.success) {
        console.debug('Event tracked:', eventType);
      }
    } catch (error) {
      console.error('Analytics error:', error);
    }
  }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  window.brmediaAnalytics = new BRMediaAnalytics();
});