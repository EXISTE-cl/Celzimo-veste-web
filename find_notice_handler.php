<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$plugin_dir = WP_CONTENT_DIR . '/plugins/transbank-webpay-plus-rest/';

echo "=== BUSCANDO front-notice-handler.js ===\n";
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($plugin_dir));
foreach ($iterator as $file) {
    if ($file->isFile() && stripos($file->getFilename(), 'notice') !== false) {
        echo "\nENcontrado: " . str_replace($plugin_dir, '', $file->getPathname()) . "\n";
        $content = file_get_contents($file->getPathname());
        echo "CONTENIDO COMPLETO:\n";
        echo $content . "\n";
    }
}

// También buscar funciones front
echo "\n\n=== FUNCIÓN tbkFrontAssetsBaseDir ===\n";
$main = file_get_contents($plugin_dir . 'webpay-rest.php');
preg_match('/function tbkFrontAssetsBase.*?(?=\nfunction |\Z)/s', $main, $m);
echo ($m[0] ?? "No encontrada\n");

// Mostrar todas las funciones tbkFront*
preg_match_all('/function (tbkFront[^\(]+)\([^\)]*\)\s*\{([^}]+(?:\{[^}]*\}[^}]*)*)\}/s', $main, $funcs);
echo "\n=== FUNCIONES tbkFront* ===\n";
foreach ($funcs[1] as $i => $fname) {
    echo "\n--- {$fname} ---\n";
    echo "function {$fname}(" . "..." . ") {\n" . $funcs[2][$i] . "}\n";
}
