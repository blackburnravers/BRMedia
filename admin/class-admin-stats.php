<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Admin_Stats {

    private $cache_duration = HOUR_IN_SECONDS; // Cache results for 1 hour

    public function __construct() {
        add_action('admin_init', [$this, 'handle_report_generation']);
    }

    /**
     * Get comprehensive stats data
     */
    public function get_stats_overview($range = 7) {
        $cache_key = 'brmedia_stats_overview_' . $range;
        $data = get_transient($cache_key);

        if (false === $data) {
            $data = [
                'total_plays' => $this->get_total_plays($range),
                'avg_duration' => $this->get_avg_duration($range),
                'device_distribution' => $this->get_device_distribution($range),
                'popular_tracks' => $this->get_popular_tracks($range),
                'hourly_activity' => $this->get_hourly_activity($range),
                'geo_distribution' => $this->get_geo_distribution($range)
            ];

            set_transient($cache_key, $data, $this->cache_duration);
        }

        return $data;
    }

    /**
     * Get total play count
     */
    public function get_total_plays($days = 7) {
        global $wpdb;

        return $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) 
             FROM {$wpdb->prefix}brmedia_stats 
             WHERE event_type = 'start' 
             AND created_at >= %s",
            date('Y-m-d', strtotime("-{$days} days"))
        );
    }

    /**
     * Get average play duration
     */
    public function get_avg_duration($days = 7) {
        global $wpdb;

        return (float) $wpdb->get_var($wpdb->prepare(
            "SELECT AVG(duration) 
             FROM {$wpdb->prefix}brmedia_stats 
             WHERE event_type = 'end' 
             AND created_at >= %s",
            date('Y-m-d', strtotime("-{$days} days"))
        );
    }

    /**
     * Get device type distribution
     */
    public function get_device_distribution($days = 7) {
        global $wpdb;

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT device_type, COUNT(*) as count 
             FROM {$wpdb->prefix}brmedia_stats 
             WHERE created_at >= %s 
             GROUP BY device_type",
            date('Y-m-d', strtotime("-{$days} days"))
        );

        $distribution = [
            'desktop' => 0,
            'mobile' => 0,
            'tablet' => 0
        ];

        foreach ($results as $row) {
            $distribution[$row->device_type] = (int) $row->count;
        }

        return $distribution;
    }

    /**
     * Generate chart-ready data for plays over time
     */
    public function get_plays_over_time_chart_data($days = 7) {
        global $wpdb;

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT DATE(created_at) as date, COUNT(*) as count 
             FROM {$wpdb->prefix}brmedia_stats 
             WHERE event_type = 'start' 
             AND created_at >= %s 
             GROUP BY DATE(created_at) 
             ORDER BY date ASC",
            date('Y-m-d', strtotime("-{$days} days"))
        );

        $labels = [];
        $data = [];
        $current_date = date('Y-m-d', strtotime("-{$days} days"));

        for ($i = 0; $i <= $days; $i++) {
            $labels[] = date('M j', strtotime($current_date));
            $found = false;
            
            foreach ($results as $row) {
                if ($row->date === $current_date) {
                    $data[] = (int) $row->count;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) $data[] = 0;
            $current_date = date('Y-m-d', strtotime("+1 day", strtotime($current_date)));
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('Plays', 'brmedia'),
                    'data' => $data,
                    'borderColor' => '#007cba',
                    'tension' => 0.4
                ]
            ]
        ];
    }

    /**
     * Handle report generation
     */
    public function handle_report_generation() {
        if (!isset($_GET['brmedia_report'])) return;

        check_admin_referer('brmedia_download_report');

        $type = sanitize_key($_GET['report_type']);
        $range = isset($_GET['range']) ? (int) $_GET['range'] : 7;

        switch ($type) {
            case 'csv':
                $this->generate_csv_report($range);
                break;
            case 'json':
                $this->generate_json_report($range);
                break;
        }
    }

    /**
     * Generate CSV report
     */
    private function generate_csv_report($range) {
        $data = $this->get_stats_overview($range);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="brmedia-report-' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, [
            __('Metric', 'brmedia'),
            __('Value', 'brmedia')
        ]);

        // Data
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subkey => $subvalue) {
                    fputcsv($output, [
                        ucfirst($key) . ' - ' . ucfirst($subkey),
                        is_array($subvalue) ? json_encode($subvalue) : $subvalue
                    ]);
                }
            } else {
                fputcsv($output, [
                    ucfirst($key),
                    $value
                ]);
            }
        }

        fclose($output);
        exit;
    }

    /**
     * Clear stats cache
     */
    public function clear_cache() {
        global $wpdb;
        
        $wpdb->query(
            "DELETE FROM $wpdb->options 
             WHERE option_name LIKE '_transient_brmedia_stats_%'"
        );
    }

    /**
     * Get popular tracks
     */
    public function get_popular_tracks($days = 7) {
        global $wpdb;

        return $wpdb->get_results($wpdb->prepare(
            "SELECT track_id, COUNT(*) as play_count 
             FROM {$wpdb->prefix}brmedia_stats 
             WHERE event_type = 'start' 
             AND created_at >= %s 
             GROUP BY track_id 
             ORDER BY play_count DESC 
             LIMIT 10",
            date('Y-m-d', strtotime("-{$days} days"))
        );
    }

    /**
     * Display admin stats page
     */
    public function render_stats_page() {
        $range = isset($_GET['range']) ? (int) $_GET['range'] : 7;
        $stats = $this->get_stats_overview($range);
        $chart_data = $this->get_plays_over_time_chart_data($range);
        ?>
        <div class="wrap brmedia-stats">
            <h1><?php esc_html_e('Playback Analytics', 'brmedia'); ?></h1>
            
            <div class="brmedia-stats-header">
                <div class="report-controls">
                    <form method="get">
                        <input type="hidden" name="page" value="<?php echo esc_attr($_GET['page']); ?>">
                        <select name="range">
                            <?php foreach [7 => __('Last 7 Days'), 30 => __('Last 30 Days'), 90 => __('Last 90 Days')] as $value => $label) : ?>
                                <option value="<?php echo $value; ?>" <?php selected($range, $value); ?>>
                                    <?php echo esc_html($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php submit_button(__('Filter', 'brmedia'), 'secondary', 'submit', false); ?>
                    </form>
                    
                    <div class="export-buttons">
                        <a href="<?php echo wp_nonce_url(
                            add_query_arg(['brmedia_report' => 1, 'report_type' => 'csv']), 
                            'brmedia_download_report'
                        ); ?>" class="button">
                            <?php esc_html_e('Export CSV', 'brmedia'); ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="brmedia-stats-grid">
                <div class="stat-card">
                    <h3><?php esc_html_e('Total Plays', 'brmedia'); ?></h3>
                    <div class="stat-value"><?php echo number_format($stats['total_plays']); ?></div>
                </div>
                
                <div class="stat-card">
                    <h3><?php esc_html_e('Avg. Duration', 'brmedia'); ?></h3>
                    <div class="stat-value">
                        <?php echo gmdate("i:s", $stats['avg_duration']); ?>
                    </div>
                </div>
            </div>

            <div class="brmedia-chart-container">
                <canvas id="brmediaPlaysChart" 
                        data-chart='<?php echo json_encode($chart_data); ?>'>
                </canvas>
            </div>
        </div>
        <?php
    }
}

new BRMedia_Admin_Stats();