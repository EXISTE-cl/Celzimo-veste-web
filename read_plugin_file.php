<?php
/**
 * Read the main plugin file to find create_function() usage.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$file = WP_PLUGIN_DIR . '/woocommerce-checkout-field-editor/woocommerce-checkout-field-editor.php';
if (file_exists($file)) {
    $lines = file($file);
    echo "FILE CONTENTS (Lines 1 to 50):\n";
    for ($i = 0; $i < min(50, count($lines)); $i++) {
        echo ($i + 1) . ": " . $lines[$i];
    }
} else {
    echo "File not found: $file\n";
}
