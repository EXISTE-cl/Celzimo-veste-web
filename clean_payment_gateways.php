<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "Configurando métodos de pago...\n";

// Deshabilitar Transbank
$tbk = get_option('woocommerce_transbank_webpay_plus_rest_settings', []);
if (is_array($tbk)) {
    $tbk['enabled'] = 'no';
    update_option('woocommerce_transbank_webpay_plus_rest_settings', $tbk);
    echo "Transbank Webpay Plus: DESACTIVADO\n";
}

// Deshabilitar Contra Reembolso (COD)
$cod = get_option('woocommerce_cod_settings', []);
if (is_array($cod)) {
    $cod['enabled'] = 'no';
    update_option('woocommerce_cod_settings', $cod);
    echo "Contra reembolso (COD): DESACTIVADO\n";
}

// Asegurar que Mercado Pago está habilitado
$mp = get_option('woocommerce_woo-mercado-pago-basic_settings', []);
if (is_array($mp)) {
    $mp['enabled'] = 'yes';
    update_option('woocommerce_woo-mercado-pago-basic_settings', $mp);
    echo "Mercado Pago: ACTIVADO\n";
}

echo "\n✅ Configuración completada exitosamente.\n";
