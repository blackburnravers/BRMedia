jQuery(document).ready(function($) {

    $('.brmedia-player.waveform').each(function () {
        const container = $(this);
        const waveEl = container.find('.brmedia-waveform-container')[0];
        const mediaUrl = container.data('media-url');
        const playBtn = container.find('[data-action="play"]');
        const pauseBtn = container.find('[data-action="pause"]');

        if (!waveEl || !mediaUrl) return;

        // Create WaveSurfer instance
        const wavesurfer = WaveSurfer.create({
            container: waveEl,
            waveColor: '#d9dcff',
            progressColor: '#3a7bd5',
            height: 60,
            barWidth: 2,
            responsive: true,
            normalize: true
        });

        wavesurfer.load(mediaUrl);

        // Control buttons
        playBtn.on('click', function () {
            wavesurfer.play();
        });

        pauseBtn.on('click', function () {
            wavesurfer.pause();
        });

        // Optional: Add active styling
        wavesurfer.on('play', function () {
            $('.brmedia-player.waveform').removeClass('is-playing');
            container.addClass('is-playing');
        });

        wavesurfer.on('pause', function () {
            container.removeClass('is-playing');
        });

        // Seek on click
        $(waveEl).on('click', function () {
            if (!wavesurfer.isPlaying()) {
                wavesurfer.play();
            }
        });

        // Store reference if needed later
        container.data('wavesurfer', wavesurfer);
    });

});