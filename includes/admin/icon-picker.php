<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

function brmedia_render_icon_picker_modal() {
    ?>
    <!-- BRMedia Icon Picker Modal -->
    <div id="brmedia-icon-picker-modal" style="display:none;">
        <div class="brmedia-icon-picker-overlay"></div>
        <div class="brmedia-icon-picker-content">
            <div class="brmedia-icon-picker-header">
                <h2>Select an Icon</h2>
                <input type="text" id="brmedia-icon-search" placeholder="Search icons..." />
                <button type="button" class="brmedia-icon-picker-close">&times;</button>
            </div>
            <div class="brmedia-icon-list">
                <?php
                $icons = [
                    'fas fa-play', 'fas fa-pause', 'fas fa-stop', 'fas fa-forward', 'fas fa-backward',
                    'fas fa-volume-up', 'fas fa-volume-mute', 'fas fa-random', 'fas fa-redo', 'fas fa-download',
                    'fas fa-headphones', 'fas fa-music', 'fas fa-video', 'fas fa-list', 'fas fa-cog',
                    'fas fa-external-link-alt', 'fas fa-expand', 'fas fa-compress', 'fas fa-plus', 'fas fa-minus',
                    'fas fa-microphone', 'fas fa-eject', 'fas fa-thumbs-up', 'fas fa-thumbs-down',
                    'fas fa-arrow-circle-up', 'fas fa-arrow-circle-down', 'fas fa-heart', 'fas fa-star',
                    'fas fa-clock', 'fas fa-globe', 'fas fa-tv', 'fas fa-cast', 'fas fa-broadcast-tower',
                    'fas fa-angle-double-right', 'fas fa-angle-double-left', 'fas fa-play-circle', 'fas fa-stop-circle',
                    'fas fa-check-circle', 'fas fa-times-circle', 'fas fa-chart-bar', 'fas fa-podcast',
                    'fas fa-equals', 'fas fa-bars', 'fas fa-sliders-h', 'fas fa-sync', 'fas fa-repeat', 'fas fa-wave-square'
                ];
                foreach ($icons as $icon) {
                    echo '<div class="brmedia-icon-item" data-icon="' . esc_attr($icon) . '"><i class="' . esc_attr($icon) . '"></i></div>';
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}
add_action('admin_footer', 'brmedia_render_icon_picker_modal');