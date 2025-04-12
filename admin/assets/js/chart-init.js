class BRMedia_Chart_Engine {
    constructor() {
        this.charts = new Map();
        this.init();
    }

    init() {
        this.initMainChart();
        this.initPieChart();
        this.initStatsListeners();
    }

    initMainChart() {
        const canvas = document.getElementById('brmediaMainChart');
        if (!canvas) return;

        const data = JSON.parse(canvas.dataset.stats);
        this.charts.set('main', new Chart(canvas, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Plays',
                    data: data.values,
                    borderColor: '#007cba',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(0,124,186,0.1)'
                }]
            },
            options: this.getChartOptions()
        }));
    }

    initPieChart() {
        const canvas = document.getElementById('devicePieChart');
        if (!canvas) return;

        const data = {
            desktop: 65,
            mobile: 30,
            tablet: 5
        };

        this.charts.set('devices', new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: Object.keys(data),
                datasets: [{
                    data: Object.values(data),
                    backgroundColor: [
                        '#007cba',
                        '#00a0d2',
                        '#006799'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'right' }
                }
            }
        }));
    }

    getChartOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            },
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            }
        };
    }

    initStatsListeners() {
        $('#brmedia_stats_range').on('change', async e => {
            const days = $(e.target).val();
            const newData = await this.fetchStatsData(days);
            this.updateCharts(newData);
        });
    }

    async fetchStatsData(days) {
        return wp.ajax.post('brmedia_get_stats', {
            range: days,
            _wpnonce: brmediaStats.nonce
        });
    }

    updateCharts(data) {
        this.charts.get('main').data.labels = data.labels;
        this.charts.get('main').data.datasets[0].data = data.values;
        this.charts.get('main').update();
    }
}

new BRMedia_Chart_Engine();