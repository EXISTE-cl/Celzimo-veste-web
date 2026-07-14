<?php
define('WP_USE_THEMES', false);
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

// Forzar inicio de sesión de WooCommerce
if ( null === WC()->session ) {
    $session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
    WC()->session  = new $session_class();
    WC()->session->init();
}

if (null === WC()->customer) {
    WC()->customer = new WC_Customer( get_current_user_id(), true );
}

if (WC()->cart->is_empty()) {
    WC()->cart->add_to_cart(45); // Añadir Jeans
}

// Emular entorno de frontend
wp();

ob_start();
// Renderizar el checkout usando el shortcode directamente
echo do_shortcode('[woocommerce_checkout]');
$html = ob_get_clean();

if (empty($html)) {
    echo "ERROR: El shortcode retornó vacío.\n";
    exit;
}

$dom = new DOMDocument();
@$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
$xp = new DOMXPath($dom);

echo "=== CAMPOS ENCONTRADOS EN CHECKOUT ===\n";
$nodes = $xp->query('//input | //select | //textarea');
foreach ($nodes as $node) {
    $name = $node->getAttribute('name');
    $id = $node->getAttribute('id');
    $type = $node->getAttribute('type');
    $placeholder = $node->getAttribute('placeholder');
    $class = $node->getAttribute('class');
    
    echo "Tag: " . $node->nodeName . " | Name: $name | ID: $id | Type: $type | Class: $class | Placeholder: $placeholder\n";
}
