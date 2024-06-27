function open_chat(order_id) {
    var url = 'chat' + '?order_number=' + encodeURIComponent(order_id);
    // Redirect to the new page
    window.location.href = url;
}