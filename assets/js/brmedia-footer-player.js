// BRMedia Footer Player Logic

jQuery(document).ready(function ($) {

    const $footerPlayer = $('#brmedia-footer-player');
    const $audio = $footerPlayer.find('audio');
    const $playBtn = $footerPlayer.find('.brmedia-footer-play');
    const $pauseBtn = $footerPlayer.find('.brmedia-footer-pause');
    const $progressBar = $footerPlayer.find('.brmedia-footer-progress');
    const $title = $footerPlayer.find('.brmedia-footer-title');
    const $cover = $footerPlayer.find('.brmedia-footer-cover img');

    let audio = $audio.get(0);

    // Play Button
    $playBtn.on('click', function () {
        audio.play();
    });

    // Pause Button
    $pauseBtn.on('click', function () {
        audio.pause();
    });

    // Update Progress
    audio.addEventListener('timeupdate', function () {
        const percent = (audio.currentTime / audio.duration) * 100;
        $progressBar.css('width', percent + '%');
    });

    // Auto update player UI when new track is set
    function updateFooterPlayer(data) {
        if (!data) return;

        $audio.attr('src', data.audio_url);
        $title.text(data.title || 'Untitled');
        if (data.cover_url) $cover.attr('src', data.cover_url);

        audio.load();
        audio.play();
    }

    // Global function for setting footer player data (can be triggered from other templates)
    window.setBRMediaFooterPlayer = function (postId, callback) {
        $.ajax({
            url: brmediaAjax.ajaxurl,
            method: 'POST',
            data: {
                action: 'brmedia_get_footer_player_data',
                post_id: postId
            },
            success: function (res) {
                if (res.success) {
                    updateFooterPlayer(res.data);
                    if (callback) callback(res.data);
                }
            }
        });
    };

    // Clicking any .footer-play-trigger will set footer player
    $('.footer-play-trigger').on('click', function (e) {
        e.preventDefault();
        const postId = $(this).data('post-id');
        if (postId) {
            window.setBRMediaFooterPlayer(postId);
        }
    });

});