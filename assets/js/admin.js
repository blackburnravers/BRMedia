class BRMediaAdmin {
  constructor() {
    this.initColorPickers();
    this.initTemplatePreviews();
    this.initChartJS();
    this.bindEvents();
  }

  initColorPickers() {
    jQuery('.brmedia-color-picker').wpColorPicker({
      defaultColor: '#2c3e50',
      change: _.throttle(function(event, ui) {
        this.updatePreviewColors(ui.color.toString());
      }, 300)
    });
  }

  initTemplatePreviews() {
    document.querySelectorAll('.template-preview').forEach(preview => {
      preview.addEventListener('click', () => {
        this.loadTemplatePreview(preview.dataset.template);
      });
    });
  }

  async loadTemplatePreview(template) {
    try {
      const response = await wp.apiFetch({
        path: '/brmedia/v1/template-preview',
        method: 'POST',
        data: { template }
      });

      this.showPreviewModal(response.html, response.css);
    } catch (error) {
      console.error('Preview load failed:', error);
    }
  }

  initChartJS() {
    this.statsChart = new Chart(document.getElementById('brmedia-stats-chart'), {
      type: 'line',
      data: {
        labels: [],
        datasets: [{
          label: 'Plays',
          data: [],
          borderColor: '#007cba',
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'bottom' }
        }
      }
    });

    this.updateStatsChart();
  }

  async updateStatsChart(range = 7) {
    try {
      const data = await wp.apiFetch({
        path: `/brmedia/v1/stats?range=${range}`
      });

      this.statsChart.data.labels = data.labels;
      this.statsChart.data.datasets[0].data = data.values;
      this.statsChart.update();
    } catch (error) {
      console.error('Stats update failed:', error);
    }
  }

  bindEvents() {
    document.getElementById('stats-range').addEventListener('change', e => {
      this.updateStatsChart(e.target.value);
    });
  }
}

new BRMediaAdmin();