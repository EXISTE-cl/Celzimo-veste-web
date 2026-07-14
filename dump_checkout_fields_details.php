<?php
/**
 * Dump exact labels and keys of checkout fields.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$checkout = WC()->checkout();
$fields = $checkout->get_checkout_fields();

echo "BILLING FIELDS:\n";
foreach ($fields['billing'] as $key => $args) {
    $enabled = isset($args['enabled']) ? ($args['enabled'] ? 'YES' : 'NO') : 'YES (default)';
    $class = isset($args['class']) ? implode(', ', $args['class']) : 'none';
    echo "- $key | Label: {$args['label']} | Type: " . ($args['type'] ?? 'text') . " | Required: " . ($args['required'] ? 'YES' : 'NO') . " | Enabled: $enabled | Class: $class | Priority: " . ($args['priority'] ?? 'none') . "\n";
}

echo "\nSHIPPING FIELDS:\n";
foreach ($fields['shipping'] as $key => $args) {
    $enabled = isset($args['enabled']) ? ($args['enabled'] ? 'YES' : 'NO') : 'YES (default)';
    $class = isset($args['class']) ? implode(', ', $args['class']) : 'none';
    echo "- $key | Label: {$args['label']} | Type: " . ($args['type'] ?? 'text') . " | Required: " . ($args['required'] ? 'YES' : 'NO') . " | Enabled: $enabled | Class: $class | Priority: " . ($args['priority'] ?? 'none') . "\n";
}
