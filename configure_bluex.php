<?php
/**
 * Configure Blue Express integration ID via PHP.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$key = 'woocommerce_correios-integration_settings';
$settings = get_option($key, []);

// Update the integration ID
$settings['tracking_bxkey'] = '6a55383eeb7eeb0e5bd88541';

$result = update_option($key, $settings);

$output = [
    'success' => $result !== false,
    'settings' => get_option($key)
];

echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
