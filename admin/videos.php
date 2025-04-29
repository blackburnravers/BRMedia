<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check user capabilities
if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

// Check if Videos feature is enabled
$settings = get_option('brmedia_general_options', []);
if (empty($settings['addon_video'])) {
    echo '<div class="container-fluid mt-4"><p>The Videos feature is currently disabled. Enable it in <a href="' . admin_url('admin.php?page=brmedia-general-settings') . '">General Settings</a>.</p></div>';
    return;
}
?>

<div class="container-fluid mt-4">
    <h1 class="mb-4">BRMedia Videos</h1>
    <p>Welcome to the BRMedia plugin Video section. More coming soon.</p>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-video fa-3x mb-3 text-warning"></i>
                    <h5 class="card-title">BRMedia Videos</h5>
                    <p class="card-text">Add video support to your media player.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-videos'); ?>" class="btn btn-warning">Manage Videos</a>
                </div>
            </div>
        </div>
    </div>
</div>