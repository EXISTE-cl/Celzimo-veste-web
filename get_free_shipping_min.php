<?php
/**
 * Get free shipping minimum amount.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$shipping_zones = WC_Shipping_Zones::get_zones();
$found = false;
foreach ($shipping_zones as $zone) {
    $methods = $zone['shipping_methods'];
    foreach ($methods as $method) {
        if ($method->id === 'free_shipping' && $method->enabled === 'yes') {
            $min_amount = $method->get_option('min_amount');
            echo "Zone: " . $zone['zone_name'] . " | Method: " . $method->id . " | Min Amount: " . $min_amount . "\n";
            $found = true;
        }
    }
}

// Also check default zone (Rest of the world)
$default_zone = WC_Shipping_Zones::get_zone_by('zone_id', 0);
if ($default_zone) {
    $methods = $default_zone->get_shipping_methods(true);
    foreach ($methods as $method) {
        if ($method->id === 'free_shipping' && $method->enabled === 'yes') {
            $min_amount = $method->get_option('min_amount');
            echo "Zone: Rest of the World | Method: free_shipping | Min Amount: " . $min_amount . "\n";
            $found = true;
        }
    }
}

if (!$found) {
    echo "No active free shipping method found.\n";
}
