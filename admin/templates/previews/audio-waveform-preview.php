<?php
/**
 * BRMedia Audio Waveform Template Preview
 * Displays a waveform-style audio player preview
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="brmedia-template-preview audio-waveform-preview">
    <div class="brmedia-cover">
        <img src="<?php echo BRMEDIA_URL; ?>assets/img/preview-cover.jpg" alt="Cover Art">
    </div>
    <div class="brmedia-player waveform-style">
        <div class="brmedia-track-title">Waveform Preview Track</div>
        <div class="brmedia-waveform-container">
            <canvas id="brmedia-waveform-canvas" height="60"></canvas>
        </div>
        <div class="brmedia-controls">
            <button><i class="fas fa-play"></i></button>
            <button><i class="fas fa-pause"></i></button>
        </div>
    </div>
</div>