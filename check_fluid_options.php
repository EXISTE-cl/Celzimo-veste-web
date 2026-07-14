<?php
/**
 * Check Fluid Checkout options.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

if (class_exists('FluidCheckout_Settings')) {
    echo "FLUID CHECKOUT OPTIONS:\n";
    $options = array(
        'fc_enable_checkout_progress_bar',
        'fc_enable_checkout_sticky_progress_bar',
        'fc_checkout_layout',
        'fc_enable_checkout_local_pickup',
        'fc_shipping_fields_before_billing',
        'fc_show_billing_same_as_shipping',
        'fc_enable_distraction_free_checkout',
        'fc_design_layout_pack'
    );
    foreach ($options as $opt) {
        echo "- $opt: " . FluidCheckout_Settings::instance()->get_option($opt) . "\n";
    }
} else {
    echo "FluidCheckout_Settings class not found.\n";
}
