<?php
/**
 * BRMedia Settings Page View
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
$tabs = array(
    'general'    => __('General Settings', 'brmedia'),
    'player'     => __('Player Settings', 'brmedia'),
    'templates'  => __('Templates', 'brmedia'),
    'social'     => __('Social Sharing', 'brmedia'),
    'advanced'   => __('Advanced', 'brmedia')
);
?>

<div class="wrap brmedia-settings">
    <header class="brmedia-settings__header">
        <h1>
            <i class="fas fa-cog"></i> 
            <?php _e('BRMedia Settings', 'brmedia'); ?>
        </h1>
        <div class="brmedia-version">v<?php echo BRMEDIA_VERSION; ?></div>
    </header>

    <div class="brmedia-settings__nav">
        <h2 class="nav-tab-wrapper">
            <?php foreach ($tabs as $tab_id => $tab_name) : ?>
                <a href="?page=brmedia-settings&tab=<?php echo $tab_id; ?>" 
                   class="nav-tab <?php echo $active_tab == $tab_id ? 'nav-tab-active' : ''; ?>">
                    <?php echo $tab_name; ?>
                </a>
            <?php endforeach; ?>
        </h2>
    </div>

    <form method="post" action="options.php" class="brmedia-settings__form">
        <?php
        settings_fields('brmedia_settings_' . $active_tab);
        do_settings_sections('brmedia_settings_' . $active_tab);
        submit_button();
        ?>
    </form>

    <?php // Tab-specific content ?>
    <div class="brmedia-settings__content">
        <?php if ($active_tab === 'general') : ?>
            <div class="brmedia-settings__card">
                <h3><i class="fas fa-sliders-h"></i> <?php _e('General Configuration', 'brmedia'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Default Volume', 'brmedia'); ?></th>
                        <td>
                            <input type="range" name="brmedia_default_volume" min="0" max="100" 
                                   value="<?php echo esc_attr(get_option('brmedia_default_volume', 80)); ?>">
                            <span class="brmedia-range-value"><?php echo esc_attr(get_option('brmedia_default_volume', 80)); ?>%</span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Enable AirPlay', 'brmedia'); ?></th>
                        <td>
                            <label class="brmedia-switch">
                                <input type="checkbox" name="brmedia_enable_airplay" 
                                    <?php checked(get_option('brmedia_enable_airplay', true)); ?>>
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Enable Cast', 'brmedia'); ?></th>
                        <td>
                            <label class="brmedia-switch">
                                <input type="checkbox" name="brmedia_enable_cast" 
                                    <?php checked(get_option('brmedia_enable_cast', true)); ?>>
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>

        <?php elseif ($active_tab === 'player') : ?>
            <div class="brmedia-settings__card">
                <h3><i class="fas fa-play-circle"></i> <?php _e('Player Appearance', 'brmedia'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Primary Color', 'brmedia'); ?></th>
                        <td>
                            <input type="color" name="brmedia_color_primary" 
                                   value="<?php echo esc_attr(get_option('brmedia_color_primary', '#3a7bd5')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Secondary Color', 'brmedia'); ?></th>
                        <td>
                            <input type="color" name="brmedia_color_secondary" 
                                   value="<?php echo esc_attr(get_option('brmedia_color_secondary', '#00d2ff')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Default Player Skin', 'brmedia'); ?></th>
                        <td>
                            <select name="brmedia_player_skin">
                                <option value="stylish" <?php selected(get_option('brmedia_player_skin'), 'stylish'); ?>>
                                    <?php _e('Stylish Player', 'brmedia'); ?>
                                </option>
                                <option value="waveform" <?php selected(get_option('brmedia_player_skin'), 'waveform'); ?>>
                                    <?php _e('Waveform Player', 'brmedia'); ?>
                                </option>
                                <option value="visualization" <?php selected(get_option('brmedia_player_skin'), 'visualization'); ?>>
                                    <?php _e('Visualization Player', 'brmedia'); ?>
                                </option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

        <?php elseif ($active_tab === 'templates') : ?>
            <div class="brmedia-settings__card">
                <h3><i class="fas fa-paint-brush"></i> <?php _e('Template Customization', 'brmedia'); ?></h3>
                <div class="brmedia-templates-grid">
                    <div class="brmedia-template-option">
                        <h4><?php _e('Stylish Player', 'brmedia'); ?></h4>
                        <div class="template-preview" style="background-image: url(<?php echo BRMEDIA_URL; ?>assets/images/preview-stylish.jpg)"></div>
                        <a href="<?php echo admin_url('admin.php?page=brmedia-settings&tab=templates&template=stylish'); ?>" 
                           class="button button-primary">
                            <?php _e('Customize', 'brmedia'); ?>
                        </a>
                    </div>
                    <div class="brmedia-template-option">
                        <h4><?php _e('Waveform Player', 'brmedia'); ?></h4>
                        <div class="template-preview" style="background-image: url(<?php echo BRMEDIA_URL; ?>assets/images/preview-waveform.jpg)"></div>
                        <a href="<?php echo admin_url('admin.php?page=brmedia-settings&tab=templates&template=waveform'); ?>" 
                           class="button button-primary">
                            <?php _e('Customize', 'brmedia'); ?>
                        </a>
                    </div>
                    <div class="brmedia-template-option">
                        <h4><?php _e('Visualization Player', 'brmedia'); ?></h4>
                        <div class="template-preview" style="background-image: url(<?php echo BRMEDIA_URL; ?>assets/images/preview-visualization.jpg)"></div>
                        <a href="<?php echo admin_url('admin.php?page=brmedia-settings&tab=templates&template=visualization'); ?>" 
                           class="button button-primary">
                            <?php _e('Customize', 'brmedia'); ?>
                        </a>
                    </div>
                </div>
            </div>

        <?php elseif ($active_tab === 'social') : ?>
            <div class="brmedia-settings__card">
                <h3><i class="fas fa-share-alt"></i> <?php _e('Social Sharing Settings', 'brmedia'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Enable Sharing', 'brmedia'); ?></th>
                        <td>
                            <label class="brmedia-switch">
                                <input type="checkbox" name="brmedia_enable_sharing" 
                                    <?php checked(get_option('brmedia_enable_sharing', true)); ?>>
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Available Platforms', 'brmedia'); ?></th>
                        <td>
                            <?php
                            $platforms = array(
                                'facebook'  => 'Facebook',
                                'x'         => 'X',
                                'whatsapp'  => 'WhatsApp',
                                'telegram'  => 'Telegram',
                                'reddit'    => 'Reddit',
                                'linkedin'  => 'LinkedIn',
                                'tumblr'    => 'Tumblr',
                                'pinterest' => 'Pinterest'
                            );
                            $selected = get_option('brmedia_social_platforms', array('facebook', 'x', 'whatsapp'));
                            
                            foreach ($platforms as $key => $name) : ?>
                                <label class="brmedia-checkbox-label">
                                    <input type="checkbox" name="brmedia_social_platforms[]" 
                                           value="<?php echo esc_attr($key); ?>" 
                                        <?php checked(in_array($key, $selected)); ?>>
                                    <span class="brmedia-social-icon brmedia-social-<?php echo $key; ?>">
                                        <i class="fab fa-<?php echo $key; ?>"></i>
                                    </span>
                                    <?php echo esc_html($name); ?>
                                </label><br>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                </table>
            </div>

        <?php elseif ($active_tab === 'advanced') : ?>
            <div class="brmedia-settings__card">
                <h3><i class="fas fa-user-shield"></i> <?php _e('Advanced Options', 'brmedia'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Cleanup on Uninstall', 'brmedia'); ?></th>
                        <td>
                            <label class="brmedia-switch">
                                <input type="checkbox" name="brmedia_cleanup_on_uninstall" 
                                    <?php checked(get_option('brmedia_cleanup_on_uninstall', false)); ?>>
                                <span class="slider round"></span>
                            </label>
                            <p class="description">
                                <?php _e('When enabled, all BRMedia data will be deleted when uninstalling the plugin.', 'brmedia'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Debug Mode', 'brmedia'); ?></th>
                        <td>
                            <label class="brmedia-switch">
                                <input type="checkbox" name="brmedia_debug_mode" 
                                    <?php checked(get_option('brmedia_debug_mode', false)); ?>>
                                <span class="slider round"></span>
                            </label>
                            <p class="description">
                                <?php _e('Enable this to log debug information for troubleshooting.', 'brmedia'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Update range value display
    $('input[type="range"]').on('input', function() {
        $(this).next('.brmedia-range-value').text($(this).val() + '%');
    });
});
</script>