<?php
/**
 * Check products in WooCommerce.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$args = array(
    'limit' => -1,
);
$products = wc_get_products($args);

echo "PRODUCTS LIST:\n";
foreach ($products as $p) {
    echo "- ID: " . $p->get_id() . " | Name: " . $p->get_name() . " | Type: " . $p->get_type() . " | Virtual: " . ($p->is_virtual() ? 'YES' : 'NO') . " | Needs Shipping: " . ($p->needs_shipping() ? 'YES' : 'NO') . "\n";
}
