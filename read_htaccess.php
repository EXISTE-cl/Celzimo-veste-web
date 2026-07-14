<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "=== .HTACCESS ACTUAL ===\n";
$htaccess = ABSPATH . '.htaccess';
if (file_exists($htaccess)) {
    echo file_get_contents($htaccess);
} else {
    echo "(no existe)\n";
}

echo "\n\n=== TEST POST CON HEADERS REALES DE NAVEGADOR ===\n";
// Probar con headers idénticos a un navegador real
$url = home_url('/?wc-ajax=update_order_review');
$nonce = wp_create_nonce('update-order-review');
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'security' => $nonce,
        'payment_method' => 'cod',
        'post_data' => 'billing_first_name=Test&billing_last_name=Test&billing_country=CL&billing_state=RM&billing_city=Santiago&billing_postcode=&billing_phone=56912345678&billing_email=test@test.com&payment_method=cod',
    ]),
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'X-Requested-With: XMLHttpRequest',
        'Origin: https://celzimoveste.cl',
        'Referer: https://celzimoveste.cl/checkout/',
        'Accept: */*',
        'Accept-Language: es-419,es;q=0.9',
    ],
]);
$body = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP {$code}: " . substr($body, 0, 400) . "\n";
