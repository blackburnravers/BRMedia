<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * BRMedia - Statistics & Analytics Admin Page
 */
function brmedia_statistics_page() {
    ?>
    <div class="wrap brmedia-admin-container">
        <h1>BRMedia Statistics & Analytics</h1>

        <div class="brmedia-statistics-overview">
            <div class="stat-box">
                <h3>Total Plays</h3>
                <p id="brmedia-total-plays">0</p>
            </div>
            <div class="stat-box">
                <h3>Total Downloads</h3>
                <p id="brmedia-total-downloads">0</p>
            </div>
            <div class="stat-box">
                <h3>Top Country</h3>
                <p id="brmedia-top-country">-</p>
            </div>
            <div class="stat-box">
                <h3>Active Listeners</h3>
                <p id="brmedia-active-listeners">0</p>
            </div>
        </div>

        <div class="brmedia-chart-section">
            <h2>Play & Download Trends</h2>
            <canvas id="brmedia-plays-downloads-chart" height="100"></canvas>
        </div>

        <div class="brmedia-chart-section">
            <h2>Top Media Content</h2>
            <canvas id="brmedia-top-media-chart" height="100"></canvas>
        </div>

        <div class="brmedia-chart-section">
            <h2>Geolocation of Listeners</h2>
            <canvas id="brmedia-geo-chart" height="100"></canvas>
        </div>
    </div>

    <style>
        .brmedia-statistics-overview {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-box {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            flex: 1 1 200px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .stat-box h3 {
            margin: 0 0 10px;
            color: #0073aa;
        }
        .brmedia-chart-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dummy data for now (replace via AJAX or WP REST API later)
            const stats = {
                plays: 2380,
                downloads: 740,
                topCountry: 'UK',
                activeListeners: 47,
                trends: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    plays: [120, 200, 450, 380, 620, 610],
                    downloads: [80, 150, 210, 240, 180, 300]
                },
                topMedia: {
                    labels: ['Track A', 'Track B', 'Track C'],
                    data: [300, 250, 200]
                },
                geo: {
                    labels: ['UK', 'US', 'CA', 'AU', 'DE'],
                    data: [500, 430, 120, 90, 60]
                }
            };

            document.getElementById('brmedia-total-plays').textContent = stats.plays;
            document.getElementById('brmedia-total-downloads').textContent = stats.downloads;
            document.getElementById('brmedia-top-country').textContent = stats.topCountry;
            document.getElementById('brmedia-active-listeners').textContent = stats.activeListeners;

            new Chart(document.getElementById('brmedia-plays-downloads-chart'), {
                type: 'line',
                data: {
                    labels: stats.trends.labels,
                    datasets: [
                        {
                            label: 'Plays',
                            data: stats.trends.plays,
                            borderColor: '#1e87f0',
                            fill: false
                        },
                        {
                            label: 'Downloads',
                            data: stats.trends.downloads,
                            borderColor: '#ff9800',
                            fill: false
                        }
                    ]
                }
            });

            new Chart(document.getElementById('brmedia-top-media-chart'), {
                type: 'bar',
                data: {
                    labels: stats.topMedia.labels,
                    datasets: [{
                        label: 'Top Media',
                        data: stats.topMedia.data,
                        backgroundColor: '#4caf50'
                    }]
                }
            });

            new Chart(document.getElementById('brmedia-geo-chart'), {
                type: 'pie',
                data: {
                    labels: stats.geo.labels,
                    datasets: [{
                        data: stats.geo.data,
                        backgroundColor: ['#1e87f0', '#ff9800', '#4caf50', '#e91e63', '#9c27b0']
                    }]
                }
            });
        });
    </script>
    <?php
}
