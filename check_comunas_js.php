<?php
/**
 * Check if Comunas de Chile has assets or JS files.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$dir = WP_PLUGIN_DIR . '/comunas-de-chile-para-woocommerce';

function scan_dir_recursive($dir) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $file) {
        if ($file->isDir()) continue;
        $rel = str_replace(WP_PLUGIN_DIR, '', $file->getPathname());
        echo "- $rel\n";
    }
}

scan_dir_recursive($dir);
