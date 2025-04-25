<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check user capabilities
if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}
?>

<div class="container-fluid mt-4">
    <h1 class="mb-4 text-center">BRMedia Stats</h1>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body bg-primary text-white">
                    <h3 class="card-title"><i class="fas fa-play-circle"></i> Total Plays</h3>
                    <div id="total-plays-chart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body bg-success text-white">
                    <h3 class="card-title"><i class="fas fa-clock"></i> Average Play Duration</h3>
                    <div id="average-duration-chart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body bg-info text-white">
                    <h3 class="card-title"><i class="fas fa-music"></i> Top Tracks</h3>
                    <div id="top-tracks-chart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body bg-warning text-white">
                    <h3 class="card-title"><i class="fas fa-share-alt"></i> Shared Tracks</h3>
                    <div id="shared-tracks-chart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch data from PHP functions
    var totalPlays = <?php echo json_encode(brmedia_get_total_plays()); ?>;
    var averageDuration = <?php echo json_encode(brmedia_get_average_play_duration()); ?>;
    var topTracks = <?php echo json_encode(brmedia_get_top_tracks()); ?>;
    var sharedTracks = <?php echo json_encode(brmedia_get_shared_tracks()); ?>;

    // Prepare data for charts
    var trackNames = topTracks.map(function(track) { return track.post_title; });
    var playCounts = topTracks.map(function(track) { return parseInt(track.play_count); });
    var sharedTrackNames = sharedTracks.map(function(track) { return track.post_title; });
    var shareCounts = sharedTracks.map(function(track) { return parseInt(track.share_count); });

    // Total Plays Radial Chart
    var totalPlaysOptions = {
        series: [totalPlays],
        chart: { type: 'radialBar', height: 350 },
        plotOptions: { radialBar: { hollow: { size: '70%' }, dataLabels: { name: { show: true }, value: { show: true } } } },
        labels: ['Total Plays'],
        colors: ['#ffffff']
    };
    new ApexCharts(document.querySelector("#total-plays-chart"), totalPlaysOptions).render();

    // Average Duration Radial Chart
    var averageDurationOptions = {
        series: [averageDuration || 0],
        chart: { type: 'radialBar', height: 350 },
        plotOptions: { radialBar: { hollow: { size: '70%' }, dataLabels: { name: { show: true }, value: { formatter: function(val) { return val + 's'; } } } } },
        labels: ['Avg Duration'],
        colors: ['#ffffff']
    };
    new ApexCharts(document.querySelector("#average-duration-chart"), averageDurationOptions).render();

    // Top Tracks Bar Chart
    var topTracksOptions = {
        series: [{ name: 'Plays', data: playCounts }],
        chart: { type: 'bar', height: 350 },
        xaxis: { categories: trackNames },
        colors: ['#ffffff']
    };
    new ApexCharts(document.querySelector("#top-tracks-chart"), topTracksOptions).render();

    // Shared Tracks Bar Chart
    var sharedTracksOptions = {
        series: [{ name: 'Shares', data: shareCounts }],
        chart: { type: 'bar', height: 350 },
        xaxis: { categories: sharedTrackNames },
        colors: ['#ffffff']
    };
    new ApexCharts(document.querySelector("#shared-tracks-chart"), sharedTracksOptions).render();
});
</script>