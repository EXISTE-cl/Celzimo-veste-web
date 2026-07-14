<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$mp = get_option('woocommerce_woo-mercado-pago-basic_settings', []);
echo "=== MERCADO PAGO BASIC SETTINGS ===\n";
if (empty($mp)) {
    echo "Configuración vacía.\n";
} else {
    foreach ($mp as $key => $value) {
        if (strpos($key, 'token') !== false || strpos($key, 'key') !== false || strpos($key, 'secret') !== false) {
            echo "$key: " . (empty($value) ? 'VACÍO' : 'LLENADO (' . strlen($value) . ' caracteres)') . "\n";
        } else {
            echo "$key: " . (is_array($value) ? print_r($value, true) : $value) . "\n";
        }
    }
}
