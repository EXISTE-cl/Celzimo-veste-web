<?php
/**
 * Dump checkout page markup with cart items.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

// 1. Add product to cart
$products = wc_get_products(array('limit' => 1));
if (!empty($products)) {
    $product_id = $products[0]->get_id();
    if (!WC()->session) {
        WC()->session = new WC_Session_Handler();
        WC()->session->init();
    }
    if (!WC()->cart) {
        WC()->cart = new WC_Cart();
    }
    WC()->cart->empty_cart();
    WC()->cart->add_to_cart($product_id, 1);
}

// 2. Render Checkout Form
global $post;
$checkout_page_id = wc_get_page_id('checkout');
$post = get_post($checkout_page_id);
setup_postdata($post);

ob_start();
echo do_shortcode('[woocommerce_checkout]');
$html = ob_get_clean();

// 3. Search for step progress bar or list steps in HTML
echo "HTML ANALYSIS:\n";
if (preg_match('/<div[^>]*class="[^"]*fc-steps[^"]*"[^>]*>.*?<\/div>/s', $html, $matches)) {
    echo "FOUND STEPS CONTAINER:\n";
    echo esc_html($matches[0]) . "\n";
} else {
    // Look for lines containing "progress" or "fc-"
    $lines = explode("\n", $html);
    foreach ($lines as $line) {
        if (strpos($line, 'fc-step') !== false || strpos($line, 'progress') !== false || strpos($line, 'stepper') !== false) {
            echo trim(esc_html($line)) . "\n";
        }
    }
}
