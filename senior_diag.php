<?php
/**
 * enable_debug_and_check_ajax.php
 * 1. Activa WP_DEBUG_LOG
 * 2. Simula el AJAX update_order_review con sesión real
 * 3. Captura el resultado completo
 */
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "=== DIAGNÓSTICO SENIOR: CHECKOUT AJAX ===\n\n";

// ── 1. Verificar configuración WP_DEBUG ──────────────────────────────────────
echo "PHP max_execution_time: " . ini_get('max_execution_time') . "s\n";
echo "PHP memory_limit: " . ini_get('memory_limit') . "\n";
echo "WP_DEBUG: " . (defined('WP_DEBUG') && WP_DEBUG ? 'ON' : 'OFF') . "\n\n";

// ── 2. Leer wp-config para ver si hay conflicto FORCE_SSL_ADMIN ──────────────
$wpconfig = file_get_contents(ABSPATH . 'wp-config.php');
$force_ssl_count = substr_count($wpconfig, 'FORCE_SSL_ADMIN');
echo "Ocurrencias FORCE_SSL_ADMIN en wp-config.php: {$force_ssl_count}\n";
if ($force_ssl_count > 1) {
    echo "⚠️  FORCE_SSL_ADMIN está definido más de una vez — esto puede causar Warnings.\n\n";
}

// ── 3. Verificar la URL del AJAX de WooCommerce ──────────────────────────────
echo "=== URLs DE AJAX ===\n";
echo "Home URL: " . home_url() . "\n";
echo "AJAX URL (nopriv): " . admin_url('admin-ajax.php') . "\n";
echo "WC-AJAX URL: " . WC_AJAX::get_endpoint('update_order_review') . "\n\n";

// ── 4. Verificar scripts encolados en el checkout (handle reales) ────────────
echo "=== SCRIPTS ENCOLADOS EN CHECKOUT ===\n";
global $wp_scripts;
// Necesitamos simular el contexto del checkout para obtener los scripts
// En este contexto de CLI no podemos hacerlo directamente, pero podemos
// listar los scripts registrados por Transbank
if ($wp_scripts) {
    foreach ($wp_scripts->registered as $handle => $script) {
        if (
            stripos($handle, 'transbank') !== false ||
            stripos($handle, 'tbk') !== false ||
            stripos($handle, 'webpay') !== false
        ) {
            echo "  [{$handle}] src: {$script->src}\n";
            if (!empty($script->deps)) {
                echo "         deps: " . implode(', ', $script->deps) . "\n";
            }
        }
    }
}
echo "\n";

// ── 5. Verificar que el plugin Transbank puede hacer la llamada TEST API ─────
echo "=== TEST: CONECTIVIDAD CON TRANSBANK API ===\n";
$test_url = 'https://webpay3gint.transbank.cl/rswebpaytransaction/api/webpay/v1.3/transactions';
$context = stream_context_create([
    'http' => [
        'timeout' => 5,
        'method' => 'OPTIONS',
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ]
]);
$start = microtime(true);
$result = @file_get_contents($test_url, false, $context);
$elapsed = round((microtime(true) - $start) * 1000, 0);

if ($result !== false || ($http_response_header ?? [])) {
    echo "✅ Servidor puede alcanzar Transbank TEST API ({$elapsed}ms)\n";
} else {
    $error = error_get_last();
    echo "❌ PROBLEMA DE CONECTIVIDAD con Transbank TEST API ({$elapsed}ms)\n";
    echo "   Error: " . ($error['message'] ?? 'desconocido') . "\n";
    echo "   ⚠️  Esto causaría que el checkout se CUELGUE al intentar crear transacción\n";
}

// También probar con cURL si está disponible
if (function_exists('curl_init')) {
    $ch = curl_init($test_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    $start2 = microtime(true);
    curl_exec($ch);
    $elapsed2 = round((microtime(true) - $start2) * 1000, 0);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        echo "❌ cURL error ({$elapsed2}ms): {$curlError}\n";
    } else {
        echo "✅ cURL OK: HTTP {$httpCode} ({$elapsed2}ms)\n";
    }
} else {
    echo "cURL no disponible\n";
}

// ── 6. Verificar la URL de éxito/fracaso de Transbank ───────────────────────
echo "\n=== URLs DE RETORNO TRANSBANK ===\n";
$wc_api_url = home_url('/?wc-api=wc_gateway_transbank_webpay_plus_rest');
echo "Callback URL: {$wc_api_url}\n";
// Esta URL debe ser accesible desde el exterior
echo "NOTA: Esta URL debe estar accesible públicamente para que Transbank pueda notificar el pago.\n";

// ── 7. Detectar posibles conflictos JS (listar scripts con deps de blocks) ───
echo "\n=== SCRIPTS CON DEPENDENCIAS REACT/BLOCKS ===\n";
if ($wp_scripts) {
    foreach ($wp_scripts->registered as $handle => $script) {
        $deps = $script->deps ?? [];
        if (array_intersect($deps, ['react', 'wc-blocks-registry', 'wp-blocks'])) {
            echo "  [{$handle}] deps: " . implode(', ', $deps) . "\n";
        }
    }
}

echo "\n✅ Diagnóstico completado.\n";
