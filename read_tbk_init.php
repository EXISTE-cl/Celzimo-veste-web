<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$plugin_dir = WP_CONTENT_DIR . '/plugins/transbank-webpay-plus-rest/';
$main = file_get_contents($plugin_dir . 'webpay-rest.php');

// Leer transbank_rest_check_cancelled_checkout
preg_match('/function transbank_rest_check_cancelled_checkout.*?(?=\n\/\*\*|\nfunction |\Z)/s', $main, $match);
echo "=== transbank_rest_check_cancelled_checkout ===\n";
echo ($match[0] ?? "No encontrada\n") . "\n";

// Leer woocommerceTransbankInit
preg_match('/function woocommerceTransbankInit.*?(?=\n\/\*\*|\nfunction |\Z)/s', $main, $match2);
echo "\n=== woocommerceTransbankInit ===\n";
echo (isset($match2[0]) ? substr($match2[0], 0, 2000) : "No encontrada") . "\n";

// Buscar front-notice-handler.js en build/
$build_dirs = ['build/', 'dist/', 'assets/', 'public/'];
foreach ($build_dirs as $d) {
    $full = $plugin_dir . $d;
    if (is_dir($full)) {
        echo "\nDirectorio encontrado: {$d}\n";
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($full));
        foreach ($it as $f) {
            if ($f->isFile() && (stripos($f->getFilename(), 'notice') !== false || stripos($f->getFilename(), 'front') !== false)) {
                echo "  " . str_replace($plugin_dir, '', $f->getPathname()) . " (" . $f->getSize() . " bytes)\n";
                // Mostrar contenido
                $jsContent = file_get_contents($f->getPathname());
                echo "  CONTENIDO: " . substr($jsContent, 0, 800) . "\n\n";
            }
        }
    }
}
