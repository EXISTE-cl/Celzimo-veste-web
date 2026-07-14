<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "=== ANÁLISIS REAL DEL CHECKOUT HTML ===\n\n";

// Obtener el HTML real del checkout como lo ve un navegador
$checkout_url = home_url('/checkout/');
$ch = curl_init($checkout_url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (diagnostic)',
    CURLOPT_COOKIEJAR => '/tmp/tbk_checkout_cookie.txt',
    CURLOPT_COOKIEFILE => '/tmp/tbk_checkout_cookie.txt',
    CURLOPT_HTTPHEADER => ['Accept: text/html'],
]);
$html = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "HTTP Code: {$httpCode}\n";
echo "Final URL: {$finalUrl}\n";
echo "HTML size: " . strlen($html) . " bytes\n\n";

if ($html) {
    // Verificar si es una página de checkout real
    $hasCheckoutForm = strpos($html, 'form.checkout') !== false || strpos($html, 'woocommerce-checkout') !== false;
    $hasTransbank = stripos($html, 'transbank') !== false || stripos($html, 'webpay') !== false;
    $hasFluidCheckout = stripos($html, 'fluid-checkout') !== false || stripos($html, 'fluid_checkout') !== false;

    echo "¿Tiene forma checkout?: " . ($hasCheckoutForm ? 'SÍ' : 'NO') . "\n";
    echo "¿Tiene Transbank/WebPay?: " . ($hasTransbank ? 'SÍ' : 'NO') . "\n";
    echo "¿Tiene Fluid Checkout?: " . ($hasFluidCheckout ? 'SÍ' : 'NO') . "\n\n";

    // Extraer scripts de Transbank cargados
    preg_match_all('/src=["\']([^"\']*(?:transbank|webpay|tbk)[^"\']*)["\']/', $html, $tbkScripts);
    echo "=== SCRIPTS TRANSBANK EN PÁGINA ===\n";
    foreach ($tbkScripts[1] as $src) {
        echo "  {$src}\n";
    }

    // Extraer TODOS los scripts para ver el orden
    preg_match_all('/src=["\']([^"\']*\.js[^"\']*)["\']/', $html, $allScripts);
    echo "\n=== ORDEN DE CARGA DE SCRIPTS (checkout) ===\n";
    $count = 0;
    foreach ($allScripts[1] as $src) {
        echo "  [{$count}] " . (strlen($src) > 80 ? '...' . substr($src, -70) : $src) . "\n";
        $count++;
        if ($count > 40) { echo "  ... (truncado)\n"; break; }
    }

    // Buscar el nonce de WooCommerce en el HTML
    preg_match_all('/"woocommerce_params":\{[^}]+\}/', $html, $wcParams);
    if (!empty($wcParams[0])) {
        echo "\n=== WC PARAMS ===\n";
        echo $wcParams[0][0] . "\n";
    }

    // Buscar el nonce de update_order_review
    preg_match('/update_order_review_nonce["\s]*:["\s]*["\']([^"\']+)["\']/', $html, $nonceMatch);
    echo "\n=== NONCE update_order_review ===\n";
    echo ($nonceMatch[1] ?? 'NO ENCONTRADO') . "\n";

    // Buscar errores PHP en la página
    preg_match_all('/(?:Warning|Fatal error|Notice|Parse error).*/', $html, $phpErrors);
    echo "\n=== ERRORES PHP EN PÁGINA ===\n";
    if (!empty($phpErrors[0])) {
        foreach ($phpErrors[0] as $err) {
            echo "  " . htmlspecialchars_decode(strip_tags($err)) . "\n";
        }
    } else {
        echo "  (ninguno visible)\n";
    }

    // Verificar si hay productos en el carrito (requerido para checkout)
    if (strpos($html, 'cart-empty') !== false || strpos($html, 'woocommerce-cart-empty') !== false) {
        echo "\n⚠️  El carrito está vacío — el checkout redirige\n";
    }

    // Buscar el fragmento de métodos de pago
    $paymentPos = strpos($html, 'payment_method');
    if ($paymentPos !== false) {
        echo "\n=== MÉTODOS DE PAGO EN HTML (contexto) ===\n";
        echo substr($html, max(0, $paymentPos - 100), 400) . "\n";
    }
}

echo "\n✅ Análisis completado.\n";
