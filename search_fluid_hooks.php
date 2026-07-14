<?php
/**
 * Search Fluid Checkout template hooks.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$dir = WP_PLUGIN_DIR . '/fluid-checkout';

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($it as $file) {
    if ($file->isDir()) continue;
    $content = file_get_contents($file->getPathname());
    
    // Check if it hooks templates only on checkout
    if (strpos($content, 'is_checkout') !== false) {
        $rel_path = str_replace(WP_PLUGIN_DIR, '', $file->getPathname());
        $lines = explode("\n", $content);
        foreach ($lines as $i => $line) {
            if (strpos($line, 'is_checkout') !== false) {
                echo "$rel_path Line " . ($i+1) . ": " . trim($line) . "\n";
            }
        }
    }
}
