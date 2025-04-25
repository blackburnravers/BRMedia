// BRMedia custom scripts
document.addEventListener('DOMContentLoaded', function() {
    // Set default volume for all players based on plugin settings
    const players = document.querySelectorAll('.brmedia-player audio');
    players.forEach(function(player) {
        const plyrInstance = new Plyr(player);
        if (typeof brmediaSettings !== 'undefined' && brmediaSettings.defaultVolume) {
            plyrInstance.volume = brmediaSettings.defaultVolume;
        }
    });
});