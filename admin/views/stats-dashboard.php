<?php
$stats_handler = new BRMedia_Stats();
$stats_data = $stats_handler->get_aggregate_stats();
$nonce = wp_create_nonce('brmedia_stats_nonce');
?>
<div class="brmedia-stats-dashboard">
    <h2><?php esc_html_e('Playback Analytics', 'brmedia'); ?></h2>
    
    <div class="stats-period-selector">
        <select id="brmedia_stats_range" data-nonce="<?php echo esc_attr($nonce); ?>">
            <option value="7"><?php esc_html_e('Last 7 Days', 'brmedia'); ?></option>
            <option value="30"><?php esc_html_e('Last 30 Days', 'brmedia'); ?></option>
            <option value="90"><?php esc_html_e('Last 90 Days', 'brmedia'); ?></option>
        </select>
    </div>

    <div class="stats-grid">
        <div class="stat-card total-plays">
            <h4><?php esc_html_e('Total Plays', 'brmedia'); ?></h4>
            <div class="stat-value"><?php echo number_format($stats_data['total_plays']); ?></div>
            <div class="stat-trend" id="playTrend"></div>
        </div>
        
        <div class="stat-card avg-duration">
            <h4><?php esc_html_e('Avg. Duration', 'brmedia'); ?></h4>
            <div class="stat-value"><?php echo gmdate("i:s", $stats_data['avg_duration']); ?></div>
            <div class="stat-trend" id="durationTrend"></div>
        </div>
    </div>

    <div class="main-chart-container">
        <canvas id="brmediaMainChart" 
                data-stats='<?php echo wp_json_encode($stats_data['chart_data']); ?>'
                data-colors='<?php echo wp_json_encode(['#2c3e50', '#007cba', '#00a0d2']); ?>'></canvas>
    </div>

    <div class="device-breakdown">
        <h3><?php esc_html_e('Device Distribution', 'brmedia'); ?></h3>
        <div class="breakdown-grid">
            <div class="pie-chart">
                <canvas id="devicePieChart" 
                        data-devices='<?php echo wp_json_encode($stats_data['device_distribution']); ?>'></canvas>
            </div>
            <div class="os-list">
                <h4><?php esc_html_e('Operating Systems', 'brmedia'); ?></h4>
                <ul>
                    <?php foreach ($stats_data['os_distribution'] as $os => $count) : ?>
                        <li>
                            <span class="os-name"><?php echo esc_html($os); ?></span>
                            <span class="os-count"><?php echo number_format($count); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Security nonce field -->
    <input type="hidden" id="brmedia_stats_nonce" value="<?php echo esc_attr($nonce); ?>">
</div>

<style>
.brmedia-stats-dashboard {
    max-width: 1200px;
    padding: 20px;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin: 2rem 0;
}

.stat-card {
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #007cba;
}

.main-chart-container {
    height: 400px;
    margin: 2rem 0;
}

.breakdown-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 2rem;
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .breakdown-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const BRMediaStats = {
        init() {
            this.initMainChart();
            this.initPieChart();
            this.bindEvents();
        },

        initMainChart() {
            const ctx = document.getElementById('brmediaMainChart');
            const data = JSON.parse(ctx.dataset.stats);
            const colors = JSON.parse(ctx.dataset.colors);

            this.mainChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Plays',
                        data: data.values,
                        borderColor: colors[0],
                        backgroundColor: colors[0] + '20',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        },

        initPieChart() {
            const ctx = document.getElementById('devicePieChart');
            const devices = JSON.parse(ctx.dataset.devices);

            this.pieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(devices),
                    datasets: [{
                        data: Object.values(devices),
                        backgroundColor: ['#2c3e50', '#007cba', '#00a0d2']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        },

        async updateCharts(days) {
            try {
                const response = await fetch(brmedia.ajax_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'brmedia_update_stats',
                        range: days,
                        nonce: document.getElementById('brmedia_stats_nonce').value
                    })
                });

                if (!response.ok) throw new Error('Network error');
                
                const data = await response.json();
                
                // Update main chart
                this.mainChart.data.labels = data.chart_data.labels;
                this.mainChart.data.datasets[0].data = data.chart_data.values;
                this.mainChart.update();

                // Update pie chart
                this.pieChart.data.datasets[0].data = Object.values(data.device_distribution);
                this.pieChart.data.labels = Object.keys(data.device_distribution);
                this.pieChart.update();

                // Update stats cards
                document.querySelector('.total-plays .stat-value').textContent = 
                    data.total_plays.toLocaleString();
                document.querySelector('.avg-duration .stat-value').textContent = 
                    this.formatDuration(data.avg_duration);

            } catch (error) {
                console.error('Stats update failed:', error);
            }
        },

        formatDuration(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        },

        bindEvents() {
            document.getElementById('brmedia_stats_range').addEventListener('change', (e) => {
                this.updateCharts(e.target.value);
            });
        }
    };

    BRMediaStats.init();
});
</script>