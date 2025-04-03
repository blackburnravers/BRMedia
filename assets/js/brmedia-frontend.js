// BRMedia Frontend Logic

jQuery(document).ready(function ($) {

    // Lazy load players if needed
    $('.brmedia-player-container').each(function () {
        const $container = $(this);
        const media = $container.find('audio, video').get(0);

        if (media && typeof Plyr !== 'undefined') {
            new Plyr(media, {
                controls: ['play', 'progress', 'current-time', 'mute', 'volume', 'settings', 'fullscreen'],
                settings: ['speed', 'loop']
            });
        }
    });

    // Handle detached cover and tracklist
    $('.brmedia-tracklist-wrapper span[data-time]').on('click', function () {
        const time = $(this).data('time');
        const player = $('#' + $(this).data('target'));
        if (player.length) {
            const media = player.find('audio, video').get(0);
            if (media) {
                media.currentTime = time;
                media.play();
            }
        }
    });

    // Toggle playlist panel
    $('.brmedia-toggle-playlist').on('click', function () {
        const $panel = $(this).closest('.brmedia-player-container').find('.brmedia-tracklist');
        $panel.slideToggle(200);
    });

    // Open custom download template (if modal-style)
    $('.brmedia-download-block .brmedia-download-action').on('click', function () {
        const url = $(this).data('url');
        if (url) {
            window.open(url, '_blank');
        }
    });

    // Optional: Highlight active track in playlist
    $('audio, video').on('play', function () {
        $('.brmedia-tracklist span').removeClass('active');
    });

});