<div class="brmedia-settings-section">
    <h2><?php esc_html_e('Global Player Settings', 'brmedia'); ?></h2>
    
    <table class="form-table">
        <?php
        $settings->show_field('brmedia_general', 'default_volume');
        $settings->show_field('brmedia_general', 'default_template');
        ?>
    </table>
    
    <h3><?php esc_html_e('Advanced Configuration', 'brmedia'); ?></h3>
    <div class="brmedia-advanced-settings">
        <?php
        $settings->show_field('brmedia_general', 'cast_targets');
        $settings->show_field('brmedia_general', 'seo_metadata');
        ?>
    </div>
</div>