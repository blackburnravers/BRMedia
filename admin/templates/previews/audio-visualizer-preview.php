<?php
/**
 * BRMedia Audio Visualizer Template Preview
 * Displays a visualizer-style audio player preview
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="brmedia-template-preview audio-visualizer-preview">
    <div class="brmedia-cover">
        <img src="<?php echo BRMEDIA_URL; ?>assets/img/preview-cover.jpg" alt="Cover Art">
    </div>
    <div class="brmedia-player visualizer-style">
        <div class="brmedia-track-title">Visualizer Preview Track</div>
        <canvas id="brmedia-visualizer-canvas" height="80"></canvas>
        <div class="brmedia-controls">
            <button><i class="fas fa-play"></i></button>
            <button><i class="fas fa-stop"></i></button>
        </div>
    </div>
</div>