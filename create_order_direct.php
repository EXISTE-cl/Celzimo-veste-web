<?php
/**
 * Programmatic order creation using wc_create_order() API.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

echo "==================================================\n";
echo "           DIRECT PROGRAMMATIC TEST ORDER          \n";
echo "==================================================\n\n";

// 1. Get a product
$products = wc_get_products(array('limit' => 1));
if (empty($products)) {
    $product = new WC_Product_Simple();
    $product->set_name("Producto de Prueba");
    $product->set_regular_price("15000"); // 15,000 CLP
    $product->set_status("publish");
    $product_id = $product->save();
    echo "Created temporary product ID: $product_id\n";
} else {
    $product_id = $products[0]->get_id();
    echo "Found existing product ID: $product_id\n";
}

// 2. Create the order
$order = wc_create_order();
$order->add_product(wc_get_product($product_id), 1);

// 3. Set address details
$address = array(
    'first_name' => 'Cristóbal Test',
    'last_name'  => '', // disabled field
    'company'    => '',
    'email'      => 'cristobal.test@example.com',
    'phone'      => '+56 9 8765 4321',
    'address_1'  => 'Av. Providencia 1234, Depto 41',
    'address_2'  => '',
    'city'       => 'Providencia', // Comuna
    'state'      => 'RM', // Región Metropolitana
    'postcode'   => '', // no zip code
    'country'    => 'CL'
);

$order->set_address($address, 'billing');
$order->set_address($address, 'shipping');

// 4. Save custom checkout field "Tipo de Documento"
$order->update_meta_data('_shipping_tipo_documento', 'boleta');
$order->update_meta_data('_billing_tipo_documento', 'boleta');

// Calculate totals and save
$order->calculate_totals();
$order->set_payment_method('cod');
$order->set_status('processing', 'Pedido de prueba creado por script de verificación.');
$order_id = $order->save();

if ($order_id) {
    echo "SUCCESSFUL_TEST_ORDER_CREATED\n";
    echo "Order ID: " . $order->get_id() . "\n";
    echo "Status: " . $order->get_status() . "\n";
    echo "Total: " . $order->get_total() . " " . $order->get_currency() . "\n";
    echo "Name: " . $order->get_shipping_first_name() . "\n";
    echo "Phone: " . $order->get_billing_phone() . "\n";
    echo "Comuna: " . $order->get_shipping_city() . "\n";
    echo "Región: " . $order->get_shipping_state() . "\n";
    echo "Dirección: " . $order->get_shipping_address_1() . "\n";
    echo "Tipo de Documento: " . $order->get_meta('_shipping_tipo_documento') . "\n";
} else {
    echo "FAIL: Failed to save test order.\n";
}
echo "==================================================\n";
