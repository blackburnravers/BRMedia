// BRMedia Casting Integration (Chromecast & AirPlay)

jQuery(document).ready(function ($) {

    const players = document.querySelectorAll('audio, video');

    // AirPlay Button Setup
    function setupAirPlayButton(mediaElement) {
        if (window.WebKitPlaybackTargetAvailabilityEvent) {
            mediaElement.addEventListener('webkitplaybacktargetavailabilitychanged', function (event) {
                if (event.availability === "available") {
                    const airplayButton = mediaElement.closest('.brmedia-player-container').querySelector('.brmedia-airplay-btn');
                    if (airplayButton) {
                        airplayButton.style.display = 'inline-block';
                        airplayButton.addEventListener('click', () => {
                            mediaElement.webkitShowPlaybackTargetPicker();
                        });
                    }
                }
            });
        }
    }

    // Initialize all media elements for AirPlay
    players.forEach((player) => {
        setupAirPlayButton(player);
    });

    // Chromecast Setup
    window.__onGCastApiAvailable = function (isAvailable) {
        if (isAvailable) {
            initializeCastApi();
        }
    };

    function initializeCastApi() {
        cast.framework.CastContext.getInstance().setOptions({
            receiverApplicationId: chrome.cast.media.DEFAULT_MEDIA_RECEIVER_APP_ID,
            autoJoinPolicy: chrome.cast.AutoJoinPolicy.ORIGIN_SCOPED
        });

        $('.brmedia-cast-btn').on('click', function () {
            const container = $(this).closest('.brmedia-player-container');
            const mediaEl = container.find('audio, video').get(0);
            const mediaSrc = mediaEl ? mediaEl.currentSrc : null;

            if (!mediaSrc) return;

            const mediaInfo = new chrome.cast.media.MediaInfo(mediaSrc, mediaEl.tagName === 'VIDEO' ? 'video/mp4' : 'audio/mpeg');
            const request = new chrome.cast.media.LoadRequest(mediaInfo);

            const castSession = cast.framework.CastContext.getInstance().getCurrentSession();
            if (castSession) {
                castSession.loadMedia(request).then(
                    () => console.log('Casting started'),
                    errorCode => console.error('Error starting cast', errorCode)
                );
            }
        });
    }

});