<?php
/**
 * Activate Blue Express plugin via PHP.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$results = [];
$plugin = 'bluex-for-woocommerce/woocommerce-bluex.php';

$all_plugins = get_plugins();

if (isset($all_plugins[$plugin])) {
    $result = activate_plugin($plugin);
    if (is_wp_error($result)) {
        $results['activation'] = '❌ Error: ' . $result->get_error_message();
    } else {
        $results['activation'] = '✅ Activado correctamente';
    }
} else {
    $results['activation'] = '⚠️ Plugin no encontrado en el servidor';
}

$active_plugins = get_option('active_plugins', []);
$results['now_active'] = $active_plugins;

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
