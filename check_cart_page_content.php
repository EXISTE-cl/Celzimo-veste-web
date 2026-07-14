<?php
/**
 * Check and convert WooCommerce Cart page to shortcode if it uses blocks.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$cart_page_id = wc_get_page_id('cart');
if ($cart_page_id && $cart_page_id !== -1) {
    $post = get_post($cart_page_id);
    echo "Cart Page ID: " . $cart_page_id . "\n";
    echo "Current Content:\n" . $post->post_content . "\n\n";
    
    // If it contains the wp:woocommerce/cart block, let's change it to [woocommerce_cart]
    if (strpos($post->post_content, 'wp:woocommerce/cart') !== false) {
        echo "Detected Gutenberg Cart block. Converting to [woocommerce_cart] shortcode...\n";
        $updated_post = array(
            'ID'           => $cart_page_id,
            'post_content' => '[woocommerce_cart]'
        );
        $res = wp_update_post($updated_post);
        if ($res) {
            echo "Successfully converted Cart page to shortcode!\n";
        } else {
            echo "Failed to convert Cart page.\n";
        }
    } else {
        echo "Cart page is already using shortcode or other content.\n";
    }
} else {
    echo "Cart page not found in WooCommerce settings.\n";
}
