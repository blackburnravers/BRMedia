jQuery(document).ready(function($) {

    // Click-to-copy shortcode
    $('.brmedia-shortcode-section code').on('click', function() {
        const $temp = $('<input>');
        $('body').append($temp);
        $temp.val($(this).text()).select();
        document.execCommand('copy');
        $temp.remove();

        const msg = $('<span class="brmedia-copy-msg">Copied!</span>');
        $(this).after(msg);
        setTimeout(() => msg.fadeOut(300, () => msg.remove()), 1000);
    });

});