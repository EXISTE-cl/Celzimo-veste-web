<?php
/**
 * Run a programmatic test order to verify checkout processing.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

echo "==================================================\n";
echo "           PROGRAMMATIC TEST ORDER                 \n";
echo "==================================================\n\n";

// 1. Get or create a product
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

// 2. Set up WooCommerce session and cart
if (!WC()->session) {
    WC()->session = new WC_Session_Handler();
    WC()->session->init();
}
if (!WC()->customer) {
    WC()->customer = new WC_Customer(get_current_user_id(), true);
}
if (!WC()->cart) {
    WC()->cart = new WC_Cart();
}

// Add to cart
WC()->cart->empty_cart();
WC()->cart->add_to_cart($product_id, 1);
echo "Product added to cart. Items in cart: " . WC()->cart->get_cart_contents_count() . "\n";

// 3. Construct checkout data (POST simulation)
$_POST = array(
    'shipping_first_name' => 'Cristóbal Test',
    'shipping_last_name' => '', // disabled
    'shipping_country' => 'CL',
    'shipping_address_1' => 'Av. Providencia 1234, Depto 41',
    'shipping_city' => 'Providencia', // Comuna
    'shipping_state' => 'RM', // Región Metropolitana
    'shipping_phone' => '+56987654321',
    'shipping_tipo_documento' => 'boleta',
    
    // Billing fields (same as shipping)
    'billing_first_name' => 'Cristóbal Test',
    'billing_last_name' => '',
    'billing_country' => 'CL',
    'billing_address_1' => 'Av. Providencia 1234, Depto 41',
    'billing_city' => 'Providencia',
    'billing_state' => 'RM',
    'billing_phone' => '+56987654321',
    'billing_tipo_documento' => 'boleta',
    'ship_to_different_address' => '0',
    
    'payment_method' => 'cod', // Cash on delivery
    'terms' => '1',
    'woocommerce-process-checkout-nonce' => wp_create_nonce('woocommerce-process_checkout')
);

// 4. Enable Cash on Delivery gateway
$gateways = WC()->payment_gateways->payment_gateways();
if (isset($gateways['cod'])) {
    $gateways['cod']->enabled = 'yes';
    update_option('woocommerce_cod_settings', array('enabled' => 'yes'));
    echo "Cash on Delivery gateway enabled.\n";
}

// 5. Run WooCommerce checkout processing
try {
    wc_clear_notices();
    
    // We suppress the wp_redirect exit behavior of WooCommerce checkout
    add_filter('wp_redirect', function($location) {
        echo "WooCommerce attempted redirect to: $location\n";
        return false; // Prevent redirection to stop script
    }, 10, 1);

    WC()->checkout()->process_checkout();
    
    // Fetch last created order
    $orders = wc_get_orders(array('limit' => 1, 'orderby' => 'date', 'order' => 'DESC'));
    if (!empty($orders)) {
        $order = $orders[0];
        echo "\nSUCCESSFUL_TEST_ORDER_CREATED\n";
        echo "Order ID: " . $order->get_id() . "\n";
        echo "Status: " . $order->get_status() . "\n";
        echo "Total: " . $order->get_total() . " " . $order->get_currency() . "\n";
        echo "Name: " . $order->get_shipping_first_name() . "\n";
        echo "Phone: " . $order->get_meta('_shipping_phone') . "\n";
        echo "Comuna: " . $order->get_shipping_city() . "\n";
        echo "Región: " . $order->get_shipping_state() . "\n";
        echo "Dirección: " . $order->get_shipping_address_1() . "\n";
        echo "Tipo de Documento: " . $order->get_meta('_shipping_tipo_documento') . "\n";
    } else {
        echo "FAIL: No orders found in system.\n";
        print_r(wc_get_notices('error'));
    }
} catch (Exception $e) {
    echo "Exception occurred: " . $e->getMessage() . "\n";
}
echo "==================================================\n";
