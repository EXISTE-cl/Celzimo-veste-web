<?php
/**
 * Dump full checkout markup.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

// Make sure session and cart are initialized so WooCommerce renders checkout fields
if (!WC()->session) {
    WC()->session = new WC_Session_Handler();
    WC()->session->init();
}
if (!WC()->customer) {
    WC()->customer = new WC_Customer(get_current_user_id(), true);
}
if (!WC()->cart) {
    WC()->cart = new WC_Cart();
}
if (WC()->cart->is_empty()) {
    $products = wc_get_products(array('limit' => 1));
    if (!empty($products)) {
        WC()->cart->add_to_cart($products[0]->get_id(), 1);
    }
}

global $post;
$checkout_page_id = wc_get_page_id('checkout');
$post = get_post($checkout_page_id);
setup_postdata($post);

ob_start();
echo do_shortcode('[woocommerce_checkout]');
$html = ob_get_clean();

echo "HTML length: " . strlen($html) . "\n";
file_put_contents(__DIR__ . '/checkout_markup.html', $html);
echo "CHECKOUT_HTML_SAVED";
