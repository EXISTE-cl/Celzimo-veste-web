<?php
/**
 * Read Fluid Checkout template files.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$dir = WP_PLUGIN_DIR . '/fluid-checkout/templates';

function find_templates_in_dir($dir) {
    if (!is_dir($dir)) {
        echo "Directory not found: $dir\n";
        return;
    }
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $file) {
        if ($file->isDir()) continue;
        if (strpos($file->getFilename(), 'progress') !== false || strpos($file->getFilename(), 'step') !== false) {
            $rel_path = str_replace(WP_PLUGIN_DIR, '', $file->getPathname());
            echo "File: $rel_path\n";
            $content = file_get_contents($file->getPathname());
            echo "--- CONTENT ---\n";
            echo esc_html($content) . "\n";
            echo "----------------------------------------\n\n";
        }
    }
}

find_templates_in_dir($dir);
