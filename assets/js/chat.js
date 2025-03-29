jQuery(document).ready(function($) {

    const chatForm = $('.brmedia-chat-form');
    const messageInput = $('#brmedia-chat-message');
    const messageList = $('.brmedia-chat-messages');
    const postId = chatForm.data('post-id');

    // Submit chat message
    chatForm.on('submit', function(e) {
        e.preventDefault();

        const message = messageInput.val().trim();
        if (!message) return;

        $.post(brmedia_vars.ajax_url, {
            action: 'brmedia_send_chat_message',
            post_id: postId,
            message: message,
            nonce: brmedia_vars.nonce
        }, function(response) {
            if (response.success) {
                messageInput.val('');
                loadMessages(); // Refresh chat
            } else {
                alert(response.data || 'Error sending message.');
            }
        });
    });

    // Load messages
    function loadMessages() {
        $.post(brmedia_vars.ajax_url, {
            action: 'brmedia_load_chat_messages',
            post_id: postId,
            nonce: brmedia_vars.nonce
        }, function(response) {
            if (response.success) {
                messageList.html(response.data);
                messageList.scrollTop(messageList[0].scrollHeight);
            }
        });
    }

    // Initial load
    loadMessages();

    // Refresh every 10 seconds
    setInterval(loadMessages, 10000);
});