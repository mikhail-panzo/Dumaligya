function open_seller(seller_id) {
    var url = 'seller' + '?seller_number=' + encodeURIComponent(seller_id);
    // Redirect to the new page
    window.location.href = url;
}