<?php
/**
 * Test progress bar output on cart page context.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

// Emulate cart page loading
if (WC()->cart) {
    echo "Cart exists. Subtotal: " . WC()->cart->get_subtotal() . "\n";
    echo "Progress Bar Output:\n";
    ob_start();
    celzimo_print_free_shipping_progress_bar();
    $output = ob_get_clean();
    echo $output;
} else {
    echo "Cart not initialized.\n";
}
