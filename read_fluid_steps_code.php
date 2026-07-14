<?php
/**
 * Inspect checkout-steps.php class markup.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$file = WP_PLUGIN_DIR . '/fluid-checkout/inc/checkout-steps.php';
if (file_exists($file)) {
    $content = file_get_contents($file);
    // Find output of progress bar or steps header
    if (preg_match_all('/class="fc-[^"]+"/', $content, $matches)) {
        echo "Found classes:\n";
        print_r(array_unique($matches[0]));
    }
    
    // Find functions related to progress bar or header
    echo "\nSearching for progress bar rendering methods:\n";
    $lines = file($file);
    foreach ($lines as $i => $line) {
        if (strpos($line, 'progress') !== false || strpos($line, 'step') !== false || strpos($line, 'header') !== false) {
            echo "Line " . ($i+1) . ": " . trim($line) . "\n";
        }
    }
} else {
    echo "File not found: $file\n";
}
