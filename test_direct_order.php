<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "=== TEST CREACIÓN DE ORDEN DIRECTA ===\n";

try {
    // 1. Crear una orden de prueba programáticamente
    $order = wc_create_order();
    
    // Obtener un producto
    $products = wc_get_products(['limit' => 1, 'status' => 'publish']);
    if (empty($products)) {
        die("No hay productos para probar.");
    }
    $product = $products[0];
    
    $order->add_product($product, 1);
    
    $address = array(
        'first_name' => 'Test',
        'last_name'  => 'Test',
        'company'    => '',
        'email'      => 'test@test.com',
        'phone'      => '56912345678',
        'address_1'  => 'Test Address 123',
        'address_2'  => '',
        'city'       => 'Santiago',
        'state'      => 'RM',
        'postcode'   => '8320000',
        'country'    => 'CL'
    );
    
    $order->set_address($address, 'billing');
    $order->set_address($address, 'shipping');
    
    $order->set_payment_method('transbank_webpay_plus_rest');
    $order->set_payment_method_title('Webpay Plus');
    
    $order->calculate_totals();
    $order->save();
    
    echo "Orden creada: ID {$order->get_id()} Total: {$order->get_total()}\n";
    
    // 2. Procesar el pago directamente invocando al gateway
    $gateways = WC()->payment_gateways->payment_gateways();
    if (!isset($gateways['transbank_webpay_plus_rest'])) {
        die("El gateway de WebPay no está cargado.");
    }
    $gateway = $gateways['transbank_webpay_plus_rest'];
    
    echo "Invocando process_payment()...\n";
    $result = $gateway->process_payment($order->get_id());
    
    print_r($result);
    
    // Limpiar
    wp_delete_post($order->get_id(), true);
    
} catch (Exception $e) {
    echo "EXCEPCIÓN: " . $e->getMessage() . "\n";
}

echo "\n✅ Test completado.\n";
