<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

// Asegurar que hay algo en el carro para ver el form real
if (WC()->cart->is_empty()) {
    WC()->cart->add_to_cart(45); // Añadir Jeans
}

// Renderizar la página de checkout y buscar los inputs
ob_start();
include(get_template_directory() . '/page.php'); // O forzar renderizado de shortcode
$html = ob_get_clean();

// Si page.php no saca el shortcode, procesarlo directamente
if (strpos($html, '<form') === false) {
    ob_start();
    echo do_shortcode('[woocommerce_checkout]');
    $html = ob_get_clean();
}

// Buscar todos los tags <select> e <input> en el HTML
$dom = new DOMDocument();
@$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
$xp = new DOMXPath($dom);

echo "=== CAMPOS ENCONTRADOS EN CHECKOUT ===\n";
$nodes = $xp->query('//input | //select | //textarea');
foreach ($nodes as $node) {
    $name = $node->getAttribute('name');
    $id = $node->getAttribute('id');
    $type = $node->getAttribute('type');
    if ($name && (strpos($name, 'city') !== false || strpos($name, 'comuna') !== false || strpos($name, 'state') !== false || strpos($name, 'address') !== false)) {
        echo "Tag: " . $node->nodeName . " | Name: $name | ID: $id | Type: $type | Value: " . $node->getAttribute('value') . "\n";
    }
}
