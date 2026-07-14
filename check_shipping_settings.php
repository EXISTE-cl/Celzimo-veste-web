<?php
/**
 * Check WooCommerce shipping settings.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

echo "WOOCOMMERCE GENERAL SHIPPING SETTINGS:\n";
echo "- Shipping location: " . get_option('woocommerce_ship_to_destination') . "\n";
echo "- Ship to countries: " . get_option('woocommerce_allowed_countries') . "\n";
echo "- Shipping allowed: " . get_option('woocommerce_shipping_enabled') . "\n";
echo "- Shipping methods: \n";

$shipping_zones = WC_Shipping_Zones::get_zones();
foreach ($shipping_zones as $zone) {
    echo "  * Zone: " . $zone['zone_name'] . "\n";
    $methods = $zone['shipping_methods'];
    foreach ($methods as $m) {
        echo "    - Method: " . $m->id . " | Enabled: " . $m->enabled . "\n";
    }
}
