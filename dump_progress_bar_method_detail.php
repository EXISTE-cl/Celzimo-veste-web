<?php
/**
 * Detailed dump of output_checkout_progress_bar.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$file = WP_PLUGIN_DIR . '/fluid-checkout/inc/checkout-steps.php';
if (file_exists($file)) {
    $content = file_get_contents($file);
    $pos = strpos($content, 'function output_checkout_progress_bar');
    if ($pos !== false) {
        // Read 1800 characters to cover the whole function
        echo "METHOD CONTENT:\n";
        echo substr($content, $pos, 2200) . "\n";
    } else {
        echo "Method not found.\n";
    }
} else {
    echo "File not found: $file\n";
}
