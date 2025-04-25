<?php
// Retrieve track metadata
$audio_url = get_post_meta($track->ID, 'audio_file', true);
$artist = get_post_meta($track->ID, 'artist', true);
$album = get_post_meta($track->ID, 'album', true);
$bpm = get_post_meta($track->ID, 'bpm', true);
$key = get_post_meta($track->ID, 'key', true);
$tracklist = get_post_meta($track->ID, 'tracklist', true);
$cover_image = get_the_post_thumbnail_url($track->ID, 'full');
$audio_file_path = str_replace(get_site_url(), ABSPATH, $audio_url);
$audio_metadata = brmedia_get_audio_metadata($audio_file_path);

// Define default settings
$defaults = [
    'text_color' => '#fff',
    'icon_color' => '#ffffff',
    'wave_color' => 'violet',
    'progress_color' => 'purple',
    'wave_height' => 100,
    'bar_width' => 2,
    'enable_marquee' => false,
    'enable_tracklist' => true,
    'controls' => ['play', 'volume', 'mute', 'speed', 'skip-forward', 'skip-backward', 'share', 'fullscreen', 'cast', 'shuffle'],
    'play_icon' => 'fas fa-play',
    'pause_icon' => 'fas fa-pause',
    'volume_icon' => 'fas fa-volume-up',
    'mute_icon' => 'fas fa-volume-mute',
    'speed_icon' => 'fas fa-tachometer-alt',
    'skip_forward_icon' => 'fas fa-forward',
    'skip_backward_icon' => 'fas fa-backward',
    'share_icon' => 'fas fa-share',
    'fullscreen_icon' => 'fas fa-expand',
    'cast_icon' => 'fas fa-broadcast-tower',
    'shuffle_icon' => 'fas fa-random'
];
$settings = wp_parse_args($settings, $defaults);
$selected_controls = $settings['controls'];
?>

<style>
    /* Fullscreen player container */
    .brmedia-fullscreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('<?php echo esc_url($cover_image); ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: <?php echo esc_attr($settings['text_color']); ?>;
        font-family: Arial, sans-serif;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        overflow: hidden;
    }

    /* Player container */
    .brmedia-player {
        width: 80%;
        max-width: 800px;
        margin-bottom: 20px;
        background: rgba(0, 0, 0, 0.7);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    /* Waveform styling */
    #waveform {
        width: 100%;
        border-radius: 5px;
        overflow: hidden;
    }

    /* Controls container */
    .brmedia-controls {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    /* Control buttons */
    .brmedia-controls button {
        background: none;
        border: none;
        color: <?php echo esc_attr($settings['icon_color']); ?>;
        font-size: 24px;
        cursor: pointer;
        padding: 10px;
        transition: transform 0.2s, opacity 0.2s;
    }

    .brmedia-controls button:hover {
        transform: scale(1.1);
        opacity: 0.8;
    }

    .brmedia-controls button:focus {
        outline: none;
        box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
    }

    /* Volume slider */
    .brmedia-controls input[type="range"] {
        width: 100px;
        height: 5px;
        background: #fff;
        border-radius: 5px;
        cursor: pointer;
        accent-color: <?php echo esc_attr($settings['progress_color']); ?>;
    }

    .brmedia-controls input[type="range"]::-webkit-slider-thumb {
        width: 15px;
        height: 15px;
        background: <?php echo esc_attr($settings['icon_color']); ?>;
        border-radius: 50%;
        cursor: pointer;
    }

    /* Speed control dropdown */
    .brmedia-controls select {
        background: rgba(0, 0, 0, 0.7);
        color: <?php echo esc_attr($settings['text_color']); ?>;
        border: 1px solid <?php echo esc_attr($settings['icon_color']); ?>;
        padding: 5px;
        border-radius: 5px;
        cursor: pointer;
    }

    /* Metadata section */
    .brmedia-metadata {
        text-align: center;
        margin-bottom: 20px;
        background: rgba(0, 0, 0, 0.5);
        padding: 15px;
        border-radius: 8px;
        width: 80%;
        max-width: 600px;
    }

    .brmedia-metadata p {
        margin: 5px 0;
        font-size: 16px;
    }

    .brmedia-metadata strong {
        font-weight: bold;
        margin-right: 5px;
    }

    /* Tracklist section */
    .brmedia-tracklist {
        max-height: 200px;
        overflow-y: auto;
        background: rgba(0, 0, 0, 0.5);
        padding: 15px;
        border-radius: 5px;
        width: 80%;
        max-width: 600px;
        margin-top: 20px;
    }

    .brmedia-tracklist h3 {
        margin: 0 0 10px 0;
        font-size: 18px;
    }

    .brmedia-tracklist pre {
        margin: 0;
        white-space: pre-wrap;
        font-size: 14px;
        color: <?php echo esc_attr($settings['text_color']); ?>;
    }

    /* Marquee animation for title */
    .marquee {
        white-space: nowrap;
        overflow: hidden;
        animation: marquee 10s linear infinite;
        font-size: 24px;
        font-weight: bold;
        padding: 10px;
        background: rgba(0, 0, 0, 0.6);
        border-radius: 5px;
    }

    @keyframes marquee {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }

    /* Responsive adjustments */
    @media (max-width: 600px) {
        .brmedia-player {
            width: 90%;
            padding: 10px;
        }
        .brmedia-controls {
            gap: 10px;
        }
        .brmedia-controls button {
            font-size: 20px;
            padding: 8px;
        }
        .brmedia-controls input[type="range"] {
            width: 80px;
        }
        .brmedia-metadata {
            width: 90%;
        }
        .brmedia-tracklist {
            width: 90%;
        }
        .marquee {
            font-size: 20px;
        }
    }
</style>

<div class="brmedia-fullscreen">
    <h2 class="<?php echo $settings['enable_marquee'] ? 'marquee' : ''; ?>">
        <?php echo esc_html($track->post_title); ?>
    </h2>
    <div class="brmedia-player">
        <div id="waveform"></div>
        <audio id="audio" src="<?php echo esc_url($audio_url); ?>"></audio>
    </div>
    <div class="brmedia-controls">
        <?php if (in_array('play', $selected_controls)): ?>
            <button id="play-pause-btn" aria-label="Play/Pause"><i class="<?php echo esc_attr($settings['play_icon']); ?>"></i></button>
        <?php endif; ?>
        <?php if (in_array('volume', $selected_controls)): ?>
            <input type="range" id="volume-slider" min="0" max="1" step="0.01" value="0.5" aria-label="Volume">
        <?php endif; ?>
        <?php if (in_array('mute', $selected_controls)): ?>
            <button id="mute-toggle-btn" aria-label="Mute/Unmute"><i class="<?php echo esc_attr($settings['mute_icon']); ?>"></i></button>
        <?php endif; ?>
        <?php if (in_array('speed', $selected_controls)): ?>
            <button id="speed-control-btn" aria-label="Adjust Speed"><i class="<?php echo esc_attr($settings['speed_icon']); ?>"></i></button>
            <select id="speed-control" style="display: none;" aria-label="Playback Speed">
                <option value="0.5">0.5x</option>
                <option value="1" selected>1x</option>
                <option value="1.5">1.5x</option>
                <option value="2">2x</option>
            </select>
        <?php endif; ?>
        <?php if (in_array('skip-backward', $selected_controls)): ?>
            <button id="skip-backward-btn" aria-label="Skip Backward"><i class="<?php echo esc_attr($settings['skip_backward_icon']); ?>"></i></button>
        <?php endif; ?>
        <?php if (in_array('skip-forward', $selected_controls)): ?>
            <button id="skip-forward-btn" aria-label="Skip Forward"><i class="<?php echo esc_attr($settings['skip_forward_icon']); ?>"></i></button>
        <?php endif; ?>
        <?php if (in_array('share', $selected_controls)): ?>
            <button id="share-btn" aria-label="Share"><i class="<?php echo esc_attr($settings['share_icon']); ?>"></i></button>
        <?php endif; ?>
        <?php if (in_array('fullscreen', $selected_controls)): ?>
            <button id="fullscreen-btn" aria-label="Fullscreen"><i class="<?php echo esc_attr($settings['fullscreen_icon']); ?>"></i></button>
        <?php endif; ?>
        <?php if (in_array('cast', $selected_controls)): ?>
            <button id="cast-btn" aria-label="Cast"><i class="<?php echo esc_attr($settings['cast_icon']); ?>"></i></button>
        <?php endif; ?>
        <?php if (in_array('shuffle', $selected_controls)): ?>
            <button id="shuffle-btn" aria-label="Shuffle"><i class="<?php echo esc_attr($settings['shuffle_icon']); ?>"></i></button>
        <?php endif; ?>
    </div>
    <div class="brmedia-metadata">
        <p><strong>Artist:</strong> <?php echo esc_html($artist); ?></p>
        <p><strong>Album:</strong> <?php echo esc_html($album); ?></p>
        <p><strong>BPM:</strong> <?php echo esc_html($bpm); ?></p>
        <p><strong>Key:</strong> <?php echo esc_html($key); ?></p>
        <?php if ($audio_metadata['duration']): ?>
            <p><strong>Duration:</strong> <?php echo esc_html($audio_metadata['duration']); ?></p>
        <?php endif; ?>
    </div>
    <?php if ($settings['enable_tracklist']): ?>
        <div class="brmedia-tracklist">
            <h3>Tracklist</h3>
            <pre><?php echo esc_html($tracklist); ?></pre>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize WaveSurfer
    var wavesurfer = WaveSurfer.create({
        container: '#waveform',
        waveColor: '<?php echo esc_js($settings['wave_color']); ?>',
        progressColor: '<?php echo esc_js($settings['progress_color']); ?>',
        height: <?php echo intval($settings['wave_height']); ?>,
        barWidth: <?php echo intval($settings['bar_width']); ?>,
        responsive: true,
        cursorColor: '#fff',
        cursorWidth: 2
    });
    wavesurfer.load('<?php echo esc_url($audio_url); ?>');
    wavesurfer.setVolume(0.5);

    // Track play duration
    var startTime;
    wavesurfer.on('play', function() {
        startTime = Date.now();
    });

    wavesurfer.on('pause', function() {
        var durationPlayed = Math.floor((Date.now() - startTime) / 1000);
        jQuery.post(ajaxurl, {
            action: 'brmedia_log_event',
            track_id: <?php echo $track->ID; ?>,
            action_type: 'play',
            duration_played: durationPlayed
        });
    });

    // Track share event
    var shareBtn = document.getElementById('share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', function() {
            jQuery.post(ajaxurl, {
                action: 'brmedia_log_event',
                track_id: <?php echo $track->ID; ?>,
                action_type: 'share'
            });
            navigator.share({
                title: '<?php echo esc_html($track->post_title); ?>',
                url: '<?php echo esc_url(get_permalink($track->ID)); ?>'
            });
        });
    }

    // Play/Pause button functionality
    var playPauseBtn = document.getElementById('play-pause-btn');
    if (playPauseBtn) {
        playPauseBtn.addEventListener('click', function() {
            wavesurfer.playPause();
            var icon = this.querySelector('i');
            if (wavesurfer.isPlaying()) {
                icon.classList.remove('<?php echo esc_js(str_replace('fas ', '', $settings['play_icon'])); ?>');
                icon.classList.add('<?php echo esc_js(str_replace('fas ', '', $settings['pause_icon'])); ?>');
            } else {
                icon.classList.remove('<?php echo esc_js(str_replace('fas ', '', $settings['pause_icon'])); ?>');
                icon.classList.add('<?php echo esc_js(str_replace('fas ', '', $settings['play_icon'])); ?>');
            }
        });
    }

    // Volume control
    var volumeSlider = document.getElementById('volume-slider');
    if (volumeSlider) {
        volumeSlider.addEventListener('input', function() {
            wavesurfer.setVolume(parseFloat(this.value));
        });
    }

    // Mute toggle
    var muteToggleBtn = document.getElementById('mute-toggle-btn');
    if (muteToggleBtn) {
        muteToggleBtn.addEventListener('click', function() {
            var isMuted = wavesurfer.getMute();
            wavesurfer.setMute(!isMuted);
            var icon = this.querySelector('i');
            if (isMuted) {
                icon.classList.remove('<?php echo esc_js(str_replace('fas ', '', $settings['mute_icon'])); ?>');
                icon.classList.add('<?php echo esc_js(str_replace('fas ', '', $settings['volume_icon'])); ?>');
            } else {
                icon.classList.remove('<?php echo esc_js(str_replace('fas ', '', $settings['volume_icon'])); ?>');
                icon.classList.add('<?php echo esc_js(str_replace('fas ', '', $settings['mute_icon'])); ?>');
            }
        });
    }

    // Speed control
    var speedControlBtn = document.getElementById('speed-control-btn');
    var speedControl = document.getElementById('speed-control');
    if (speedControlBtn && speedControl) {
        speedControlBtn.addEventListener('click', function() {
            speedControl.style.display = speedControl.style.display === 'none' ? 'inline-block' : 'none';
        });
        speedControl.addEventListener('change', function() {
            wavesurfer.setPlaybackRate(parseFloat(this.value));
            speedControl.style.display = 'none';
        });
    }

    // Skip backward
    var skipBackwardBtn = document.getElementById('skip-backward-btn');
    if (skipBackwardBtn) {
        skipBackwardBtn.addEventListener('click', function() {
            wavesurfer.skipBackward(10);
        });
    }

    // Skip forward
    var skipForwardBtn = document.getElementById('skip-forward-btn');
    if (skipForwardBtn) {
        skipForwardBtn.addEventListener('click', function() {
            wavesurfer.skipForward(10);
        });
    }

    // Fullscreen toggle
    var fullscreenBtn = document.getElementById('fullscreen-btn');
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', function() {
            var elem = document.querySelector('.brmedia-fullscreen');
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.mozRequestFullScreen) { // Firefox
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) { // Chrome, Safari, Opera
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) { // IE/Edge
                elem.msRequestFullscreen();
            }
        });
    }

    // Cast button (placeholder)
    var castBtn = document.getElementById('cast-btn');
    if (castBtn) {
        castBtn.addEventListener('click', function() {
            alert('Casting functionality not implemented yet.');
        });
    }

    // Shuffle button (placeholder)
    var shuffleBtn = document.getElementById('shuffle-btn');
    if (shuffleBtn) {
        shuffleBtn.addEventListener('click', function() {
            alert('Shuffle functionality not implemented yet.');
        });
    }
});
</script>