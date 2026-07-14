<?php
/**
 * Search calls to is_checkout_page_or_fragment in Fluid Checkout.
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
    if (strpos($content, 'is_checkout_page_or_fragment') !== false) {
        $rel_path = str_replace(WP_PLUGIN_DIR, '', $file->getPathname());
        $lines = explode("\n", $content);
        foreach ($lines as $i => $line) {
            if (strpos($line, 'is_checkout_page_or_fragment') !== false && strpos($line, 'function ') === false) {
                echo "$rel_path Line " . ($i+1) . ": " . trim($line) . "\n";
            }
        }
    }
}
