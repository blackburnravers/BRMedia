<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check user capabilities
if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

// Check if Footbar feature is enabled
$settings = get_option('brmedia_general_options', []);
if (empty($settings['addon_footbar'])) {
    echo '<div class="container-fluid mt-4"><p>The Footbar feature is currently disabled. Enable it in <a href="' . admin_url('admin.php?page=brmedia-general-settings') . '">General Settings</a>.</p></div>';
    return;
}
?>

<div class="container-fluid mt-4">
    <h1 class="mb-4">BRMedia Footbar</h1>
    <p>Welcome to the BRMedia plugin Footbar section. More coming soon.</p>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-bars-progress fa-3x mb-3 text-warning"></i>
                    <h5 class="card-title">BRMedia Footbar</h5>
                    <p class="card-text">Sticky Footbar player, syncs with BRMedia across the site for continuous playback.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-footbar'); ?>" class="btn btn-warning">Manage Footbar</a>
                </div>
            </div>
        </div>
    </div>
</div>