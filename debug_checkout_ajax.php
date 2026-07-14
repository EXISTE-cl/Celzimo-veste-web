<?php
/**
 * debug_checkout_ajax.php
 * Simula el AJAX update_order_review de WooCommerce y captura errores
 */
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "=== DEBUG: TRANSBANK payment_fields() ===\n\n";

// Cargar las clases de WooCommerce
WC()->frontend_includes();

$gateways_obj = WC()->payment_gateways();
$all_gateways = $gateways_obj->payment_gateways();

echo "Gateways disponibles:\n";
foreach ($all_gateways as $id => $gw) {
    echo "  - {$id} ({$gw->title}) enabled={$gw->enabled}\n";
}

// Buscar gateway Transbank
$tbk_gateway = null;
foreach ($all_gateways as $id => $gw) {
    if (stripos($id, 'transbank') !== false) {
        $tbk_gateway = $gw;
        echo "\n✓ Gateway Transbank encontrado: {$id}\n";
        break;
    }
}

if (!$tbk_gateway) {
    echo "❌ Gateway Transbank NO encontrado en gateways activos.\n";
    echo "   Esto significa que no aparece en el checkout.\n";
    
    // Verificar si está en la lista de gateways deshabilitados
    $disabled = get_option('woocommerce_transbank_webpay_plus_rest_settings', []);
    echo "Settings: " . json_encode($disabled) . "\n";
    exit;
}

echo "\nTesting payment_fields():\n";
echo "---\n";

// Capturar output de payment_fields
ob_start();
try {
    $tbk_gateway->payment_fields();
    $output = ob_get_clean();
    echo "OUTPUT (primeros 500 chars):\n";
    echo substr(strip_tags($output), 0, 500) . "\n";
    echo "---\n";
    echo "✓ payment_fields() ejecutado sin errores.\n";
} catch (Throwable $e) {
    $output = ob_get_clean();
    echo "❌ ERROR en payment_fields(): " . $e->getMessage() . "\n";
    echo "   Línea: " . $e->getLine() . " en " . $e->getFile() . "\n";
}

echo "\n=== SCRIPTS ENCOLADOS por Transbank ===\n";
global $wp_scripts;
if ($wp_scripts) {
    foreach ($wp_scripts->registered as $handle => $script) {
        if (stripos($handle, 'transbank') !== false || stripos($handle, 'webpay') !== false) {
            echo "  Script: {$handle} => {$script->src}\n";
        }
    }
}

echo "\n=== VERIFICAR OPCIÓN enabled ===\n";
$settings = get_option('woocommerce_transbank_webpay_plus_rest_settings', []);
echo "enabled: " . ($settings['enabled'] ?? 'NOT SET') . "\n";
echo "environment: " . ($settings['environment'] ?? 'NOT SET') . "\n";

// Si enabled no es 'yes', forzarlo
if (($settings['enabled'] ?? '') !== 'yes') {
    $settings['enabled'] = 'yes';
    update_option('woocommerce_transbank_webpay_plus_rest_settings', $settings);
    echo "✓ Forzado enabled=yes\n";
}

echo "\n✅ Debug completado.\n";
