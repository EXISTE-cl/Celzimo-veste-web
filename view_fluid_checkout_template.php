<?php
/**
 * View Fluid Checkout form-checkout.php template.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$file = WP_PLUGIN_DIR . '/fluid-checkout/templates/fc/checkout-steps/checkout/form-checkout.php';
if (file_exists($file)) {
    echo "FILE CONTENT of form-checkout.php:\n";
    echo file_get_contents($file);
} else {
    echo "File not found.\n";
}
