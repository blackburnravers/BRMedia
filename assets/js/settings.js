jQuery(document).ready(function($) {

    // Tab Navigation
    $('.brmedia-tab-wrapper a').on('click', function(e) {
        e.preventDefault();

        $('.brmedia-tab-wrapper a').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');

        $('.brmedia-settings-section').hide();
        $($(this).attr('href')).fadeIn();
    });

    // Activate first tab by default
    $('.brmedia-tab-wrapper a:first').click();

    // Color Picker
    if (typeof $.fn.wpColorPicker !== 'undefined') {
        $('.wp-color-picker-field').wpColorPicker();
    }

    // Range Sliders
    $('input[type="range"]').on('input change', function() {
        $(this).next('.brmedia-range-display').text($(this).val());
    });

});