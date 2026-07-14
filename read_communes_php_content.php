<?php
/**
 * Read communes.php content.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$file = WP_PLUGIN_DIR . '/comunas-de-chile-para-woocommerce/data/communes.php';
if (file_exists($file)) {
    $lines = file($file);
    echo "communes.php content:\n";
    for ($i = 0; $i < 50 && $i < count($lines); $i++) {
        echo ($i + 1) . ": " . $lines[$i];
    }
} else {
    echo "File not found: $file\n";
}
