<?php
/**
 * Add a variation to the cart and dump checkout HTML.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

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

// Find a variation ID
$args = array(
    'post_type' => 'product_variation',
    'posts_per_page' => 1,
);
$variations = get_posts($args);

if (!empty($variations)) {
    $variation_id = $variations[0]->ID;
    echo "Found variation ID: $variation_id\n";
    $added = WC()->cart->add_to_cart($variations[0]->post_parent, 1, $variation_id);
    echo "Added to cart: " . ($added ? 'YES' : 'NO') . "\n";
} else {
    // If no variations found, search for simple products or create one
    echo "No variations found. Let's find simple products.\n";
    $products = wc_get_products(array('type' => 'simple', 'limit' => 1));
    if (!empty($products)) {
        $pid = $products[0]->get_id();
        echo "Found simple product ID: $pid\n";
        WC()->cart->add_to_cart($pid, 1);
    } else {
        echo "No simple products. Let's create one.\n";
        $product = new WC_Product_Simple();
        $product->set_name("Producto Físico de Prueba");
        $product->set_regular_price("19990");
        $product->set_status("publish");
        $pid = $product->save();
        WC()->cart->add_to_cart($pid, 1);
        echo "Created and added simple product ID: $pid\n";
    }
}

echo "Items in cart: " . WC()->cart->get_cart_contents_count() . "\n";

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
