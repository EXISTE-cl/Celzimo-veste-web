<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$plugin_dir = WP_CONTENT_DIR . '/plugins/transbank-webpay-plus-rest/';
$gateway_file = $plugin_dir . 'src/PaymentGateways/WC_Gateway_Transbank_Webpay_Plus_REST.php';

echo "=== WC_Gateway_Transbank_Webpay_Plus_REST ===\n\n";
if (file_exists($gateway_file)) {
    $content = file($gateway_file);
    // Mostrar las primeras 120 líneas (constructor + init)
    foreach (array_slice($content, 0, 120) as $i => $line) {
        echo ($i+1) . ": " . $line;
    }
} else {
    echo "Archivo no encontrado: {$gateway_file}\n";
    // Buscar
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($plugin_dir));
    foreach ($iterator as $file) {
        if (stripos($file->getFilename(), 'gateway') !== false || stripos($file->getFilename(), 'webpay_plus') !== false) {
            echo "Candidato: " . str_replace($plugin_dir, '', $file->getPathname()) . "\n";
        }
    }
}
