<?php
/**
 * Fix checkout field configuration:
 * - Move tipo_documento to billing section only (not shipping)
 * - Ensure email is at top
 * - Ensure phone shows +56 placeholder
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$results = [];

// Get raw option keys used by THWCFD
global $wpdb;
$options = $wpdb->get_results(
    "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '%thwcf%' OR option_name LIKE '%wcfd%' OR option_name LIKE '%checkout_field%'",
    ARRAY_A
);
$results['option_keys'] = array_column($options, 'option_name');

// Check what's stored
foreach ($results['option_keys'] as $key) {
    $val = get_option($key);
    if (is_array($val) || is_object($val)) {
        $results['option_values'][$key] = print_r($val, true);
    } else {
        $results['option_values'][$key] = $val;
    }
}

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
