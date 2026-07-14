<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$dir = WP_CONTENT_DIR . '/plugins/fluid-checkout/';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($it as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        if (strpos($content, 'wc-ajax') !== false || strpos($content, 'wc_ajax') !== false) {
            echo "Match in: " . str_replace($dir, '', $file->getPathname()) . "\n";
            // Print matching lines
            $lines = explode("\n", $content);
            foreach ($lines as $i => $line) {
                if (strpos($line, 'wc-ajax') !== false || strpos($line, 'wc_ajax') !== false) {
                    echo "  Line " . ($i + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
}
