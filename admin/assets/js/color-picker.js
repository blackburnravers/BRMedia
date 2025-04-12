(function($) {
    const initColorPickers = () => {
        $('.brmedia-color-field').each(function() {
            const $input = $(this);
            $input.wpColorPicker({
                defaultColor: $input.data('default'),
                change: function(event, ui) {
                    const color = ui.color.toString();
                    $input.trigger('brmedia:color-change', [color]);
                    updatePreviewColors();
                },
                clear: () => {
                    $input.trigger('brmedia:color-clear');
                    updatePreviewColors();
                }
            });
        });
    };

    const updatePreviewColors = () => {
        const colors = {
            primary: $('#player_primary_color').val(),
            secondary: $('#player_secondary_color').val(),
            background: $('#player_background_color').val()
        };

        $('.mock-player').css({
            'background-color': colors.background,
            '--primary-color': colors.primary,
            '--secondary-color': colors.secondary
        });
    };

    $(document).ready(() => {
        initColorPickers();
        $('.brmedia-color-field').on('color:change', updatePreviewColors);
    });

    $(window).on('brmedia:settings-loaded', initColorPickers);
})(jQuery);