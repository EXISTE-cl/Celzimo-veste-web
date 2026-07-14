<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$tbk = get_option('woocommerce_transbank_webpay_plus_rest_settings', []);
if (is_array($tbk)) {
    $tbk['enabled'] = 'yes';
    update_option('woocommerce_transbank_webpay_plus_rest_settings', $tbk);
    echo "Transbank Webpay Plus: ACTIVADO\n";
}
