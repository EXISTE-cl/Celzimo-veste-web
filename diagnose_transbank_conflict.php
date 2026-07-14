<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "=== DIAGNÓSTICO CHECKOUT + TRANSBANK ===\n\n";

// 1. Versión de Fluid Checkout
require_once(ABSPATH . 'wp-admin/includes/plugin.php');
$plugins = get_plugins();

$fluid = null;
foreach ($plugins as $pf => $pd) {
    if (stripos($pf, 'fluid') !== false) {
        $fluid = $pd;
    }
}
echo "Fluid Checkout: " . ($fluid ? $fluid['Name'] . " v" . $fluid['Version'] : "No encontrado") . "\n";

// 2. Leer error log de PHP (últimas 50 líneas)
$error_log_paths = [
    ABSPATH . '../logs/error_log',
    ABSPATH . 'wp-content/debug.log',
    ini_get('error_log'),
    '/home/' . get_current_user() . '/logs/error_log',
];

echo "\n=== PHP ERROR LOG (últimas líneas) ===\n";
$log_found = false;
foreach ($error_log_paths as $log_path) {
    if ($log_path && file_exists($log_path) && is_readable($log_path)) {
        echo "Log encontrado: {$log_path}\n";
        $lines = file($log_path);
        $last = array_slice($lines, -30);
        foreach ($last as $line) {
            echo $line;
        }
        $log_found = true;
        break;
    }
}
if (!$log_found) {
    echo "No se encontró archivo de log en rutas estándar.\n";
    // Intentar directorio home del usuario FTP
    $home = exec('echo $HOME');
    echo "HOME del proceso: $home\n";
}

// 3. Verificar si WP_DEBUG está activo
echo "\n=== CONFIGURACIÓN DEBUG ===\n";
echo "WP_DEBUG: " . (defined('WP_DEBUG') && WP_DEBUG ? 'ON' : 'OFF') . "\n";
echo "WP_DEBUG_LOG: " . (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG ? 'ON' : 'OFF') . "\n";
echo "ABSPATH: " . ABSPATH . "\n";
echo "WP Content Dir: " . WP_CONTENT_DIR . "\n";

// 4. Verificar opciones de Fluid Checkout que pueden interferir
echo "\n=== OPCIONES FLUID CHECKOUT ===\n";
$fluid_options = [
    'fc_checkout_steps_behaviour',
    'fc_checkout_layout',
    'fc_enable_checkout_page_template',
];
foreach ($fluid_options as $opt) {
    $val = get_option($opt, 'N/A');
    echo "  {$opt}: " . (is_array($val) ? json_encode($val) : $val) . "\n";
}

// 5. Ver scripts encolados relacionados con Transbank/WC
echo "\n=== OPCIONES RELEVANTES WC ===\n";
$wc_payment_order = get_option('woocommerce_gateway_order', []);
echo "Gateway order: " . json_encode($wc_payment_order) . "\n";

// 6. Verificar si hay fragmentos de checkout cacheados con el nuevo gateway
echo "\nTransient wc_fragment_*: ";
global $wpdb;
$fragments = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name LIKE '_transient_wc_fragment_%'");
echo "{$fragments} fragmentos en caché\n";

// Limpiar fragmentos
$deleted = $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_wc_fragment_%'");
echo "✓ Fragmentos eliminados: {$deleted}\n";

// También limpiar transients de WooCommerce
delete_transient('wc_session_' . session_id());
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_woocommerce_%'");
echo "✓ Transients de WooCommerce limpiados\n";

echo "\n✅ Diagnóstico completado.\n";
