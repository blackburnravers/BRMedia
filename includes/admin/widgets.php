<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Register all BRMedia widgets
 */
function brmedia_register_custom_widgets() {
    register_widget('BRMedia_Featured_Media_Widget');
    register_widget('BRMedia_Latest_Music_Widget');
    register_widget('BRMedia_Latest_Video_Widget');
    register_widget('BRMedia_Random_Media_Widget');
}
add_action('widgets_init', 'brmedia_register_custom_widgets');

/**
 * Featured Media Widget
 */
class BRMedia_Featured_Media_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'brmedia_featured_widget',
            __('BRMedia: Featured Media', 'brmedia'),
            ['description' => __('Display a featured BRMedia track or video.', 'brmedia')]
        );
    }

    function widget($args, $instance) {
        $post_id = $instance['post_id'] ?? '';
        if (!$post_id || !get_post($post_id)) return;

        echo $args['before_widget'];
        echo $args['before_title'] . 'Featured Media' . $args['after_title'];

        echo do_shortcode('[brmedia_audio id="' . esc_attr($post_id) . '"]');
        echo $args['after_widget'];
    }

    function form($instance) {
        $post_id = $instance['post_id'] ?? '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('post_id'); ?>">Media Post ID:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('post_id'); ?>" name="<?php echo $this->get_field_name('post_id'); ?>" type="text" value="<?php echo esc_attr($post_id); ?>">
        </p>
        <?php
    }

    function update($new, $old) {
        return ['post_id' => sanitize_text_field($new['post_id'])];
    }
}

/**
 * Latest Music Widget
 */
class BRMedia_Latest_Music_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'brmedia_latest_music_widget',
            __('BRMedia: Latest Music', 'brmedia'),
            ['description' => __('Displays latest music entries.', 'brmedia')]
        );
    }

    function widget($args, $instance) {
        $count = $instance['count'] ?? 5;

        $query = new WP_Query([
            'post_type' => 'brmusic',
            'posts_per_page' => $count,
            'post_status' => 'publish'
        ]);

        echo $args['before_widget'];
        echo $args['before_title'] . 'Latest Music' . $args['after_title'];
        echo '<ul class="brmedia-widget-list">';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
        }
        echo '</ul>';
        echo $args['after_widget'];

        wp_reset_postdata();
    }

    function form($instance) {
        $count = $instance['count'] ?? 5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>">Number of items to show:</label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="number" value="<?php echo esc_attr($count); ?>" min="1" max="20">
        </p>
        <?php
    }

    function update($new, $old) {
        return ['count' => intval($new['count'])];
    }
}

/**
 * Latest Video Widget
 */
class BRMedia_Latest_Video_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'brmedia_latest_video_widget',
            __('BRMedia: Latest Videos', 'brmedia'),
            ['description' => __('Displays latest video posts.', 'brmedia')]
        );
    }

    function widget($args, $instance) {
        $count = $instance['count'] ?? 5;

        $query = new WP_Query([
            'post_type' => 'brvideo',
            'posts_per_page' => $count,
            'post_status' => 'publish'
        ]);

        echo $args['before_widget'];
        echo $args['before_title'] . 'Latest Videos' . $args['after_title'];
        echo '<ul class="brmedia-widget-list">';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
        }
        echo '</ul>';
        echo $args['after_widget'];

        wp_reset_postdata();
    }

    function form($instance) {
        $count = $instance['count'] ?? 5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>">Number of items to show:</label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="number" value="<?php echo esc_attr($count); ?>" min="1" max="20">
        </p>
        <?php
    }

    function update($new, $old) {
        return ['count' => intval($new['count'])];
    }
}

/**
 * Random Media Widget
 */
class BRMedia_Random_Media_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'brmedia_random_media_widget',
            __('BRMedia: Random Media', 'brmedia'),
            ['description' => __('Display a random music or video post.', 'brmedia')]
        );
    }

    function widget($args, $instance) {
        $post_type = $instance['post_type'] ?? 'brmusic';
        $query = new WP_Query([
            'post_type' => $post_type,
            'posts_per_page' => 1,
            'orderby' => 'rand',
            'post_status' => 'publish'
        ]);

        echo $args['before_widget'];
        echo $args['before_title'] . 'Random Media' . $args['after_title'];

        if ($query->have_posts()) {
            $query->the_post();
            echo '<a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a>';
        }

        echo $args['after_widget'];
        wp_reset_postdata();
    }

    function form($instance) {
        $post_type = $instance['post_type'] ?? 'brmusic';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('post_type'); ?>">Media Type:</label>
            <select name="<?php echo $this->get_field_name('post_type'); ?>" id="<?php echo $this->get_field_id('post_type'); ?>">
                <option value="brmusic" <?php selected($post_type, 'brmusic'); ?>>Music</option>
                <option value="brvideo" <?php selected($post_type, 'brvideo'); ?>>Video</option>
            </select>
        </p>
        <?php
    }

    function update($new, $old) {
        return ['post_type' => sanitize_text_field($new['post_type'])];
    }
}