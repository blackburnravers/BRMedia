<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Add Templates Settings Page to the BRMedia Menu
 */
function brmedia_add_templates_settings_page() {
    add_submenu_page(
        'brmedia-dashboard',
        'BRMedia Templates',
        'Templates',
        'manage_options',
        'brmedia-templates',
        'brmedia_render_templates_settings'
    );
}
add_action('admin_menu', 'brmedia_add_templates_settings_page');

/**
 * Render the Templates Settings Page
 */
function brmedia_render_templates_settings() {
    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'audio';
    ?>

    <div class="wrap brmedia-admin-container">
        <h1>BRMedia Templates Settings</h1>

        <h2 class="nav-tab-wrapper">
            <a href="?page=brmedia-templates&tab=audio" class="nav-tab <?php echo ($active_tab === 'audio') ? 'nav-tab-active' : ''; ?>">Audio Templates</a>
            <a href="?page=brmedia-templates&tab=video" class="nav-tab <?php echo ($active_tab === 'video') ? 'nav-tab-active' : ''; ?>">Video Templates</a>
        </h2>

        <div class="brmedia-tab-content">
            <?php
            if ($active_tab === 'audio') {
                include plugin_dir_path(__FILE__) . 'templates-audio-settings.php';
            } elseif ($active_tab === 'video') {
                include plugin_dir_path(__FILE__) . 'templates-video-settings.php';
            } else {
                echo '<p>Invalid tab selection.</p>';
            }
            ?>
        </div>
    </div>

    <?php
}