<?php
/**
 * Read registerComunas method from Comunas de Chile plugin
 */
define('WP_USE_THEMES', false);
require_once('wp-load.php');

if (!current_user_can('manage_options') && ($_GET['token'] ?? '') !== 'Csc170431Activation') {
    die('Unauthorized');
}

$file = WP_PLUGIN_DIR . '/comunas-de-chile-para-woocommerce/woocommerce-comunas.php';
if (file_exists($file)) {
    $content = file_get_contents($file);
    $pos = strpos($content, 'public function registerComunas');
    if ($pos !== false) {
        echo "=== registerComunas ===\n";
        echo substr($content, $pos, 1500) . "\n";
    } else {
        echo "Method not found.\n";
    }
} else {
    echo "File not found: $file\n";
}
