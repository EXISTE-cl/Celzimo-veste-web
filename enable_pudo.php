<?php
/**
 * Enable PUDO and active logs settings in WooCommerce.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$key = 'woocommerce_correios-integration_settings';
$settings = get_option($key, []);

// Enable PUDO (pickup points) and debug logs
$settings['pudoEnable'] = 'yes';
$settings['active_logs'] = 'yes';

$result = update_option($key, $settings);

$output = [
    'success' => $result !== false,
    'settings' => get_option($key)
];

echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
