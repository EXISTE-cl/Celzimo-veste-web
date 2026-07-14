<?php
/**
 * Read Comunas de Chile plugin file.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$file = WP_PLUGIN_DIR . '/comunas-de-chile-para-woocommerce/woocommerce-comunas.php';
if (file_exists($file)) {
    $lines = file($file);
    echo "FILE LINES 1 to 150:\n";
    for ($i = 0; $i < 150 && $i < count($lines); $i++) {
        echo ($i + 1) . ": " . $lines[$i];
    }
} else {
    echo "File not found: $file\n";
}
