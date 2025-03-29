jQuery(document).ready(function($) {

    const timeframeSelect = $('#brmedia-stats-timeframe');
    const chartCanvas = document.getElementById('brmedia-stats-chart');
    let chartInstance = null;

    function fetchDashboardStats() {
        const timeframe = timeframeSelect.val();

        $.post(brmedia_vars.ajax_url, {
            action: 'brmedia_admin_stats',
            nonce: brmedia_vars.nonce,
            timeframe: timeframe
        }, function(response) {
            if (response.success) {
                updateStatCounts(response.data);
                renderChart(response.data);
            } else {
                console.error('Dashboard stats error:', response.data);
            }
        });
    }

    function updateStatCounts(data) {
        $('#brmedia-stat-tracks').text(data.tracks || 0);
        $('#brmedia-stat-videos').text(data.videos || 0);
        $('#brmedia-stat-plays').text(data.plays || 0);
        $('#brmedia-stat-downloads').text(data.downloads || 0);
    }

    function renderChart(data) {
        const labels = [];
        const plays = [];
        const downloads = [];

        if (data.chart && data.chart.length) {
            data.chart.forEach(entry => {
                labels.push(entry.date);
                plays.push(parseInt(entry.plays));
            });
        }

        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(chartCanvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Plays',
                        data: plays,
                        borderWidth: 2,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }

    timeframeSelect.on('change', fetchDashboardStats);

    // Initial load
    fetchDashboardStats();

});