<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Admin_Settings {

    private $settings_api;

    public function __construct() {
        $this->settings_api = new WeDevs_Settings_API();
        add_action('admin_init', [$this, 'admin_init']);
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    public function admin_init() {
        $this->settings_api->set_sections($this->get_settings_sections());
        $this->settings_api->set_fields($this->get_settings_fields());
        $this->settings_api->admin_init();
    }

    public function admin_menu() {
        add_menu_page(
            'BRMedia Settings',
            'BRMedia',
            'manage_options',
            'brmedia-settings',
            [$this, 'plugin_page'],
            'dashicons-format-audio',
            80
        );
    }

    public function get_settings_sections() {
        return [
            [
                'id' => 'brmedia_general',
                'title' => __('General Settings', 'brmedia'),
                'icon' => 'dashicons-admin-generic'
            ],
            [
                'id' => 'brmedia_templates',
                'title' => __('Template Customization', 'brmedia'),
                'icon' => 'dashicons-art'
            ],
            [
                'id' => 'brmedia_controls',
                'title' => __('Player Controls', 'brmedia'),
                'icon' => 'dashicons-controls-play'
            ],
            [
                'id' => 'brmedia_stats',
                'title' => __('Analytics & Tracking', 'brmedia'),
                'icon' => 'dashicons-chart-bar'
            ]
        ];
    }

    public function get_settings_fields() {
        return [
            'brmedia_general' => [
                [
                    'name' => 'default_volume',
                    'label' => __('Default Volume', 'brmedia'),
                    'type' => 'range',
                    'default' => 75,
                    'sanitize_callback' => 'absint',
                    'options' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 5
                    ]
                ],
                [
                    'name' => 'default_template',
                    'label' => __('Default Template', 'brmedia'),
                    'type' => 'select',
                    'options' => $this->get_template_options()
                ]
            ],
            'brmedia_templates' => $this->get_template_fields(),
            'brmedia_controls' => $this->get_control_fields(),
            'brmedia_stats' => [
                [
                    'name' => 'data_retention',
                    'label' => __('Data Retention Period', 'brmedia'),
                    'type' => 'number',
                    'default' => 365,
                    'sanitize_callback' => 'absint',
                    'desc' => __('Number of days to keep analytics data', 'brmedia'),
                    'options' => [
                        'min' => 30,
                        'max' => 730,
                        'step' => 1
                    ]
                ],
                [
                    'name' => 'anonymize_ips',
                    'label' => __('Anonymize IP Addresses', 'brmedia'),
                    'type' => 'checkbox',
                    'default' => 'on',
                    'desc' => __('Protect user privacy by anonymizing stored IP addresses', 'brmedia')
                ],
                [
                    'name' => 'tracking_consent',
                    'label' => __('GDPR Compliance', 'brmedia'),
                    'type' => 'select',
                    'options' => [
                        'none' => __('No Consent Required', 'brmedia'),
                        'basic' => __('Implied Consent', 'brmedia'),
                        'strict' => __('Explicit Consent', 'brmedia')
                    ],
                    'default' => 'basic',
                    'desc' => __('Select your region\'s privacy compliance requirements', 'brmedia')
                ],
                [
                    'name' => 'dashboard_access',
                    'label' => __('Dashboard Access', 'brmedia'),
                    'type' => 'multicheck',
                    'options' => $this->get_user_roles(),
                    'default' => ['administrator'],
                    'desc' => __('Select user roles that can view analytics', 'brmedia')
                ],
                [
                    'name' => 'export_data',
                    'label' => __('Data Export', 'brmedia'),
                    'type' => 'html',
                    'desc' => '<a href="'.admin_url('admin-post.php?action=brmedia_export_stats').'" class="button">'.__('Export All Stats', 'brmedia').'</a>'
                ]
            ]
        ];
    }

    private function get_user_roles() {
        global $wp_roles;
        $roles = [];
        
        foreach ($wp_roles->roles as $key => $role) {
            $roles[$key] = $role['name'];
        }
        
        return $roles;
    }

    private function get_template_options() {
        return [
            'template-1' => 'Standard Template 1',
            'template-2' => 'Vertical Layout',
            'template-3' => 'Card Style',
            'template-fullscreen' => 'Fullscreen Mode'
        ];
    }

    private function get_template_fields() {
        return [
            [
                'name' => 'player_bg',
                'label' => __('Player Background', 'brmedia'),
                'type' => 'color',
                'default' => '#2c3e50'
            ],
            [
                'name' => 'control_icons',
                'label' => __('Icon Style', 'brmedia'),
                'type' => 'select',
                'options' => [
                    'filled' => 'Filled Icons',
                    'outlined' => 'Outlined Icons',
                    'custom' => 'Custom SVG'
                ]
            ]
        ];
    }

    private function get_control_fields() {
        return [
            [
                'name' => 'enabled_controls',
                'label' => __('Active Controls', 'brmedia'),
                'type' => 'multicheck',
                'options' => [
                    'skip' => 'Skip Buttons',
                    'speed' => 'Playback Speed',
                    'share' => 'Share Track',
                    'fullscreen' => 'Fullscreen'
                ]
            ]
        ];
    }

    public function plugin_page() {
        echo '<div class="wrap brmedia-admin-wrapper">';
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();
        echo '</div>';
    }
}

new BRMedia_Admin_Settings();