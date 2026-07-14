<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "=== TEST ENDPOINT CHECKOUT ===\n\n";

// Crear un carrito válido para la prueba
WC()->session->set_customer_session_cookie(true);
WC()->cart->empty_cart();
$products = wc_get_products(['limit' => 1, 'status' => 'publish']);
if (empty($products)) die("No hay productos");
WC()->cart->add_to_cart($products[0]->get_id(), 1);

$nonce = wp_create_nonce('woocommerce-process_checkout');

$post_data = [
    'billing_first_name' => 'Test',
    'billing_last_name' => 'User',
    'billing_country' => 'CL',
    'billing_address_1' => 'Test 123',
    'billing_city' => 'Santiago',
    'billing_state' => 'RM',
    'billing_postcode' => '8320000',
    'billing_phone' => '56912345678',
    'billing_email' => 'test@test.com',
    'payment_method' => 'transbank_webpay_plus_rest',
    'woocommerce-process-checkout-nonce' => $nonce,
    '_wp_http_referer' => '/checkout/'
];

$url = home_url('/?wc-ajax=checkout');

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($post_data),
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/126.0.0.0',
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'X-Requested-With: XMLHttpRequest',
        'Origin: ' . home_url(),
        'Referer: ' . home_url('/checkout/'),
        'Accept: application/json, text/javascript, */*; q=0.01'
    ]
]);

ob_start();
$t = microtime(true);
$body = curl_exec($ch);
$ms = round((microtime(true) - $t) * 1000);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$errors = ob_get_clean();

echo "HTTP {$code} ({$ms}ms)\n";
if ($errors) echo "PHP Errors/Warnings:\n{$errors}\n";

echo "\nCuerpo de la respuesta:\n";
echo substr($body, 0, 1000) . "\n";

// Analizar si es JSON válido
$json = json_decode($body, true);
if (json_last_error() === JSON_ERROR_NONE) {
    echo "\n✅ Respuesta es JSON Válido:\n";
    print_r($json);
} else {
    echo "\n❌ Respuesta NO es JSON válido. Error: " . json_last_error_msg() . "\n";
    
    // Ver si ModSecurity lo bloqueó
    if (strpos($body, 'Mod_Security') !== false || strpos($body, 'Not Acceptable') !== false) {
        echo "\n⚠️ BLOQUEADO POR MODSECURITY\n";
    }
}

WC()->cart->empty_cart();

echo "\n✅ Test completado.\n";
