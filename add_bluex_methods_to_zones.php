<?php
/**
 * Add Blue Express shipping methods to WooCommerce zones using direct database access.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

global $wpdb;

$table_zones = $wpdb->prefix . 'woocommerce_shipping_zones';
$table_methods = $wpdb->prefix . 'woocommerce_shipping_zone_methods';

// Get all defined shipping zones
$zones = $wpdb->get_results("SELECT zone_id, zone_name FROM $table_zones", ARRAY_A);
if (!$zones) {
    $zones = [];
}

// Add the default zone (Rest of the World, ID 0)
$zones[] = [
    'zone_id' => 0,
    'zone_name' => 'Resto del mundo'
];

$methods_to_add = ['bluex-ex', 'bluex-pudo'];
$results = [];

foreach ($zones as $zone) {
    $zone_id = intval($zone['zone_id']);
    $zone_name = $zone['zone_name'];

    $results[$zone_name] = [
        'zone_id' => $zone_id,
        'added' => [],
        'already_existed' => []
    ];

    // Check existing methods in this zone
    $existing = $wpdb->get_col($wpdb->prepare(
        "SELECT method_id FROM $table_methods WHERE zone_id = %d",
        $zone_id
    ));

    foreach ($methods_to_add as $method_id) {
        if (!in_array($method_id, $existing)) {
            // Direct insert into shipping zone methods table
            $inserted = $wpdb->insert(
                $table_methods,
                [
                    'zone_id' => $zone_id,
                    'method_id' => $method_id,
                    'method_order' => 1,
                    'is_enabled' => 1
                ],
                ['%d', '%s', '%d', '%d']
            );

            if ($inserted) {
                $instance_id = $wpdb->insert_id;
                // Create settings option to mark it enabled
                $option_name = 'woocommerce_' . $method_id . '_' . $instance_id . '_settings';
                update_option($option_name, ['enabled' => 'yes']);
                
                $results[$zone_name]['added'][] = "$method_id (Instance: $instance_id)";
            } else {
                $results[$zone_name]['errors'][] = "No se pudo agregar $method_id";
            }
        } else {
            $results[$zone_name]['already_existed'][] = $method_id;
        }
    }
}

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
