// BRMedia Font Awesome Icon Preview Selector

jQuery(document).ready(function ($) {

    // Open icon picker modal
    $('.brmedia-icon-picker-trigger').on('click', function (e) {
        e.preventDefault();

        const targetInputId = $(this).data('target');
        $('#brmedia-icon-picker-modal').data('target', targetInputId).fadeIn(200);
    });

    // Close icon picker
    $('.brmedia-icon-picker-close').on('click', function () {
        $('#brmedia-icon-picker-modal').fadeOut(200);
    });

    // Select an icon
    $(document).on('click', '.brmedia-icon-picker-grid i', function () {
        const selectedIcon = $(this).attr('class');
        const targetInputId = $('#brmedia-icon-picker-modal').data('target');

        $('#' + targetInputId).val(selectedIcon);
        $('#brmedia-icon-picker-modal').fadeOut(200);
    });

    // Filter icons by search
    $('#brmedia-icon-search').on('keyup', function () {
        const query = $(this).val().toLowerCase();

        $('.brmedia-icon-picker-grid i').each(function () {
            const iconClass = $(this).attr('class');
            if (iconClass.toLowerCase().indexOf(query) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

});