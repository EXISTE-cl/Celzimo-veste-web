<?php
/**
 * List all template files in Fluid Checkout.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$dir = WP_PLUGIN_DIR . '/fluid-checkout/templates';

function list_files_in_dir($dir) {
    if (!is_dir($dir)) {
        echo "Directory not found: $dir\n";
        return;
    }
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $file) {
        if ($file->isDir()) continue;
        $rel_path = str_replace(WP_PLUGIN_DIR, '', $file->getPathname());
        echo "- $rel_path\n";
    }
}

list_files_in_dir($dir);
