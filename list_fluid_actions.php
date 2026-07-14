<?php
/**
 * Find all do_action calls in Fluid Checkout.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$dir = WP_PLUGIN_DIR . '/fluid-checkout';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$actions = array();
foreach ($it as $file) {
    if ($file->isDir()) continue;
    if (pathinfo($file->getPathname(), PATHINFO_EXTENSION) !== 'php') continue;
    
    $content = file_get_contents($file->getPathname());
    if (preg_match_all('/do_action\(\s*\'([^\']+)\'/i', $content, $matches)) {
        foreach ($matches[1] as $action) {
            $actions[$action][] = str_replace(WP_PLUGIN_DIR, '', $file->getPathname());
        }
    }
}

ksort($actions);
foreach ($actions as $action => $files) {
    echo "Action: $action\n";
    echo "  Files: " . implode(', ', array_unique($files)) . "\n\n";
}
