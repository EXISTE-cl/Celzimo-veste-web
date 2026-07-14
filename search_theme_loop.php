<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$dir = get_template_directory();
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($it as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        if (strpos($content, 'update_checkout') !== false || strpos($content, 'update_order_review') !== false) {
            echo "Match in: " . str_replace($dir, '', $file->getPathname()) . "\n";
            $lines = explode("\n", $content);
            foreach ($lines as $i => $line) {
                if (strpos($line, 'update_checkout') !== false || strpos($line, 'update_order_review') !== false) {
                    echo "  Line " . ($i + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
}
