<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "=== TEST ENDPOINTS AJAX WOOCOMMERCE ===\n\n";

$base = home_url('/');
$endpoints = [
    'checkout_page'           => $base . 'checkout/',
    'update_order_review'     => $base . '?wc-ajax=update_order_review',
    'checkout_ajax'           => $base . '?wc-ajax=checkout',
    'wp_ajax'                 => $base . 'wp-admin/admin-ajax.php',
    'cart_fragments'          => $base . '?wc-ajax=get_refreshed_fragments',
];

foreach ($endpoints as $name => $url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        CURLOPT_HTTPHEADER => [
            'Accept: application/json, text/html, */*',
            'X-Requested-With: XMLHttpRequest',
        ],
    ]);
    $t = microtime(true);
    $body = curl_exec($ch);
    $ms = round((microtime(true) - $t) * 1000);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);

    $icon = ($code >= 200 && $code < 400) ? '✅' : (($code >= 500) ? '🔴' : '⚠️');
    echo "{$icon} [{$name}] HTTP {$code} ({$ms}ms)\n";
    if ($err) echo "   curl error: {$err}\n";
    if ($code >= 400 && strlen($body) < 1000) {
        echo "   body: " . substr($body, 0, 200) . "\n";
    }
}

// ── Test POST al endpoint de checkout AJAX ─────────────────────────────────────
echo "\n--- POST a update_order_review ---\n";
$ajax_url = $base . '?wc-ajax=update_order_review';
$nonce = wp_create_nonce('update-order-review');

$ch = curl_init($ajax_url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => "security={$nonce}&payment_method=transbank_webpay_plus_rest&post_data=payment_method%3Dtransbank_webpay_plus_rest",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0',
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/x-www-form-urlencoded',
        'X-Requested-With: XMLHttpRequest',
    ],
]);
$t = microtime(true);
$body = curl_exec($ch);
$ms = round((microtime(true) - $t) * 1000);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP {$code} ({$ms}ms)\n";
echo "Respuesta: " . substr($body, 0, 300) . "\n";

// ── Verificar si hay un firewall/security plugin activo ───────────────────────
echo "\n--- PLUGINS DE SEGURIDAD/CACHÉ ACTIVOS ---\n";
$active = get_option('active_plugins', []);
$security_plugins = [
    'wordfence/wordfence.php'              => 'Wordfence',
    'wp-cerber/cerber.php'                => 'WP Cerber',
    'really-simple-ssl/really-simple-ssl.php' => 'Really Simple SSL',
    'all-in-one-wp-security-and-firewall/wp-security.php' => 'All In One Security',
    'shield-security/icwp-wpsf.php'       => 'Shield Security',
    'litespeed-cache/litespeed-cache.php' => 'LiteSpeed Cache',
    'w3-total-cache/w3-total-cache.php'   => 'W3 Total Cache',
    'wp-super-cache/wp-cache.php'         => 'WP Super Cache',
    'wp-rocket/wp-rocket.php'             => 'WP Rocket',
    'autoptimize/autoptimize.php'         => 'Autoptimize',
    'hummingbird-performance/wp-hummingbird.php' => 'Hummingbird',
];
foreach ($security_plugins as $file => $name) {
    if (in_array($file, $active)) {
        echo "  ⚠️  {$name} está ACTIVO\n";
    }
}

// Listar todos los plugins activos para inspección
echo "\n--- TODOS LOS PLUGINS ACTIVOS ---\n";
foreach ($active as $plugin) {
    echo "  {$plugin}\n";
}

echo "\n✅ Test completado.\n";
