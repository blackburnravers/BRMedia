jQuery(document).ready(function($) {

    $('.brmedia-social-share .share-btn').on('click', function(e) {
        const url = $(this).attr('href');
        const width = 600;
        const height = 400;
        const left = (screen.width / 2) - (width / 2);
        const top = (screen.height / 2) - (height / 2);

        // Open share in new popup window
        window.open(
            url,
            'brmedia_share',
            'toolbar=no, location=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=' + width + ', height=' + height + ', top=' + top + ', left=' + left
        );

        // Optional: AJAX log
        const mediaId = $(this).closest('.brmedia-player').data('player-id');
        const platform = $(this).attr('class').split('share-')[1];

        if (mediaId && platform) {
            $.post(brmedia_vars.ajax_url, {
                action: 'brmedia_log_share',
                media_id: mediaId,
                platform: platform,
                nonce: brmedia_vars.nonce
            });
        }

        e.preventDefault();
    });

});