<?php
/**
 * Dump how steps are outputted.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$file = WP_PLUGIN_DIR . '/fluid-checkout/inc/checkout-steps.php';
if (file_exists($file)) {
    $content = file_get_contents($file);
    // Find fc-progress-bar__step
    $pos = strpos($content, 'fc-progress-bar__step');
    if ($pos !== false) {
        echo "FOUND STEP MARKUP:\n";
        echo substr($content, $pos - 200, 800) . "\n";
    } else {
        echo "Not found.\n";
    }
} else {
    echo "File not found: $file\n";
}
