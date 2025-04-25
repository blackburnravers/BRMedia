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
    <h1 class="mb-4">BRMedia Shortcodes</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <i class="fas fa-code fa-3x mb-4 text-secondary"></i>
            <p class="lead">Use the following shortcodes to embed the BRMedia player or its components in your posts or pages.</p>
            
            <h3 class="mt-4">[brmedia]</h3>
            <p>Embeds the music player with the specified track and template.</p>
            <h5>Attributes:</h5>
            <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item"><code>track_id</code>: The ID of the track to play. If not specified, the first track is used.</li>
                <li class="list-group-item"><code>template</code>: The template to use (e.g., "template-1", "template-2"). Defaults to the default template set in settings.</li>
            </ul>
            <h5>Example:</h5>
            <pre class="bg-light p-3 rounded">[brmedia track_id="123" template="template-1"]</pre>

            <h3 class="mt-4">[brmedia_tracklist]</h3>
            <p>Displays the tracklist of the current track, with waveforms for tracks that include audio URLs.</p>
            <h5>Example:</h5>
            <pre class="bg-light p-3 rounded">[brmedia_tracklist]</pre>
            <p><strong>Note:</strong> The tracklist should be formatted as "Track Title - URL" to enable waveform visualization.</p>

            <h3 class="mt-4">[brmedia_cover]</h3>
            <p>Displays the cover image of the current track.</p>
            <h5>Example:</h5>
            <pre class="bg-light p-3 rounded">[brmedia_cover]</pre>
        </div>
    </div>
</div>