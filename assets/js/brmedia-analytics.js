// BRMedia Admin Analytics Scripts

jQuery(document).ready(function ($) {

    // Chart.js Global Options
    Chart.defaults.font.family = 'Arial, sans-serif';
    Chart.defaults.color = '#333';

    // Main Data Chart: Plays, Views, Downloads Over Time
    if ($('#brmedia-stats-overview').length) {
        const ctx = document.getElementById('brmedia-stats-overview').getContext('2d');

        const data = JSON.parse($('#brmedia-stats-overview').attr('data-stats'));
        const overviewChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Plays',
                        data: data.plays,
                        fill: false,
                        borderColor: '#0073aa',
                        tension: 0.4
                    },
                    {
                        label: 'Downloads',
                        data: data.downloads,
                        fill: false,
                        borderColor: '#1e87f0',
                        tension: 0.4
                    },
                    {
                        label: 'Views',
                        data: data.views,
                        fill: false,
                        borderColor: '#00c853',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Geo Chart (Top Countries)
    if ($('#brmedia-geo-map').length) {
        const geoCtx = document.getElementById('brmedia-geo-map').getContext('2d');
        const geoData = JSON.parse($('#brmedia-geo-map').attr('data-geo'));

        const countries = geoData.map(item => item.country);
        const counts = geoData.map(item => item.count);

        const geoChart = new Chart(geoCtx, {
            type: 'bar',
            data: {
                labels: countries,
                datasets: [{
                    label: 'Top Countries',
                    data: counts,
                    backgroundColor: '#0073aa'
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Show Data Filters
    $('.brmedia-analytics-filter select').on('change', function () {
        $(this).closest('form').submit();
    });

    // Individual Post Charts (if enabled)
    $('.brmedia-individual-chart').each(function () {
        const chartId = $(this).attr('id');
        const jsonData = JSON.parse($(this).attr('data-individual'));
        const chartCtx = document.getElementById(chartId).getContext('2d');

        new Chart(chartCtx, {
            type: 'line',
            data: {
                labels: jsonData.labels,
                datasets: [
                    {
                        label: 'Plays',
                        data: jsonData.plays,
                        borderColor: '#0073aa',
                        tension: 0.4
                    },
                    {
                        label: 'Downloads',
                        data: jsonData.downloads,
                        borderColor: '#ff9800',
                        tension: 0.4
                    }
                ]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });

});