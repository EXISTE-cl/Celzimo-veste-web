<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

require_once(ABSPATH . 'wp-admin/includes/plugin.php');

echo "=== VERIFICACIÓN TRANSBANK ===\n\n";

// 1. Plugin instalado?
$all_plugins = get_plugins();
$found = null;
foreach ($all_plugins as $pf => $pd) {
    if (stripos($pf, 'transbank') !== false) {
        $found = ['file' => $pf, 'data' => $pd];
    }
}

if ($found) {
    $active = in_array($found['file'], get_option('active_plugins', []));
    echo "Plugin: {$found['data']['Name']} v{$found['data']['Version']}\n";
    echo "Archivo: {$found['file']}\n";
    echo "Estado: " . ($active ? "✅ ACTIVO" : "❌ INACTIVO") . "\n\n";
} else {
    echo "❌ Plugin de Transbank NO encontrado.\n";
    exit;
}

// 2. Configuración guardada
$settings = get_option('woocommerce_transbank_webpay_plus_rest_settings', []);
echo "Configuración guardada:\n";
if (empty($settings)) {
    echo "  ⚠️  Sin configuración guardada.\n";
} else {
    foreach ($settings as $k => $v) {
        $display = ($k === 'api_key_secret') ? substr($v, 0, 10) . '...' : $v;
        echo "  {$k}: {$display}\n";
    }
}

// 3. Verificar WooCommerce gateways
echo "\nGateways registrados en WooCommerce:\n";
$gateways_option = get_option('woocommerce_gateway_order', []);
foreach ($gateways_option as $gw_id => $order) {
    echo "  - {$gw_id}\n";
}

// 4. Leer directamente la opción habilitada del gateway Transbank
$tbk_settings_key = 'woocommerce_transbank_webpay_plus_rest_settings';
$tbk_opts = get_option($tbk_settings_key);
echo "\nOpción directa '{$tbk_settings_key}':\n";
if ($tbk_opts) {
    echo "  ✅ Existe en base de datos.\n";
    echo "  enabled: " . ($tbk_opts['enabled'] ?? 'N/A') . "\n";
    echo "  environment: " . ($tbk_opts['environment'] ?? 'N/A') . "\n";
    echo "  commerce_code: " . ($tbk_opts['commerce_code'] ?? 'N/A') . "\n";
} else {
    echo "  ⚠️  No encontrada en BD.\n";
}

echo "\n✅ Verificación completada.\n";
echo "👉 Visita tu checkout para ver WebPay como método de pago.\n";
