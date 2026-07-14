<?php
/**
 * Script to inspect the WordPress setup and WooCommerce cart page remotely.
 */
define('WP_USE_THEMES', false);
require_once('wp-load.php');

if (!current_user_can('manage_options') && ($_GET['token'] ?? '') !== 'Csc170431Activation') {
    die('Unauthorized');
}

echo "--- WordPress / WooCommerce Inspection ---\n";
echo "Active Theme: " . get_option('current_theme') . " (" . get_stylesheet() . ")\n";

echo "\n--- Active Plugins ---\n";
$active_plugins = get_option('active_plugins');
foreach ($active_plugins as $plugin) {
    echo "- $plugin\n";
}

echo "\n--- Cart Page Info ---\n";
$cart_page_id = wc_get_page_id('cart');
echo "Cart Page ID: $cart_page_id\n";
if ($cart_page_id) {
    $cart_page = get_post($cart_page_id);
    echo "Cart Page Content:\n";
    echo $cart_page->post_content . "\n";
    echo "Cart Page Template: " . get_post_meta($cart_page_id, '_wp_page_template', true) . "\n";
}

echo "\n--- WooCommerce Cart Settings ---\n";
echo "Cart Totals Template Overridden: " . (locate_template('woocommerce/cart/cart-totals.php') ? 'Yes' : 'No') . "\n";
echo "Cart Template Overridden: " . (locate_template('woocommerce/cart/cart.php') ? 'Yes' : 'No') . "\n";

echo "\n--- Actions Hooked to woocommerce_cart_collaterals ---\n";
global $wp_filter;
if (isset($wp_filter['woocommerce_cart_collaterals'])) {
    foreach ($wp_filter['woocommerce_cart_collaterals']->callbacks as $priority => $callbacks) {
        echo "Priority $priority:\n";
        foreach ($callbacks as $idx => $callback) {
            echo "  - " . $idx . "\n";
        }
    }
}
