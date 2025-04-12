<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Admin_Help {

    private $tabs = [
        'getting-started' => 'Getting Started',
        'templates' => 'Template Guide',
        'analytics' => 'Understanding Stats'
    ];

    public function __construct() {
        add_action('admin_menu', [$this, 'add_help_page']);
    }

    public function add_help_page() {
        add_submenu_page(
            'brmedia-settings',
            'BRMedia Help',
            'Help & Docs',
            'manage_options',
            'brmedia-help',
            [$this, 'render_help_page']
        );
    }

    public function render_help_page() {
        $current_tab = $_GET['tab'] ?? 'getting-started';
        ?>
        <div class="wrap brmedia-help">
            <h1>BRMedia Documentation</h1>
            
            <nav class="nav-tab-wrapper">
                <?php foreach ($this->tabs as $id => $label) : ?>
                    <a href="<?= esc_url(admin_url('admin.php?page=brmedia-help&tab=' . $id)) ?>"
                       class="nav-tab <?= $current_tab === $id ? 'nav-tab-active' : '' ?>">
                        <?= esc_html($label) ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="help-content">
                <?php $this->render_tab_content($current_tab); ?>
            </div>
        </div>
        <?php
    }

    private function render_tab_content($tab) {
        switch ($tab) {
            case 'templates':
                include BRMEDIA_PATH . 'admin/views/help-templates.php';
                break;
            case 'analytics':
                include BRMEDIA_PATH . 'admin/views/help-analytics.php';
                break;
            default:
                include BRMEDIA_PATH . 'admin/views/help-getting-started.php';
        }
    }
}

new BRMedia_Admin_Help();