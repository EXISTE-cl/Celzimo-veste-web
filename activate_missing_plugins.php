<?php
/**
 * Activate required plugins via PHP.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

// Require the plugin functions
require_once ABSPATH . 'wp-admin/includes/plugin.php';

$results = [];

// List of plugins to activate
$to_activate = [
    'comunas-de-chile-para-woocommerce/comunas-de-chile-para-woocommerce.php',
    'woo-checkout-field-editor-pro/woo-checkout-field-editor-pro.php',
];

// First, let's verify what plugins are physically present
$all_plugins = get_plugins();
$results['available_plugins'] = array_keys($all_plugins);

foreach ($to_activate as $plugin) {
    if (isset($all_plugins[$plugin])) {
        $result = activate_plugin($plugin);
        if (is_wp_error($result)) {
            $results['activation'][$plugin] = '❌ Error: ' . $result->get_error_message();
        } else {
            $results['activation'][$plugin] = '✅ Activado correctamente';
        }
    } else {
        $results['activation'][$plugin] = '⚠️ Plugin no encontrado en el servidor';
    }
}

// Confirm current state
$active_plugins = get_option('active_plugins', []);
$results['now_active'] = $active_plugins;

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
