<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check user capabilities
if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
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
                    <p class="card-text">Coming soon: Add video support to your media player.</p>
                    <button class="btn btn-warning" disabled>Coming Soon</button>
                </div>
            </div>
        </div>
    </div>
</div>