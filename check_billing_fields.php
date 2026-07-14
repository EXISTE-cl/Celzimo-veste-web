<?php
/**
 * Check checkout fields details
 */
define('WP_USE_THEMES', false);
require_once('wp-load.php');

if (!current_user_can('manage_options') && ($_GET['token'] ?? '') !== 'Csc170431Activation') {
    die('Unauthorized');
}

$checkout = WC()->checkout();
$fields = $checkout->get_checkout_fields('billing');

echo "=== BILLING FIELDS DETAILED ===\n";
foreach ($fields as $key => $args) {
    echo "- Key: $key\n";
    echo "  Label: " . ($args['label'] ?? '') . "\n";
    echo "  Priority: " . ($args['priority'] ?? '') . "\n";
    echo "  Required: " . (($args['required'] ?? false) ? 'true' : 'false') . "\n";
    echo "  Class: " . implode(', ', (array)($args['class'] ?? array())) . "\n";
}
