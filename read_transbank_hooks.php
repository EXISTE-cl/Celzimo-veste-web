<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "=== HOOKS DE TRANSBANK EN WOOCOMMERCE ===\n\n";

// Leer el archivo principal del plugin
$plugin_dir = WP_CONTENT_DIR . '/plugins/transbank-webpay-plus-rest/';
$files = ['webpay-rest.php', 'plugin.php', 'src/'];

echo "Archivos del plugin:\n";
$all_files = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($plugin_dir));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $all_files[] = $file->getPathname();
        echo "  " . str_replace($plugin_dir, '', $file->getPathname()) . "\n";
    }
}

// Leer el archivo principal
echo "\n=== CONTENIDO webpay-rest.php (primeras 100 líneas) ===\n";
$main_file = $plugin_dir . 'webpay-rest.php';
if (file_exists($main_file)) {
    $lines = file($main_file);
    foreach (array_slice($lines, 0, 100) as $i => $line) {
        echo ($i+1) . ": " . $line;
    }
}

// Buscar add_action en todos los archivos
echo "\n\n=== TODOS LOS add_action/add_filter DEL PLUGIN ===\n";
foreach ($all_files as $filepath) {
    $content = file_get_contents($filepath);
    $relpath = str_replace($plugin_dir, '', $filepath);
    
    preg_match_all('/add_(action|filter)\s*\(\s*[\'"]([^\'"]+)[\'"]/', $content, $matches);
    if (!empty($matches[2])) {
        foreach ($matches[2] as $hook) {
            echo "  [{$relpath}] {$matches[1][array_search($hook, $matches[2])]}: {$hook}\n";
        }
    }
}

echo "\n✅ Listo.\n";
