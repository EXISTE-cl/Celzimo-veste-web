<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$plugin_dir = WP_CONTENT_DIR . '/plugins/transbank-webpay-plus-rest/';

// Leer la función transbankRestEnqueueCheckoutNoticeHandler desde webpay-rest.php
$main = file_get_contents($plugin_dir . 'webpay-rest.php');

// Buscar la función
preg_match('/function transbankRestEnqueueCheckoutNoticeHandler.*?(?=\nfunction |\Z)/s', $main, $match);
echo "=== transbankRestEnqueueCheckoutNoticeHandler ===\n";
echo ($match[0] ?? "NO ENCONTRADA EN webpay-rest.php\n");

// Buscar en todos los PHP files
echo "\n\n=== BUSCANDO EN TODOS LOS PHP ===\n";
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($plugin_dir));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        if (strpos($content, 'transbankRestEnqueueCheckoutNoticeHandler') !== false || 
            strpos($content, 'checkout-notice') !== false ||
            strpos($content, 'transbank_checkout') !== false) {
            $relpath = str_replace($plugin_dir, '', $file->getPathname());
            echo "\nEncontrado en: {$relpath}\n";
            // Mostrar sección relevante
            $lines = file($file->getPathname());
            foreach ($lines as $i => $line) {
                if (stripos($line, 'checkout') !== false || stripos($line, 'enqueue') !== false || stripos($line, 'script') !== false) {
                    echo ($i+1) . ": " . $line;
                }
            }
        }
    }
}

// Listar archivos JS del plugin
echo "\n\n=== ARCHIVOS JS DEL PLUGIN ===\n";
$js_dir = $plugin_dir . 'js/';
if (is_dir($js_dir)) {
    foreach (scandir($js_dir) as $f) {
        if (pathinfo($f, PATHINFO_EXTENSION) === 'js') {
            $size = filesize($js_dir . $f);
            echo "  {$f} ({$size} bytes)\n";
            // Mostrar primeras 30 líneas de cada JS
            $content = file_get_contents($js_dir . $f);
            echo "  PREVIEW: " . substr($content, 0, 300) . "\n\n";
        }
    }
}
