function open_product(product_id) {
    var url = 'edit-product' + '?product_number=' + encodeURIComponent(product_id);

    // Redirect to the new page
    window.location.href = url;
}