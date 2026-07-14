<?php
/**
 * Find files with fc-progress-bar.
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
    if (strpos($content, 'fc-progress-bar') !== false) {
        $rel_path = str_replace(WP_PLUGIN_DIR, '', $file->getPathname());
        echo "Found in: $rel_path\n";
    }
}
