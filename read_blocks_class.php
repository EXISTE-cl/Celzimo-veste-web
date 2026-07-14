<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$plugin_dir = WP_CONTENT_DIR . '/plugins/transbank-webpay-plus-rest/';

// Leer el archivo de Blocks
echo "=== WCGatewayTransbankBlocks.php ===\n";
$blocks_file = $plugin_dir . 'src/Blocks/WCGatewayTransbankBlocks.php';
if (file_exists($blocks_file)) {
    echo file_get_contents($blocks_file);
} else {
    // Buscar
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($plugin_dir));
    foreach ($it as $f) {
        if ($f->isFile() && stripos($f->getFilename(), 'block') !== false && $f->getExtension() === 'php') {
            echo "\n--- " . $f->getPathname() . " ---\n";
            echo file_get_contents($f->getPathname());
        }
    }
}

echo "\n\n=== LEER WCGatewayTransbankWebpayBlocks ===\n";
$webpay_blocks = $plugin_dir . 'src/Blocks/WCGatewayTransbankWebpayBlocks.php';
if (file_exists($webpay_blocks)) {
    echo file_get_contents($webpay_blocks);
}
