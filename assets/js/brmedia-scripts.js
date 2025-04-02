// BRMedia Frontend Scripts

jQuery(document).ready(function ($) {

    // Track plays
    $('audio.plyr, video.plyr').each(function () {
        const media = this;
        const $media = $(this);

        if (!media.dataset.brmediaTracked) {
            media.addEventListener('play', function () {
                const postId = $media.closest('[data-post-id]').data('post-id');
                if (postId) {
                    $.post(brmediaAjax.ajaxurl, {
                        action: 'brmedia_update_media_stats',
                        post_id: postId,
                        action_type: 'play'
                    });
                }
            });

            media.dataset.brmediaTracked = true;
        }
    });

    // Track download clicks
    $('.brmedia-download-btn').on('click', function () {
        const postId = $(this).closest('[data-post-id]').data('post-id');
        if (postId) {
            $.post(brmediaAjax.ajaxurl, {
                action: 'brmedia_update_media_stats',
                post_id: postId,
                action_type: 'download'
            });
        }
    });

    // Popup open/close triggers
    $('.brmedia-popup-open-btn').on('click', function () {
        $('#brmedia-popup-player').fadeIn(300);
    });

    $('.popup-close-btn').on('click', function () {
        $('#brmedia-popup-player').fadeOut(200);
    });

    // Fullscreen player open/close
    $('.brmedia-fullscreen-open-btn').on('click', function () {
        $('#brmedia-fullscreen-player').fadeIn(300);
    });

    $('.fullscreen-close-btn').on('click', function () {
        $('#brmedia-fullscreen-player').fadeOut(200);
    });

    // Tracklist timestamps
    $('.brmedia-tracklist span[data-time]').on('click', function () {
        const time = $(this).data('time');
        const audio = $(this).closest('.brmedia-player-container').find('audio').get(0);
        if (audio) {
            audio.currentTime = time;
            audio.play();
        }
    });

    // Enable Plyr globally
    if (typeof Plyr !== 'undefined') {
        const players = Plyr.setup('audio.plyr, video.plyr', {
            controls: ['play', 'progress', 'current-time', 'mute', 'volume', 'settings', 'fullscreen'],
            settings: ['speed', 'loop'],
        });
    }

});