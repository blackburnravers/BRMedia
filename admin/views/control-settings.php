<div class="brmedia-controls-settings">
    <h2><?php esc_html_e('Player Controls Configuration', 'brmedia'); ?></h2>
    
    <div class="control-visual-editor">
        <div class="player-preview">
            <div class="mock-player">
                <?php $this->render_mock_controls(); ?>
            </div>
        </div>
        
        <div class="control-settings">
            <table class="form-table">
                <?php
                $this->settings_api->show_field('brmedia_controls', 'enabled_controls');
                $this->settings_api->show_field('brmedia_controls', 'icon_size');
                $this->settings_api->show_field('brmedia_controls', 'tooltip_position');
                ?>
            </table>
        </div>
    </div>

    <h3><?php esc_html_e('Advanced Control Behavior', 'brmedia'); ?></h3>
    <div class="advanced-controls">
        <?php
        $this->settings_api->show_field('brmedia_controls', 'skip_duration');
        $this->settings_api->show_field('brmedia_controls', 'speed_options');
        ?>
    </div>
</div>