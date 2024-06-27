function open_cart(order_id) {
    var url = 'checkout' + '?order_number=' + encodeURIComponent(order_id);
    // Redirect to the new page
    window.location.href = url;
}