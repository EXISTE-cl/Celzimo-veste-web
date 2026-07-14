<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "=== ROOT CAUSE ANALYSIS ===\n\n";

// ── 1. Leer process_payment() de Transbank ────────────────────────────────────
echo "--- process_payment() TRANSBANK ---\n";
$gw_file = WP_CONTENT_DIR . '/plugins/transbank-webpay-plus-rest/src/PaymentGateways/WC_Gateway_Transbank_Webpay_Plus_REST.php';
$lines = file($gw_file);
$in_method = false;
$depth = 0;
$method_lines = [];
foreach ($lines as $i => $line) {
    if (!$in_method && stripos($line, 'function process_payment') !== false) {
        $in_method = true;
    }
    if ($in_method) {
        $method_lines[] = ($i+1) . ": " . $line;
        $depth += substr_count($line, '{') - substr_count($line, '}');
        if ($depth <= 0 && count($method_lines) > 2) break;
    }
}
echo implode('', $method_lines);

// ── 2. Leer CreateWebpayController para ver la llamada API real ───────────────
echo "\n--- CreateWebpayController (llamada a Transbank API) ---\n";
$ctrl_file = WP_CONTENT_DIR . '/plugins/transbank-webpay-plus-rest/src/Controllers/CreateWebpayController.php';
if (file_exists($ctrl_file)) {
    $content = file_get_contents($ctrl_file);
    echo substr($content, 0, 3000) . "\n";
}

// ── 3. Verificar el nonce de WooCommerce checkout ─────────────────────────────
echo "\n--- NONCE WC CHECKOUT ---\n";
$nonce = wp_create_nonce('woocommerce-process_checkout');
echo "Nonce generado: {$nonce}\n";
echo "Longitud: " . strlen($nonce) . " chars\n";

// Verificar que la cookie de sesión funciona
echo "Session handler: " . session_save_path() . "\n";
echo "WC Session cookie: " . WC()->session->get_session_cookie() . "\n";

// ── 4. Revisar si hay conflicto con wp-config.php ─────────────────────────────
echo "\n--- WP-CONFIG ISSUES ---\n";
$wpconfig = file_get_contents(ABSPATH . 'wp-config.php');
// Encontrar línea donde FORCE_SSL_ADMIN está definido
preg_match_all('/.*FORCE_SSL_ADMIN.*/', $wpconfig, $matches);
echo "Líneas con FORCE_SSL_ADMIN:\n";
foreach ($matches[0] as $m) {
    echo "  " . trim($m) . "\n";
}

// Verificar table prefix
echo "\n\$table_prefix: " . $GLOBALS['table_prefix'] . "\n";

// ── 5. Verificar si el checkout endpoint está OK ──────────────────────────────
echo "\n--- WC AJAX ENDPOINTS ---\n";
echo "checkout: " . WC_AJAX::get_endpoint('checkout') . "\n";
echo "update_order_review: " . WC_AJAX::get_endpoint('update_order_review') . "\n";

// ── 6. Ver si Fluid Checkout tiene configuraciones especiales ─────────────────
echo "\n--- FLUID CHECKOUT OPCIONES ---\n";
$fc_opts = [
    'fc_checkout_layout_option',
    'fc_checkout_background_color',
    'fc_checkout_optional_fields_display',
    'fc_enable_checkout_page_template',
];
foreach ($fc_opts as $opt) {
    echo "  {$opt}: " . (get_option($opt, 'N/D')) . "\n";
}

// Listar TODAS las opciones de Fluid Checkout
global $wpdb;
$all_fc = $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE 'fc_%' LIMIT 30");
echo "\n  Opciones fc_* en DB:\n";
foreach ($all_fc as $row) {
    echo "  {$row->option_name}: {$row->option_value}\n";
}

echo "\n✅ Análisis completado.\n";
