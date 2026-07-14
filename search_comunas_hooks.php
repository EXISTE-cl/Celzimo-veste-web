<?php
/**
 * Search hooks changing labels in Comunas de Chile.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$file = WP_PLUGIN_DIR . '/comunas-de-chile-para-woocommerce/woocommerce-comunas.php';
if (file_exists($file)) {
    $content = file_get_contents($file);
    $lines = explode("\n", $content);
    foreach ($lines as $i => $line) {
        if (strpos($line, 'filter') !== false || strpos($line, 'label') !== false || strpos($line, 'state') !== false || strpos($line, 'city') !== false) {
            echo "Line " . ($i+1) . ": " . trim($line) . "\n";
        }
    }
} else {
    echo "File not found: $file\n";
}
