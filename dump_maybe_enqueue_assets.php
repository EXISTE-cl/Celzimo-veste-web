<?php
/**
 * Dump maybe_enqueue_assets in checkout-steps.php.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$file = WP_PLUGIN_DIR . '/fluid-checkout/inc/checkout-steps.php';
if (file_exists($file)) {
    $content = file_get_contents($file);
    $pos = strpos($content, 'function maybe_enqueue_assets');
    if ($pos !== false) {
        echo "FOUND METHOD:\n";
        echo substr($content, $pos, 400) . "\n";
    } else {
        echo "Method not found.\n";
    }
} else {
    echo "File not found: $file\n";
}
