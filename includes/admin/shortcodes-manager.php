<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

function brmedia_shortcodes_page() {
    ?>
    <div class="wrap brmedia-admin">
        <h1>BRMedia Shortcodes Manager</h1>
        <p>Use the buttons below to copy shortcodes for use in posts, pages, or widgets.</p>

        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Shortcode</th>
                    <th>Copy</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $args = array(
                    'post_type'      => array('music', 'video'),
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                );
                $media_posts = get_posts($args);

                if ($media_posts) {
                    foreach ($media_posts as $post) {
                        $post_id = $post->ID;
                        $title   = esc_html(get_the_title($post_id));
                        $type    = get_post_type($post_id);
                        $label   = ($type === 'music') ? 'Music' : 'Video';

                        $shortcodes = array(
                            'Player'     => "[brmedia_player id=\"$post_id\"]",
                            'Tracklist'  => "[brmedia_tracklist id=\"$post_id\"]",
                            'Cover'      => "[brmedia_cover id=\"$post_id\"]",
                            'Download'   => "[brmedia_download id=\"$post_id\"]",
                        );

                        foreach ($shortcodes as $name => $code) {
                            echo '<tr>';
                            echo "<td>$label - $name</td>";
                            echo "<td><code id=\"shortcode-$post_id-$name\">$code</code></td>";
                            echo "<td><button class=\"button brmedia-copy-btn\" data-target=\"shortcode-$post_id-$name\">Copy</button></td>";
                            echo '</tr>';
                        }
                    }
                } else {
                    echo '<tr><td colspan="3">No music or video posts found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.brmedia-copy-btn');
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-target');
                const code = document.getElementById(targetId).innerText;
                navigator.clipboard.writeText(code).then(() => {
                    button.innerText = 'Copied!';
                    setTimeout(() => { button.innerText = 'Copy'; }, 1500);
                });
            });
        });
    });
    </script>

    <style>
    .brmedia-copy-btn {
        font-size: 12px;
        padding: 5px 10px;
    }
    code {
        background: #f1f1f1;
        padding: 4px 6px;
        display: inline-block;
    }
    </style>
    <?php
}