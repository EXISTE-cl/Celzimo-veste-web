<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

// Forzar adición al carro
if (WC()->cart->is_empty()) {
    WC()->cart->add_to_cart(45);
}

// Emular sesión
if (null === WC()->session) {
    WC()->session = new WC_Session_Handler();
    WC()->session->init();
}

// Configurar request de checkout
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/finalizar-compra/';

// Renderizar shortcode directamente
$html = do_shortcode('[woocommerce_checkout]');
file_put_contents(WP_CONTENT_DIR . '/checkout_page_dump.html', $html);
echo "HTML guardado en /wp-content/checkout_page_dump.html (" . strlen($html) . " bytes)\n";
