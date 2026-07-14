<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "=== DIAGNÓSTICO CRÍTICO ===\n\n";

// ── 1. Test conectividad Transbank TEST API ───────────────────────────────────
echo "--- CONECTIVIDAD TRANSBANK API ---\n";
$endpoints = [
    'TEST WebPay' => 'https://webpay3gint.transbank.cl/rswebpaytransaction/api/webpay/v1.3/transactions',
    'TEST DNS'    => 'webpay3gint.transbank.cl',
    'PROD WebPay' => 'https://webpay3g.transbank.cl/rswebpaytransaction/api/webpay/v1.3/transactions',
];

foreach ($endpoints as $label => $url) {
    if (strpos($url, 'http') === 0) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 8,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_NOBODY => true,
        ]);
        $t1 = microtime(true);
        curl_exec($ch);
        $ms = round((microtime(true) - $t1) * 1000);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            echo "  ❌ {$label}: ERROR - {$err} ({$ms}ms)\n";
        } else {
            echo "  ✅ {$label}: HTTP {$code} ({$ms}ms)\n";
        }
    }
}

// ── 2. Handles exactos de scripts Transbank registrados ──────────────────────
echo "\n--- HANDLES TRANSBANK REGISTRADOS ---\n";
global $wp_scripts;
$found_tbk = [];
if ($wp_scripts) {
    foreach ($wp_scripts->registered as $handle => $script) {
        if (stripos($handle, 'transbank') !== false || stripos($handle, 'tbk') !== false || stripos($handle, 'webpay') !== false) {
            $found_tbk[] = $handle;
            echo "  [{$handle}]\n";
            echo "    src: {$script->src}\n";
            echo "    deps: " . implode(', ', $script->deps ?? []) . "\n";
            echo "    in_footer: " . ($script->extra['in_footer'] ?? 'no') . "\n";
        }
    }
}
if (empty($found_tbk)) {
    echo "  (ninguno encontrado en este contexto — normal si no es página de checkout)\n";
}

// ── 3. Verificar conflicto THWCFE + Blocks ───────────────────────────────────
echo "\n--- PLUGINS ACTIVOS CON DEPENDENCIAS BLOCKS ---\n";
$block_plugins = [
    'woo-checkout-field-editor-pro/checkout-form-designer.php' => 'THWCFE (Checkout Field Editor Pro)',
    'fluid-checkout/fluid-checkout.php'                        => 'Fluid Checkout',
    'transbank-webpay-plus-rest/webpay-rest.php'               => 'Transbank WebPay Plus',
];
$active = get_option('active_plugins', []);
foreach ($block_plugins as $file => $name) {
    $is_active = in_array($file, $active);
    echo "  " . ($is_active ? '✅' : '⬜') . " {$name}\n";
}

// ── 4. Verificar WooCommerce config (HPOS, Blocks) ───────────────────────────
echo "\n--- WOOCOMMERCE CONFIG ---\n";
echo "  HPOS activo: " . (get_option('woocommerce_custom_orders_table_enabled') === 'yes' ? 'SÍ' : 'NO') . "\n";
echo "  Block checkout: " . (get_option('woocommerce_block_checkout_enabled') ? 'SÍ' : 'NO') . "\n";
echo "  Cart block: " . (get_option('woocommerce_block_cart_enabled') ? 'SÍ' : 'NO') . "\n";
echo "  Moneda: " . get_woocommerce_currency() . "\n";

// ── 5. Simular el AJAX update_order_review ─────────────────────────────────
echo "\n--- SIMULACIÓN AJAX update_order_review ---\n";
// Agregar un producto al carrito para poder simular
WC()->session->set_customer_session_cookie(true);

$products = wc_get_products(['limit' => 1, 'status' => 'publish']);
if (!empty($products)) {
    $product = $products[0];
    WC()->cart->empty_cart();
    WC()->cart->add_to_cart($product->get_id(), 1);

    // Simular datos del checkout
    $_POST = [
        'security'       => wp_create_nonce('update-order-review'),
        'post_data'      => 'billing_country=CL&billing_state=RM&billing_city=Santiago&payment_method=transbank_webpay_plus_rest',
        'payment_method' => 'transbank_webpay_plus_rest',
    ];

    ob_start();
    $start = microtime(true);
    try {
        WC_AJAX::update_order_review();
    } catch (Throwable $e) {
        echo "EXCEPCIÓN: " . $e->getMessage() . "\n";
    }
    $ajaxOutput = ob_get_clean();
    $elapsed = round((microtime(true) - $start) * 1000);

    echo "  Tiempo: {$elapsed}ms\n";
    $decoded = json_decode($ajaxOutput, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "  ✅ Respuesta JSON válida\n";
        echo "  Claves: " . implode(', ', array_keys($decoded)) . "\n";
        if (isset($decoded['result'])) {
            echo "  result: " . $decoded['result'] . "\n";
        }
        // Verificar si hay HTML de métodos de pago
        if (isset($decoded['fragments']['div.wc-block-components-payment-methods'])) {
            echo "  ✅ Fragmento blocks payment presente\n";
        }
        if (isset($decoded['fragments']['.woocommerce-checkout-payment'])) {
            echo "  ✅ Fragmento classic payment presente\n";
            // Verificar si Transbank aparece
            $payHTML = $decoded['fragments']['.woocommerce-checkout-payment'];
            if (stripos($payHTML, 'transbank') !== false || stripos($payHTML, 'webpay') !== false) {
                echo "  ✅ Transbank WebPay aparece en el HTML de pago\n";
            } else {
                echo "  ⚠️  Transbank WebPay NO aparece en HTML de pago\n";
            }
        }
    } else {
        echo "  ⚠️  Respuesta no es JSON válido. Primeros 500 chars:\n";
        echo "  " . substr($ajaxOutput, 0, 500) . "\n";
    }
} else {
    echo "  No hay productos para simular.\n";
}

echo "\n✅ Diagnóstico completado.\n";
