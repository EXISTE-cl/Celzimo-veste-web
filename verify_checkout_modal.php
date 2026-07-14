<?php
/**
 * Verify checkout modal configuration.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$results = [];

// 1. Check active plugins
$active_plugins = get_option('active_plugins', []);
$required_plugins = [
    'fluid-checkout/fluid-checkout.php'      => 'Fluid Checkout Lite',
    'comunas-de-chile-para-woocommerce/comunas-de-chile-para-woocommerce.php' => 'Comunas de Chile',
    'popup-maker/popup-maker.php'            => 'Popup Maker',
    'woo-checkout-field-editor-pro/woo-checkout-field-editor-pro.php' => 'ThemeHigh Checkout Fields',
];

$results['plugins'] = [];
foreach ($required_plugins as $plugin_file => $plugin_name) {
    $active = in_array($plugin_file, $active_plugins);
    $results['plugins'][$plugin_name] = $active ? '✅ Activo' : '❌ INACTIVO';
}

// 2. Check WooCommerce currency
$results['currency'] = get_woocommerce_currency() . ' — ' . get_woocommerce_currency_symbol();

// 3. Check WooCommerce country
$results['country'] = WC()->countries->get_base_country();

// 4. Check active theme
$theme = wp_get_theme();
$results['theme'] = $theme->get('Name') . ' v' . $theme->get('Version');

// 5. Check Popup Maker popups
if (class_exists('PUM')) {
    $popups = get_posts([
        'post_type'   => 'popup',
        'numberposts' => 5,
        'post_status' => 'publish',
    ]);
    $results['popups'] = [];
    foreach ($popups as $popup) {
        $results['popups'][] = 'ID ' . $popup->ID . ': ' . $popup->post_title;
    }
} else {
    $results['popups'] = ['Popup Maker no disponible'];
}

// 6. Check ThemeHigh custom fields
$th_fields = get_option('thwcfe_checkout_fields', null);
if ($th_fields) {
    $sections = array_keys($th_fields);
    $results['checkout_fields_sections'] = $sections;
    $custom_fields = [];
    foreach ($th_fields as $section => $fields) {
        foreach ($fields as $fkey => $fval) {
            if (strpos($fkey, 'billing_') === false && strpos($fkey, 'shipping_') === false) {
                $custom_fields[] = $fkey . ' (' . $section . ')';
            }
        }
    }
    $results['custom_fields'] = $custom_fields ?: ['Ninguno personalizado encontrado'];
} else {
    $results['checkout_fields_sections'] = [];
    $results['custom_fields'] = ['ThemeHigh fields option no encontrada'];
}

// 7. Check Fluid Checkout settings
$fc_settings = [
    'fc_hide_optional_fields_link'     => get_option('fc_hide_optional_fields_link'),
    'fc_enable_checkout_layout'        => get_option('fc_enable_checkout_layout'),
    'fc_enable_coupon_code_section'    => get_option('fc_enable_coupon_code_section'),
];
$results['fluid_checkout_settings'] = $fc_settings;

// 8. Check checkout page URL
$results['checkout_url'] = wc_get_checkout_url();
$results['cart_url']     = wc_get_cart_url();

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
