jQuery(document).ready(function($) {

    // Auto-highlight current playing media
    const players = $('.brmedia-player');

    players.each(function () {
        const player = $(this);
        const audio = player.find('audio')[0];

        if (!audio) return;

        audio.addEventListener('play', function () {
            players.removeClass('is-playing');
            player.addClass('is-playing');
        });

        audio.addEventListener('pause', function () {
            player.removeClass('is-playing');
        });
    });

    // Scroll into view when play is triggered
    $('.brmedia-player [data-action="play"]').on('click', function () {
        const wrapper = $(this).closest('.brmedia-player');
        $('html, body').animate({
            scrollTop: wrapper.offset().top - 100
        }, 500);
    });

    // Toggle tracklist
    $('.brmedia-toggle-tracklist').on('click', function () {
        const box = $(this).closest('.brmedia-player').find('.brmedia-tracklist');
        box.slideToggle(200);
    });

});