<?php
/**
 * Verbose activation script.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

$themehigh_slug = 'woocommerce-checkout-field-editor/woocommerce-checkout-field-editor.php';

echo "Attempting to activate ThemeHigh plugin...\n";
$result = activate_plugin($themehigh_slug, '', false, false);

if (is_wp_error($result)) {
    echo "WP_Error: " . $result->get_error_message() . "\n";
} else {
    echo "Activation call completed with result: ";
    var_dump($result);
}

echo "\nActive plugins list:\n";
print_r(get_option('active_plugins'));
