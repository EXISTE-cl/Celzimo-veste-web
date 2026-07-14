<?php
/**
 * Dump is_checkout_page_or_fragment inside checkout-steps.php.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$file = WP_PLUGIN_DIR . '/fluid-checkout/inc/checkout-steps.php';
if (file_exists($file)) {
    $content = file_get_contents($file);
    $pos = strpos($content, 'function is_checkout_page_or_fragment');
    if ($pos !== false) {
        echo "FOUND METHOD:\n";
        echo substr($content, $pos, 800) . "\n";
    } else {
        echo "Method not found.\n";
    }
} else {
    echo "File not found: $file\n";
}
