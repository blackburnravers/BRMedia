jQuery(document).ready(function($) {

    // Cover Image Uploader
    $('.brmedia-upload-cover').on('click', function(e) {
        e.preventDefault();

        var button = $(this);
        var target = $('#' + button.data('target'));
        var preview = $('#' + button.data('preview'));

        var frame = wp.media({
            title: 'Select Cover Image',
            button: { text: 'Use This Image' },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            target.val(attachment.url);
            preview.attr('src', attachment.url).fadeIn();
        });

        frame.open();
    });

    // Media File Selector
    $('.brmedia-select-media').on('click', function(e) {
        e.preventDefault();

        var button = $(this);
        var target = $('#' + button.data('target'));

        var frame = wp.media({
            title: 'Select Media File',
            button: { text: 'Use This File' },
            library: { type: ['audio', 'video'] },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            target.val(attachment.url);
        });

        frame.open();
    });

    // Audio Preview
    $('.brmedia-audio-preview').on('click', function(e) {
        e.preventDefault();

        var url = $(this).data('url');
        var player = new Audio(url);
        player.play();
    });

    // Upload Tracklist Text File and auto-fill textarea
    $('.brmedia-upload-tracklist').on('click', function(e) {
        e.preventDefault();

        const target = $('#' + $(this).data('target'));

        const frame = wp.media({
            title: 'Select Tracklist File',
            button: { text: 'Use This File' },
            multiple: false
        });

        frame.on('select', function() {
            const attachment = frame.state().get('selection').first().toJSON();
            const fileUrl = attachment.url;

            fetch(fileUrl)
                .then(res => {
                    if (!res.ok) throw new Error('Failed to fetch file');
                    return res.text();
                })
                .then(text => {
                    const formatted = text.replace(/\r\n|\r|\n/g, '\n').trim();
                    target.val(formatted);
                })
                .catch(err => {
                    console.error('Could not load .txt content:', err);
                    target.val('Error loading .txt file.');
                });
        });

        frame.open();
    });

});