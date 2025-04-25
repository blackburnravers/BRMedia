<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define [brmedia] shortcode
function brmedia_shortcode($atts) {
    $atts = shortcode_atts(array(
        'track_id' => null,
        'template' => null,
    ), $atts);
    
    if (empty($atts['template'])) {
        $general_options = get_option('brmedia_general_options');
        $atts['template'] = isset($general_options['default_template']) ? $general_options['default_template'] : 'template-1';
    }
    
    if (empty($atts['track_id'])) {
        $tracks = get_posts(array('post_type' => 'brmedia_track', 'posts_per_page' => 1));
        if ($tracks) {
            $track = $tracks[0];
        } else {
            return '<p>No tracks found.</p>';
        }
    } else {
        $track = get_post($atts['track_id']);
        if (!$track || $track->post_type != 'brmedia_track') {
            return '<p>Invalid track ID.</p>';
        }
    }

    $general_options = get_option('brmedia_general_options');
    $default_volume = isset($general_options['default_volume']) ? $general_options['default_volume'] / 100 : 0.5;
    
    $template_file = plugin_dir_path(__FILE__) . '../templates/' . $atts['template'] . '.php';
    if (!file_exists($template_file)) {
        return '<p>Template not found: ' . esc_html($atts['template']) . '</p>';
    }
    
    $template_options = get_option('brmedia_template_options_' . $atts['template']);
    $settings = $template_options ?: array();
    
    ob_start();
    include $template_file;
    return ob_get_clean();
}
add_shortcode('brmedia', 'brmedia_shortcode');

// Define [brmedia_tracklist] shortcode with waveform visualization
function brmedia_tracklist_shortcode($atts) {
    global $post;
    $tracklist = get_post_meta($post->ID, 'tracklist', true);
    if (empty($tracklist)) return '<p>No tracklist available.</p>';

    $tracks = explode("\n", trim($tracklist));
    $output = '<div class="brmedia-tracklist">';
    $index = 0;
    foreach ($tracks as $track) {
        $track = trim($track);
        if (empty($track)) continue;

        $parts = explode(' - ', $track, 2);
        if (count($parts) == 2) {
            $title = $parts[0];
            $audio_url = $parts[1];
            if (!filter_var($audio_url, FILTER_VALIDATE_URL)) {
                $title = $track;
                $audio_url = '';
            }
        } else {
            $title = $track;
            $audio_url = '';
        }

        $output .= '<div class="track">';
        $output .= '<span>' . esc_html($title) . '</span>';
        if ($audio_url) {
            $output .= '<div class="mini-waveform" id="mini-waveform-' . $index . '" data-audio="' . esc_url($audio_url) . '"></div>';
        }
        $output .= '</div>';
        $index++;
    }
    $output .= '</div>';
    $output .= '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var waveforms = document.querySelectorAll(".mini-waveform");
            waveforms.forEach(function(waveform) {
                var audioUrl = waveform.getAttribute("data-audio");
                if (audioUrl) {
                    var wavesurfer = WaveSurfer.create({
                        container: waveform,
                        waveColor: "violet",
                        progressColor: "purple",
                        height: 50,
                        barWidth: 1,
                        responsive: true,
                        interact: false
                    });
                    wavesurfer.load(audioUrl);
                }
            });
        });
    </script>';
    return $output;
}
add_shortcode('brmedia_tracklist', 'brmedia_tracklist_shortcode');

// Define [brmedia_cover] shortcode
function brmedia_cover_shortcode() {
    global $post;
    $cover_image = get_the_post_thumbnail_url($post->ID, 'full');
    if (empty($cover_image)) return '<p>No cover image available.</p>';
    return '<img src="' . esc_url($cover_image) . '" alt="Cover Image" class="brmedia-cover">';
}
add_shortcode('brmedia_cover', 'brmedia_cover_shortcode');