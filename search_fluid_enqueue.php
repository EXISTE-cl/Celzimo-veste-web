<?php
/**
 * Search Fluid Checkout enqueue functions.
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
    if (strpos($content, 'enqueue_') !== false && (strpos($content, 'style') !== false || strpos($content, 'script') !== false)) {
        $rel_path = str_replace(WP_PLUGIN_DIR, '', $file->getPathname());
        // Find lines with enqueue or style/script
        $lines = explode("\n", $content);
        foreach ($lines as $i => $line) {
            if (strpos($line, 'wp_enqueue_') !== false || strpos($line, 'enqueue_assets') !== false) {
                echo "$rel_path Line " . ($i+1) . ": " . trim($line) . "\n";
            }
        }
    }
}
