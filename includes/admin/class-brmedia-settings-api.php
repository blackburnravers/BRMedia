<?php
/**
 * BRMedia Settings API
 * A lightweight settings API for WordPress plugins.
 */

class BRMedia_Settings_API {
    private $settings_sections = array();
    private $settings_fields = array();

    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }

    public function admin_enqueue_scripts($hook) {
        if ($hook != 'brmedia_page_brmedia-settings') {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('brmedia-settings', BRMEDIA_URL . 'assets/css/settings.css', array(), BRMEDIA_VERSION);

        wp_enqueue_media();
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('jquery');
        wp_enqueue_script('brmedia-settings', BRMEDIA_URL . 'assets/js/settings.js', array('jquery'), BRMEDIA_VERSION, true);
    }

    public function set_sections($sections) {
        $this->settings_sections = $sections;
        return $this;
    }

    public function add_section($section) {
        $this->settings_sections[] = $section;
        return $this;
    }

    public function set_fields($fields) {
        $this->settings_fields = $fields;
        return $this;
    }

    public function add_field($section, $field) {
        $defaults = array(
            'name'  => '',
            'label' => '',
            'desc'  => '',
            'type'  => 'text'
        );

        $this->settings_fields[$section][] = wp_parse_args($field, $defaults);
        return $this;
    }

    public function admin_init() {
        // Register settings sections
        foreach ($this->settings_sections as $section) {
            if (false == get_option($section['id'])) {
                add_option($section['id']);
            }

            add_settings_section(
                $section['id'],
                $section['title'],
                array($this, 'section_callback'),
                $section['id']
            );
        }

        // Register settings fields
        foreach ($this->settings_fields as $section => $field) {
            foreach ($field as $option) {
                $args = array(
                    'id'                => $option['name'],
                    'label_for'         => $option['name'],
                    'desc'              => isset($option['desc']) ? $option['desc'] : '',
                    'name'              => $option['label'],
                    'section'           => $section,
                    'size'              => isset($option['size']) ? $option['size'] : null,
                    'options'           => isset($option['options']) ? $option['options'] : '',
                    'std'               => isset($option['default']) ? $option['default'] : '',
                    'sanitize_callback' => isset($option['sanitize_callback']) ? $option['sanitize_callback'] : '',
                    'type'              => $option['type'],
                    'placeholder'       => isset($option['placeholder']) ? $option['placeholder'] : '',
                    'min'               => isset($option['min']) ? $option['min'] : '',
                    'max'               => isset($option['max']) ? $option['max'] : '',
                    'step'              => isset($option['step']) ? $option['step'] : ''
                );

                add_settings_field(
                    $section . '[' . $option['name'] . ']',
                    $args['label_for'],
                    array($this, 'callback_' . $option['type']),
                    $section,
                    $section,
                    $args
                );
            }

            // Register settings
            register_setting($section, $section, array($this, 'sanitize_options'));
        }
    }

    public function section_callback($args) {
        // Optional section description output
        foreach ($this->settings_sections as $section) {
            if ($section['id'] == $args['id'] && isset($section['desc'])) {
                echo '<div class="brmedia-section-desc">' . esc_html($section['desc']) . '</div>';
                break;
            }
        }
    }

    public function callback_text($args) {
        $value = isset($this->get_option($args['id'], $args['section'], $args['std'])) ? $this->get_option($args['id'], $args['section'], $args['std']) : '';
        $size  = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $html  = sprintf(
            '<input type="text" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" placeholder="%5$s"/>',
            $size,
            $args['section'],
            $args['id'],
            esc_attr($value),
            esc_attr($args['placeholder'])
        );
        $html .= $this->get_field_description($args);

        echo $html;
    }

    public function callback_number($args) {
        $value = isset($this->get_option($args['id'], $args['section'], $args['std'])) ? $this->get_option($args['id'], $args['section'], $args['std']) : '';
        $size  = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $html  = sprintf(
            '<input type="number" class="%1$s-number" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" placeholder="%5$s" min="%6$s" max="%7$s" step="%8$s"/>',
            $size,
            $args['section'],
            $args['id'],
            esc_attr($value),
            esc_attr($args['placeholder']),
            esc_attr($args['min']),
            esc_attr($args['max']),
            esc_attr($args['step'])
        );
        $html .= $this->get_field_description($args);

        echo $html;
    }

    public function callback_range($args) {
        $value = isset($this->get_option($args['id'], $args['section'], $args['std'])) ? $this->get_option($args['id'], $args['section'], $args['std']) : $args['std'];
        $html  = sprintf(
            '<div class="brmedia-range-control"><input type="range" class="brmedia-range" id="%1$s[%2$s]" name="%1$s[%2$s]" value="%3$s" min="%4$s" max="%5$s" step="%6$s" data-show-value="true"/>',
            $args['section'],
            $args['id'],
            esc_attr($value),
            esc_attr($args['min']),
            esc_attr($args['max']),
            esc_attr($args['step'])
        );
        $html .= sprintf('<span class="brmedia-range-value">%s</span></div>', esc_attr($value));
        $html .= $this->get_field_description($args);

        echo $html;
    }

    public function callback_checkbox($args) {
        $value = isset($this->get_option($args['id'], $args['section'], $args['std'])) ? $this->get_option($args['id'], $args['section'], $args['std']) : '';
        $html  = '<fieldset>';
        $html .= sprintf(
            '<label for="%1$s[%2$s]" class="brmedia-switch"><input type="checkbox" class="checkbox" id="%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s/><span class="slider round"></span></label>',
            $args['section'],
            $args['id'],
            checked($value, 'on', false)
        );
        $html .= sprintf('<span class="description">%s</span>', $args['desc']);
        $html .= '</fieldset>';

        echo $html;
    }

    public function callback_multicheck($args) {
        $value = $this->get_option($args['id'], $args['section'], $args['std']);
        $html  = '<fieldset>';
        foreach ($args['options'] as $key => $label) {
            $checked = isset($value[$key]) ? $value[$key] : '0';
            $html   .= sprintf(
                '<label for="%1$s[%2$s][%3$s]" class="brmedia-checkbox-label"><input type="checkbox" class="checkbox" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s/>%5$s</label><br>',
                $args['section'],
                $args['id'],
                $key,
                checked($checked, $key, false),
                $label
            );
        }
        $html .= $this->get_field_description($args);
        $html .= '</fieldset>';

        echo $html;
    }

    public function callback_radio($args) {
        $value = $this->get_option($args['id'], $args['section'], $args['std']);
        $html  = '<fieldset>';
        foreach ($args['options'] as $key => $label) {
            $html .= sprintf(
                '<label for="%1$s[%2$s][%3$s]"><input type="radio" class="radio" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s/>%5$s</label><br>',
                $args['section'],
                $args['id'],
                $key,
                checked($value, $key, false),
                $label
            );
        }
        $html .= $this->get_field_description($args);
        $html .= '</fieldset>';

        echo $html;
    }

    public function callback_select($args) {
        $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
        $size  = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $html  = sprintf(
            '<select class="%1$s" id="%2$s[%3$s]" name="%2$s[%3$s]">',
            $size,
            $args['section'],
            $args['id']
        );

        foreach ($args['options'] as $key => $label) {
            $html .= sprintf(
                '<option value="%s"%s>%s</option>',
                $key,
                selected($value, $key, false),
                $label
            );
        }

        $html .= '</select>';
        $html .= $this->get_field_description($args);

        echo $html;
    }

    public function callback_textarea($args) {
        $value = esc_textarea($this->get_option($args['id'], $args['section'], $args['std']));
        $size  = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $html  = sprintf(
            '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" placeholder="%4$s">%5$s</textarea>',
            $size,
            $args['section'],
            $args['id'],
            esc_attr($args['placeholder']),
            $value
        );
        $html .= $this->get_field_description($args);

        echo $html;
    }

    public function callback_color($args) {
        $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
        $size  = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $html  = sprintf(
            '<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s"/>',
            $size,
            $args['section'],
            $args['id'],
            $value,
            $args['std']
        );
        $html .= $this->get_field_description($args);

        echo $html;
    }

    public function sanitize_options($options) {
        if (!$options) {
            return $options;
        }

        foreach ($options as $option_slug => $option_value) {
            $sanitize_callback = $this->get_sanitize_callback($option_slug);

            // If callback is set, call it
            if ($sanitize_callback) {
                $options[$option_slug] = call_user_func($sanitize_callback, $option_value);
                continue;
            }

            // Treat everything that's not an array as text
            if (!is_array($option_value)) {
                $options[$option_slug] = sanitize_text_field($option_value);
            }
        }

        return $options;
    }

    protected function get_sanitize_callback($slug = '') {
        if (empty($slug)) {
            return false;
        }

        // Iterate over registered fields and see if we can find proper callback
        foreach ($this->settings_fields as $section => $options) {
            foreach ($options as $option) {
                if ($option['name'] != $slug) {
                    continue;
                }

                // Return the callback name
                return isset($option['sanitize_callback']) && is_callable($option['sanitize_callback']) 
                    ? $option['sanitize_callback'] 
                    : false;
            }
        }

        return false;
    }

    protected function get_field_description($args) {
        if (!empty($args['desc'])) {
            $desc = sprintf('<p class="description">%s</p>', $args['desc']);
        } else {
            $desc = '';
        }

        return $desc;
    }

    public function show_navigation() {
        echo '<h2 class="nav-tab-wrapper brmedia-tab-wrapper">';
        foreach ($this->settings_sections as $tab) {
            printf(
                '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>',
                $tab['id'],
                $tab['title']
            );
        }
        echo '</h2>';
    }

    public function show_forms() {
        ?>
        <div class="metabox-holder">
            <div class="brmedia-settings-content">
                <?php foreach ($this->settings_sections as $form) { ?>
                    <div id="<?php echo $form['id']; ?>" class="brmedia-settings-section">
                        <form method="post" action="options.php">
                            <?php
                            do_action('brmedia_form_top_' . $form['id'], $form);
                            settings_fields($form['id']);
                            do_settings_sections($form['id']);
                            do_action('brmedia_form_bottom_' . $form['id'], $form);
                            
                            if (isset($this->settings_fields[$form['id']])):
                            ?>
                            <div class="brmedia-settings-footer">
                                <?php submit_button(); ?>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        $this->script();
    }

    public function script() {
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Tab navigation
            $('.brmedia-tab-wrapper a').click(function(e) {
                e.preventDefault();
                $('.brmedia-tab-wrapper a').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                $('.brmedia-settings-section').hide();
                $($(this).attr('href')).show();
            });

            // Show first tab by default
            $('.brmedia-tab-wrapper a:first').click();
            
            // Range value display
            $('.brmedia-range').on('input change', function() {
                $(this).next('.brmedia-range-value').text($(this).val());
            });
            
            // Color picker
            if ($('.wp-color-picker-field').length > 0) {
                $('.wp-color-picker-field').wpColorPicker();
            }
        });
        </script>
        <?php
    }

    public function get_option($option, $section, $default = '') {
        $options = get_option($section);

        if (isset($options[$option])) {
            return $options[$option];
        }

        return $default;
    }
}