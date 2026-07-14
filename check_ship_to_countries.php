<?php
/**
 * Check ship to countries option.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

echo "woocommerce_ship_to_countries: " . get_option('woocommerce_ship_to_countries') . "\n";
echo "woocommerce_allowed_countries: " . get_option('woocommerce_allowed_countries') . "\n";
echo "woocommerce_ship_to_destination: " . get_option('woocommerce_ship_to_destination') . "\n";
