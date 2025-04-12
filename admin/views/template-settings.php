<?php
$current_template = $this->settings_api->get_option('default_template', 'brmedia_general', 'template-1');
?>
<div class="brmedia-template-settings">
    <h2><?php esc_html_e('Template Customization', 'brmedia'); ?></h2>
    
    <div class="template-presets">
        <h3><?php esc_html_e('Available Templates', 'brmedia'); ?></h3>
        <div class="brmedia-template-preview-grid">
            <?php foreach (['template-1', 'template-2', 'template-3', 'template-fullscreen'] as $template) : ?>
                <div class="template-card <?= $template === $current_template ? 'active' : '' ?>">
                    <img src="<?= esc_url(BRMEDIA_URL . 'assets/admin/previews/' . $template . '.jpg') ?>" 
                         alt="<?= esc_attr(ucfirst(str_replace('-', ' ', $template))) ?> Preview">
                    <div class="template-actions">
                        <button class="button-primary set-default-template" 
                                data-template="<?= esc_attr($template) ?>">
                            <?= $template === $current_template ? 
                                __('Active', 'brmedia') : 
                                __('Set Default', 'brmedia') ?>
                        </button>
                        <a href="#" class="button customize-template" 
                           data-template="<?= esc_attr($template) ?>">
                            <?php esc_html_e('Customize', 'brmedia'); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="template-customization">
        <h3><?php esc_html_e('Advanced Styling', 'brmedia'); ?></h3>
        <table class="form-table">
            <?php
            $this->settings_api->show_field('brmedia_templates', 'player_bg');
            $this->settings_api->show_field('brmedia_templates', 'control_icons');
            $this->settings_api->show_field('brmedia_templates', 'hover_effects');
            ?>
        </table>
    </div>
</div>