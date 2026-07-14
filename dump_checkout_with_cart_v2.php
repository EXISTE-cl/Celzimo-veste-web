<?php
/**
 * Dump checkout page markup with product 45.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

// Ensure session and cart are initialized
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

WC()->cart->empty_cart();
WC()->cart->add_to_cart(45, 1);
echo "Added product 45 to cart. Cart total: " . WC()->cart->get_cart_contents_count() . "\n";

// Render Checkout Page
global $post;
$checkout_page_id = wc_get_page_id('checkout');
$post = get_post($checkout_page_id);
setup_postdata($post);

ob_start();
echo do_shortcode('[woocommerce_checkout]');
$html = ob_get_clean();

echo "Rendered HTML length: " . strlen($html) . "\n";

// Save to file for inspection
file_put_contents(ABSPATH . 'checkout_rendered.html', $html);
echo "Saved HTML to checkout_rendered.html\n";
