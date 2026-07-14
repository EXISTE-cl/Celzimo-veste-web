<?php
/**
 * Render the full checkout HTML with all plugins active.
 * Simulates visiting the checkout page.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

// Add a product to cart to make checkout accessible
WC()->session->set_customer_session_cookie(true);
WC()->cart->empty_cart();

// Get first available product
$products = wc_get_products(['limit' => 1, 'status' => 'publish']);
if (!empty($products)) {
    $product = $products[0];
    WC()->cart->add_to_cart($product->get_id(), 1);
}

// Check cart state
$cart_items = WC()->cart->get_cart();
echo '<p>Items en carrito: ' . count($cart_items) . '</p>';

// Check all checkout fields that would appear
$checkout = WC()->checkout();
$fields = $checkout->get_checkout_fields();

echo '<h2>Campos de Envío (Shipping)</h2><pre>';
foreach ($fields['shipping'] as $key => $field) {
    $enabled = isset($field['enabled']) ? ($field['enabled'] ? 'VISIBLE' : 'hidden') : 'VISIBLE';
    echo $key . ' [' . ($field['type'] ?? 'text') . '] label="' . ($field['label'] ?? '') . '" required=' . (!empty($field['required']) ? 'yes' : 'no') . ' ' . $enabled . "\n";
}
echo '</pre>';

echo '<h2>Campos de Facturación (Billing)</h2><pre>';
foreach ($fields['billing'] as $key => $field) {
    $enabled = isset($field['enabled']) ? ($field['enabled'] ? 'VISIBLE' : 'hidden') : 'VISIBLE';
    echo $key . ' [' . ($field['type'] ?? 'text') . '] label="' . ($field['label'] ?? '') . '" required=' . (!empty($field['required']) ? 'yes' : 'no') . ' ' . $enabled . "\n";
}
echo '</pre>';

// Check popup settings
$popups = get_posts(['post_type' => 'popup', 'numberposts' => 3, 'post_status' => 'publish']);
echo '<h2>Popups Activos</h2><pre>';
foreach ($popups as $popup) {
    $settings = get_post_meta($popup->ID, '_pum_settings', true);
    echo 'ID ' . $popup->ID . ': ' . $popup->post_title . "\n";
    if ($settings) {
        echo '  Size: ' . ($settings['size'] ?? '?') . ', Theme: ' . ($settings['theme_id'] ?? '?') . "\n";
        echo '  Trigger: ' . ($settings['trigger'] ?? '?') . "\n";
    }
}
echo '</pre>';
