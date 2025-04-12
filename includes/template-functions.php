<?php
if (!defined('ABSPATH')) exit;

function brmedia_metadata_display($track_id = null) {
    $track_id = $track_id ?: BRMedia_Core::instance()->player->get_current_track();
    $meta = BRMedia_Core::instance()->templates->get_track_meta($track_id);
    ?>
    <div class="brmedia-meta">
        <div class="meta-item bpm">
            <span class="label"><?php esc_html_e('BPM', 'brmedia'); ?></span>
            <span class="value"><?= esc_html($meta['bpm']); ?></span>
        </div>
        <div class="meta-item key">
            <span class="label"><?php esc_html_e('Key', 'brmedia'); ?></span>
            <span class="value"><?= esc_html($meta['key']); ?></span>
        </div>
    </div>
    <?php
}

function brmedia_control_buttons() {
    $controls = BRMedia_Core::instance()->player->get_enabled_controls();
    ?>
    <div class="brmedia-controls">
        <?php foreach ($controls as $control) : ?>
            <button class="control-<?= esc_attr($control) ?>" 
                    data-action="<?= esc_attr($control) ?>">
                <i class="<?= brmedia_control_icon($control) ?>"></i>
            </button>
        <?php endforeach; ?>
    </div>
    <?php
}

function brmedia_control_icon($control) {
    $icons = [
        'play' => 'fas fa-play',
        'pause' => 'fas fa-pause',
        'volume' => 'fas fa-volume-up',
        'fullscreen' => 'fas fa-expand'
    ];
    return $icons[$control] ?? 'fas fa-question-circle';
}

function brmedia_progress_bar() {
    ?>
    <div class="brmedia-progress">
        <div class="progress-bar">
            <div class="progress-fill" style="width: 0%"></div>
        </div>
        <div class="time-display">
            <span class="current-time">0:00</span> / 
            <span class="duration">0:00</span>
        </div>
    </div>
    <?php
}