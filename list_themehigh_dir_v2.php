<?php
/**
 * List files in the unzipped ThemeHigh directory v2.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$dir = WP_PLUGIN_DIR . '/woo-checkout-field-editor-pro';
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
    // Let's also check if there is a similar folder
    $plugins = scandir(WP_PLUGIN_DIR);
    echo "Other plugin folders:\n";
    foreach ($plugins as $plugin) {
        if ($plugin !== '.' && $plugin !== '..') {
            echo " - $plugin\n";
        }
    }
}
