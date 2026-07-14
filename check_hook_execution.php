<?php
/**
 * Check where checkout hooks are executed.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

// We will simulate a WooCommerce checkout page render by triggering hooks and seeing what is hooked
$hooks = array(
    'woocommerce_before_checkout_form',
    'woocommerce_checkout_before_customer_details',
    'woocommerce_checkout_billing',
    'woocommerce_checkout_shipping',
    'woocommerce_checkout_after_customer_details',
    'woocommerce_checkout_before_order_review',
    'woocommerce_checkout_order_review',
    'woocommerce_checkout_after_order_review',
    'woocommerce_after_checkout_form'
);

echo "REGISTERED HANDLERS FOR CHECKOUT HOOKS:\n\n";
foreach ($hooks as $hook) {
    echo "Hook: $hook\n";
    if (has_action($hook)) {
        global $wp_filter;
        $filters = $wp_filter[$hook];
        foreach ($filters->callbacks as $priority => $callbacks) {
            foreach ($callbacks as $idx => $cb) {
                $function_name = is_string($cb['function']) ? $cb['function'] : (is_array($cb['function']) ? get_class($cb['function'][0]) . '->' . $cb['function'][1] : 'closure');
                echo "  - Priority: $priority | Function: $function_name\n";
            }
        }
    } else {
        echo "  - No handlers registered.\n";
    }
    echo "\n";
}
