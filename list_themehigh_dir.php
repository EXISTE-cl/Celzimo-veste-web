<?php
/**
 * List files in the unzipped ThemeHigh directory.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$dir = WP_PLUGIN_DIR . '/woocommerce-checkout-field-editor';
if (is_dir($dir)) {
    echo "Directory exists: $dir\n";
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "- $file\n";
        }
    }
} else {
    echo "Directory does not exist: $dir\n";
}
