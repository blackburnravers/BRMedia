// BRMedia Admin JS

jQuery(document).ready(function($) {

    // Media uploader functionality
    $('.brmedia-upload-button').on('click', function(e) {
        e.preventDefault();

        var button = $(this);
        var targetField = $('#' + button.data('target'));

        var file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select or Upload File',
            button: {
                text: 'Use This File'
            },
            multiple: false
        });

        file_frame.on('select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            targetField.val(attachment.url);
            targetField.trigger('change');
        });

        file_frame.open();
    });

    // Color picker
    if ($('.brmedia-color-picker').length) {
        $('.brmedia-color-picker').wpColorPicker();
    }

    // Tabs
    $('.brmedia-tab-nav a').click(function(e) {
        e.preventDefault();
        $('.brmedia-tab-nav a').removeClass('active');
        $(this).addClass('active');
        var target = $(this).data('tab');
        $('.brmedia-tab-pane').hide();
        $('#' + target).fadeIn(200);
    });

    // Font Awesome icon preview
    $('.brmedia-icon-select').on('change', function () {
        var iconClass = $(this).val();
        var preview = $(this).closest('.brmedia-icon-picker').find('.icon-preview');
        preview.attr('class', 'icon-preview ' + iconClass);
    });

    // Reset button confirm
    $('.brmedia-reset-settings').on('click', function(e) {
        if (!confirm('Are you sure you want to reset this section to default settings?')) {
            e.preventDefault();
        }
    });

    // Live preview for toggle switches
    $('.brmedia-toggle-input').on('change', function() {
        var target = $(this).data('target');
        if ($(this).is(':checked')) {
            $('#' + target).slideDown();
        } else {
            $('#' + target).slideUp();
        }
    });

});