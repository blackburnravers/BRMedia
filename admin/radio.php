<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check user permissions
if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

// Define image URLs
$image_url1 = plugins_url('../assets/images/radio/shoutcast.png', __FILE__);
$image_url2 = plugins_url('../assets/images/radio/icecast.png', __FILE__);
$image_url3 = plugins_url('../assets/images/radio/facebook-live.png', __FILE__);
$image_url4 = plugins_url('../assets/images/radio/youtube-live.png', __FILE__);
$image_url5 = plugins_url('../assets/images/radio/windows-encoder.png', __FILE__);
$image_url6 = plugins_url('../assets/images/radio/azuracast.png', __FILE__);
?>

<div class="container-fluid mt-4">
    <h1 class="mb-4">BRMedia Radio</h1>
    <p>Welcome to the BRMedia plugin Radio centre. Use the cards below to navigate to organise your stations.</p>
    <div class="row">
        <!-- First radio with $image_url1 -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="<?php echo esc_url($image_url1); ?>" alt="Shoutcast" class="mb-3" style="width: auto; height: 120px;">
                    <h5 class="card-title">Shoutcast Radio Setup</h5>
                    <p class="card-text">Shoutcast is a streaming platform that lets users broadcast live audio, like music or radio shows, to listeners over the internet.</p>
                    <button class="btn btn-warning" disabled>Coming Soon</button>
                </div>
            </div>
        </div>
        <!-- Second radio with $image_url2 -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="<?php echo esc_url($image_url2); ?>" alt="Icecast" class="mb-3" style="width: auto; height: 120px;">
                    <h5 class="card-title">Icecast Radio Setup</h5>
                    <p class="card-text">Icecast is an open-source streaming service that lets users broadcast audio and video online, often used for internet radio.</p>
                    <button class="btn btn-secondary" disabled>Coming Soon</button>
                </div>
            </div>
        </div>
        <!-- Second radio with $image_url3 -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="<?php echo esc_url($image_url3); ?>" alt="Facebook Live" class="mb-3" style="width: auto; height: 120px;">
                    <h5 class="card-title">Facebook Live Setup</h5>
                    <p class="card-text">Facebook Live lets users stream live video directly to their Facebook page or profile, allowing real-time interaction with viewers.</p>
                    <button class="btn btn-primary" disabled>Coming Soon</button>
                </div>
            </div>
        </div>    
        <!-- Second radio with $image_url4 -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="<?php echo esc_url($image_url4); ?>" alt="YouTube Live" class="mb-3" style="width: auto; height: 120px;">
                    <h5 class="card-title">YouTube Live Setup</h5>
                    <p class="card-text">YouTube Live is YouTube’s platform for live video streaming, used for events, gaming, concerts, and connecting with audiences in real time.</p>
                    <button class="btn btn-danger" disabled>Coming Soon</button>
                </div>
            </div>
        </div>
        <!-- Second radio with $image_url5 -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="<?php echo esc_url($image_url5); ?>" alt="Windows Media Encoder" class="mb-3" style="width: auto; height: 120px;">
                    <h5 class="card-title">Windows Media Encoder Setup</h5>
                    <p class="card-text">Windows Media Encoder is a Microsoft tool that lets users capture, compress, and stream live or recorded audio and video over the internet.</p>
                    <button class="btn btn-success" disabled>Coming Soon</button>
                </div>
            </div>
        </div>
                <!-- Second radio with $image_url6 -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="<?php echo esc_url($image_url6); ?>" alt="AzuraCast" class="mb-3" style="width: auto; height: 120px;">
                    <h5 class="card-title">AzuraCast Setup</h5>
                    <p class="card-text">AzuraCast is a free, open-source platform that makes it easy to set up and manage internet radio stations with built-in streaming and automation tools.</p>
                    <button class="btn btn-info" disabled>Coming Soon</button>
                </div>
            </div>
        </div>
    </div>
</div>