<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "=== TEST UPDATE ORDER REVIEW ===\n";

$_POST = [
    'security' => wp_create_nonce('update-order-review'),
    'payment_method' => 'woo-mercado-pago-basic',
    'country' => 'CL',
    'state' => 'RM',
    'city' => 'Maipú',
    'postcode' => '',
    'address' => 'Guinea 3499',
    'address_2' => '',
    's_country' => 'CL',
    's_state' => 'RM',
    's_city' => 'Maipú',
    's_postcode' => '',
    's_address' => 'Guinea 3499',
    's_address_2' => '',
    'has_full_address' => 'true',
    'post_data' => 'billing_first_name=Cristobal&billing_last_name=Pizarro&billing_email=cscspachile%40gmail.com&billing_phone=930659685&billing_country=CL&billing_address_1=Guinea+3499&billing_state=RM&billing_city=Maip%C3%BA&billing_rut_personal=176790385&payment_method=woo-mercado-pago-basic'
];

$_GET['wc-ajax'] = 'update_order_review';

define('DOING_AJAX', true);

// Execute the AJAX call
WC_AJAX::get_endpoint_data();
