jQuery(document).ready(function($) {

    // Color Picker Initialization
    $('.brmedia-color-picker').wpColorPicker({
        defaultColor: '#2c3e50',
        change: _.throttle(function(event, ui) {
            $(event.target).trigger('colorchange');
        }, 3000)
    });

    // Live Template Preview
    $('#template-selector').on('change', function() {
        const template = $(this).val();
        $('#template-preview').attr('data-template', template);
    });

    // Stats Chart Initialization
    const ctx = document.getElementById('brmediaStatsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: brmediaStatsData.labels,
            datasets: [{
                label: 'Plays',
                data: brmediaStatsData.plays,
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Advanced Toggle
    $('.brmedia-advanced-toggle').click(function() {
        $(this).toggleClass('active')
               .next('.brmedia-advanced-settings').slideToggle();
    });

});