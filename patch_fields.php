<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

// Fix WooCommerce billing city (Comuna) requirement globally for Chile if it's hidden
add_filter('woocommerce_billing_fields', function($fields) {
    if (isset($fields['billing_city'])) {
        $fields['billing_city']['required'] = false;
    }
    return $fields;
}, 9999);

add_filter('woocommerce_shipping_fields', function($fields) {
    if (isset($fields['shipping_city'])) {
        $fields['shipping_city']['required'] = false;
    }
    return $fields;
}, 9999);

add_filter('woocommerce_default_address_fields', function($fields) {
    if (isset($fields['city'])) {
        $fields['city']['required'] = false;
    }
    return $fields;
}, 9999);

echo "Campos requeridos parcheados temporalmente. Si quieres aplicarlo permanentemente, debemos poner esto en functions.php\n";
