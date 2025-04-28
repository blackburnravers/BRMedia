// Basic alert to confirm script is loading
alert('admin.js loaded');

jQuery(document).ready(function($) {
    // Confirm jQuery is available
    alert('jQuery is loaded');

    // Initialize Select2 for icon pickers
    $('.icon-picker').select2({
        templateResult: function(data) {
            if (!data.id) {
                return data.text;
            }
            var $option = $('<span><i class="' + data.id + '"></i> ' + data.text + '</span>');
            return $option;
        },
        templateSelection: function(data) {
            if (!data.id) {
                return data.text;
            }
            var $selection = $('<span><i class="' + data.id + '"></i> ' + data.text + '</span>');
            return $selection;
        },
        width: '100%'
    });

    // Synchronize color picker and hex input
    $('.color-picker').on('input', function() {
        $(this).next('.hex-code').val(this.value);
    });

    $('.hex-code').on('input', function() {
        var hex = this.value;
        if (/^#[0-9A-F]{6}$/i.test(hex)) {
            $(this).prev('.color-picker').val(hex);
        }
    });

    // Master Reset Button
    $('#reset-all-templates').on('click', function(e) {
        e.preventDefault();
        alert('Master reset button clicked');
        if (confirm('Are you sure you want to reset all templates to their default settings? This action cannot be undone.')) {
            alert('Sending master reset AJAX request');
            $.ajax({
                url: brmedia_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'brmedia_mass_reset_templates',
                    nonce: brmedia_ajax.maintenance_nonce
                },
                success: function(response) {
                    alert('Master reset response: ' + JSON.stringify(response));
                    if (response.success) {
                        alert(response.data);
                        location.reload();
                    } else {
                        alert('Error: ' + response.data);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Master reset AJAX error: ' + status + ' - ' + error);
                }
            });
        }
    });

    // Individual Template Reset Button
    $('.reset-template').on('click', function(e) {
        e.preventDefault();
        var template = $(this).data('template');
        alert('Reset template button clicked for: ' + template);
        if (confirm('Are you sure you want to reset the ' + template + ' template to its default settings? This action cannot be undone.')) {
            alert('Sending reset template AJAX request for: ' + template);
            $.ajax({
                url: brmedia_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'brmedia_reset_template',
                    template: template,
                    nonce: brmedia_ajax.template_reset_nonce
                },
                success: function(response) {
                    alert('Reset template response: ' + JSON.stringify(response));
                    if (response.success) {
                        alert(response.data);
                        location.reload();
                    } else {
                        alert('Error: ' + response.data);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Reset template AJAX error: ' + status + ' - ' + error);
                }
            });
        }
    });

    // Stats page functionality
    if ($('#brmedia-stats-tabs').length) {
        // Tab switching
        $('.nav-link').on('click', function(e) {
            e.preventDefault();
            $('.nav-link').removeClass('active');
            $('.tab-pane').removeClass('show active');
            $(this).addClass('active');
            $($(this).attr('href')).addClass('show active');
            renderCharts($(this).attr('href'));
        });

        // Date range filter
        $('#date-range-filter').on('change', function() {
            var range = $(this).val();
            var data = {
                action: 'brmedia_get_filtered_stats',
                date_range: range
            };
            $.post(ajaxurl, data, function(response) {
                if (response.success) {
                    updateCharts(response.data);
                }
            });
        });

        // Track filter
        $('#track-filter').on('change', function() {
            var trackId = $(this).val();
            var data = {
                action: 'brmedia_get_filtered_stats',
                track_id: trackId
            };
            $.post(ajaxurl, data, function(response) {
                if (response.success) {
                    updateCharts(response.data);
                }
            });
        });

        // Export CSV button
        $('#export-csv-btn').on('click', function() {
            var data = {
                action: 'brmedia_export_stats_csv'
            };
            $.post(ajaxurl, data, function(response) {
                if (response.success) {
                    var blob = new Blob([response.data.csv], { type: 'text/csv' });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'brmedia-stats-' + new Date().toISOString().split('T')[0] + '.csv';
                    link.click();
                }
            });
        });

        // Initial chart rendering
        renderCharts('#overview');
    }

    function renderCharts(activeTab) {
        var data = {
            totalPlays: <?php echo json_encode(brmedia_get_total_plays()); ?>,
            averageDuration: <?php echo json_encode(brmedia_get_average_play_duration()); ?>,
            topTracks: <?php echo json_encode(brmedia_get_top_tracks()); ?>,
            sharedTracks: <?php echo json_encode(brmedia_get_shared_tracks()); ?>,
            playsOverTime: <?php echo json_encode(brmedia_get_plays_over_time()); ?>,
            uniqueUsers: <?php echo json_encode(brmedia_get_unique_users()); ?>,
            avgPlaysPerUser: <?php echo json_encode(brmedia_get_avg_plays_per_user()); ?>,
            geoLocation: <?php echo json_encode(brmedia_get_geo_location()); ?>,
            deviceUsage: <?php echo json_encode(brmedia_get_device_usage()); ?>,
            browserUsage: <?php echo json_encode(brmedia_get_browser_usage()); ?>,
            completionRate: <?php echo json_encode(brmedia_get_track_completion_rate()); ?>,
            sharingPlatforms: <?php echo json_encode(brmedia_get_sharing_platforms()); ?>
        };
        updateCharts(data);
    }

    function updateCharts(data) {
        var activeTab = $('.tab-pane.show.active').attr('id');

        if (activeTab === 'overview') {
            var totalPlaysOptions = {
                series: [data.totalPlays],
                chart: { type: 'radialBar', height: 300 },
                plotOptions: { 
                    radialBar: { 
                        hollow: { size: '70%' }, 
                        dataLabels: { 
                            name: { show: true, color: '#fff' }, 
                            value: { show: true, color: '#fff', formatter: function(val) { return val; } } 
                        } 
                    } 
                },
                labels: ['Total Plays'],
                colors: ['#fff']
            };
            new ApexCharts(document.querySelector("#total-plays-chart"), totalPlaysOptions).render();

            var uniqueUsersOptions = {
                series: [data.uniqueUsers],
                chart: { type: 'radialBar', height: 300 },
                plotOptions: { 
                    radialBar: { 
                        hollow: { size: '70%' }, 
                        dataLabels: { 
                            name: { show: true, color: '#fff' }, 
                            value: { show: true, color: '#fff', formatter: function(val) { return val; } } 
                        } 
                    } 
                },
                labels: ['Unique Users'],
                colors: ['#fff']
            };
            new ApexCharts(document.querySelector("#unique-users-chart"), uniqueUsersOptions).render();

            var playsOverTimeOptions = {
                series: [{ name: 'Plays', data: data.playsOverTime.data }],
                chart: { type: 'line', height: 300 },
                xaxis: { categories: data.playsOverTime.labels, labels: { style: { colors: '#fff' } } },
                yaxis: { labels: { style: { colors: '#fff' } } },
                colors: ['#fff']
            };
            new ApexCharts(document.querySelector("#plays-over-time-chart"), playsOverTimeOptions).render();
        }

        if (activeTab === 'tracks') {
            var trackNames = data.topTracks.map(function(track) { return track.post_title; });
            var playCounts = data.topTracks.map(function(track) { return parseInt(track.play_count); });
            var sharedTrackNames = data.sharedTracks.map(function(track) { return track.post_title; });
            var shareCounts = data.sharedTracks.map(function(track) { return parseInt(track.share_count); });

            var topTracksOptions = {
                series: [{ name: 'Plays', data: playCounts }],
                chart: { type: 'bar', height: 300 },
                xaxis: { categories: trackNames, labels: { style: { colors: '#fff' } } },
                yaxis: { labels: { style: { colors: '#fff' } } },
                colors: ['#fff']
            };
            new ApexCharts(document.querySelector("#top-tracks-chart"), topTracksOptions).render();

            var sharedTracksOptions = {
                series: [{ name: 'Shares', data: shareCounts }],
                chart: { type: 'bar', height: 300 },
                xaxis: { categories: sharedTrackNames, labels: { style: { colors: '#fff' } } },
                yaxis: { labels: { style: { colors: '#fff' } } },
                colors: ['#fff']
            };
            new ApexCharts(document.querySelector("#shared-tracks-chart"), sharedTracksOptions).render();

            var completionRateOptions = {
                series: [{ name: 'Completion Rate (%)', data: data.completionRate.map(function(item) { return item.rate; }) }],
                chart: { type: 'bar', height: 300 },
                xaxis: { categories: data.completionRate.map(function(item) { return item.post_title; }), labels: { style: { colors: '#fff' } } },
                yaxis: { labels: { style: { colors: '#fff' } } },
                colors: ['#fff']
            };
            new ApexCharts(document.querySelector("#completion-rate-chart"), completionRateOptions).render();

            var sharingPlatformsOptions = {
                series: data.sharingPlatforms.map(function(item) { return item.count; }),
                chart: { type: 'pie', height: 300 },
                labels: data.sharingPlatforms.map(function(item) { return item.platform; }),
                colors: ['#ff6b6b', '#feca57', '#1dd1a1', '#48dbfb']
            };
            new ApexCharts(document.querySelector("#sharing-platforms-chart"), sharingPlatformsOptions).render();
        }

        if (activeTab === 'users') {
            var avgPlaysPerUserOptions = {
                series: [{ name: 'Avg Plays', data: [data.avgPlaysPerUser] }],
                chart: { type: 'bar', height: 300 },
                xaxis: { categories: ['All Users'], labels: { style: { colors: '#fff' } } },
                yaxis: { labels: { style: { colors: '#fff' } } },
                colors: ['#fff']
            };
            new ApexCharts(document.querySelector("#avg-plays-per-user-chart"), avgPlaysPerUserOptions).render();

            var geoLocationOptions = {
                series: [{ name: 'Plays', data: data.geoLocation.map(function(item) { return item.count; }) }],
                chart: { type: 'bar', height: 300 },
                xaxis: { categories: data.geoLocation.map(function(item) { return item.country; }), labels: { style: { colors: '#fff' } } },
                yaxis: { labels: { style: { colors: '#fff' } } },
                colors: ['#fff']
            };
            new ApexCharts(document.querySelector("#geo-location-chart"), geoLocationOptions).render();

            var deviceUsageOptions = {
                series: data.deviceUsage.map(function(item) { return item.count; }),
                chart: { type: 'pie', height: 300 },
                labels: data.deviceUsage.map(function(item) { return item.device; }),
                colors: ['#ff6b6b', '#feca57', '#1dd1a1']
            };
            new ApexCharts(document.querySelector("#device-usage-chart"), deviceUsageOptions).render();

            var browserUsageOptions = {
                series: data.browserUsage.map(function(item) { return item.count; }),
                chart: { type: 'pie', height: 300 },
                labels: data.browserUsage.map(function(item) { return item.browser; }),
                colors: ['#ff6b6b', '#feca57', '#1dd1a1', '#48dbfb']
            };
            new ApexCharts(document.querySelector("#browser-usage-chart"), browserUsageOptions).render();
        }
    }
});