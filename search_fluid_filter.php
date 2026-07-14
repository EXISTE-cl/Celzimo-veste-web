<?php
/**
 * Search Fluid Checkout fc_is_checkout_page_or_fragment filter.
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
    if (strpos($content, 'fc_is_checkout_page_or_fragment') !== false) {
        $rel_path = str_replace(WP_PLUGIN_DIR, '', $file->getPathname());
        $lines = explode("\n", $content);
        foreach ($lines as $i => $line) {
            if (strpos($line, 'fc_is_checkout_page_or_fragment') !== false) {
                echo "$rel_path Line " . ($i+1) . ": " . trim($line) . "\n";
            }
        }
    }
}
