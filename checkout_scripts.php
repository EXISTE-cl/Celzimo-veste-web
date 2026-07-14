<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "=== CHECKOUT PAGE SCRIPTS ===\n";

// Asegurar sesión
if ( ! WC()->session->has_session() ) {
    WC()->session->set_customer_session_cookie(true);
}
// Vaciar y agregar producto
WC()->cart->empty_cart();
$products = wc_get_products(['limit' => 1, 'status' => 'publish']);
if (!empty($products)) {
    WC()->cart->add_to_cart($products[0]->get_id(), 1);
} else {
    die("No hay productos.");
}

ob_start();
// Configurar vars necesarias
global $wp, $wp_query;
$wp->query_vars['pagename'] = 'checkout';
$wp_query->is_page = true;
$wp_query->is_singular = true;
$wp_query->is_checkout = true;
define('WP_USE_THEMES', true);

// Imprimir scripts
wp_head();
wp_footer();
$html = ob_get_clean();

preg_match_all('/<script[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $html, $matches);

echo "Scripts encontrados:\n";
foreach ($matches[1] as $src) {
    if (strpos($src, 'transbank') !== false || strpos($src, 'tbk') !== false || strpos($src, 'webpay') !== false || strpos($src, 'fluid') !== false || strpos($src, 'checkout') !== false) {
        echo "  " . basename($src) . "\n";
    }
}

// Limpiar carrito
WC()->cart->empty_cart();

echo "\n✅ Completado.\n";
