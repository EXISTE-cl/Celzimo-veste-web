<?php
/**
 * Automated Verification Script.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

echo "==================================================\n";
echo "           AUTOMATED SETUP VERIFICATION            \n";
echo "==================================================\n\n";

// 1. Check Active Theme
$active_theme = wp_get_theme();
echo "ACTIVE THEME:\n";
echo "- Name: " . $active_theme->get('Name') . "\n";
echo "- Stylesheet: " . $active_theme->get_stylesheet() . "\n";
echo "- Status: " . ($active_theme->get_stylesheet() === 'celzimo-theme' ? 'OK' : 'FAIL') . "\n\n";

// 2. Check Active Plugins
$required_plugins = array(
    'woocommerce/woocommerce.php' => 'WooCommerce',
    'fluid-checkout/fluid-checkout.php' => 'Fluid Checkout Lite',
    'comunas-de-chile-para-woocommerce/woocommerce-comunas.php' => 'Comunas de Chile',
    'woo-checkout-field-editor-pro/checkout-form-designer.php' => 'Checkout Field Editor (ThemeHigh)',
    'popup-maker/popup-maker.php' => 'Popup Maker'
);

echo "ACTIVE PLUGINS:\n";
foreach ($required_plugins as $plugin_file => $plugin_name) {
    $active = is_plugin_active($plugin_file);
    echo "- $plugin_name: " . ($active ? 'ACTIVE (OK)' : 'INACTIVE (FAIL)') . "\n";
}
echo "\n";

// 3. Check WooCommerce Options
echo "WOOCOMMERCE OPTIONS:\n";
echo "- Country: " . get_option('woocommerce_default_country') . " (Expected: CL) - " . (get_option('woocommerce_default_country') === 'CL' ? 'OK' : 'FAIL') . "\n";
echo "- Currency: " . get_option('woocommerce_currency') . " (Expected: CLP) - " . (get_option('woocommerce_currency') === 'CLP' ? 'OK' : 'FAIL') . "\n";
echo "- Price Decimals: " . get_option('woocommerce_price_num_decimals') . " (Expected: 0) - " . (get_option('woocommerce_price_num_decimals') == 0 ? 'OK' : 'FAIL') . "\n\n";

// 4. Check Popup Maker popup config
echo "POPUP MAKER CONFIGURATION:\n";
$popup = get_post(92);
if ($popup && $popup->post_type === 'popup') {
    echo "- Popup Title: " . $popup->post_title . "\n";
    echo "- Popup Content: " . $popup->post_content . " (Expected: [woocommerce_checkout])\n";
    $settings = get_post_meta($popup->ID, '_pum_popup_settings', true);
    if (isset($settings['triggers'][0]['settings']['extra_selectors'])) {
        echo "- Trigger Selectors: " . $settings['triggers'][0]['settings']['extra_selectors'] . " (Expected: .checkout-trigger) - OK\n";
    } else {
        echo "- Trigger Selectors: NOT FOUND - FAIL\n";
    }
} else {
    echo "- Popup ID 92: NOT FOUND - FAIL\n";
}
echo "\n";

// 5. Check Checkout Fields (ThemeHigh)
if (class_exists('THWCFD_Utils')) {
    echo "THEMEHIGH CHECKOUT FIELDS:\n";
    $shipping = THWCFD_Utils::get_fields('shipping');
    
    // Check Nombre Completo
    if (isset($shipping['shipping_first_name']) && $shipping['shipping_first_name']['label'] === 'Nombre Completo') {
        echo "- shipping_first_name renamed to Nombre Completo: OK\n";
    } else {
        echo "- shipping_first_name renamed to Nombre Completo: FAIL\n";
    }
    
    // Check Apellidos disabled
    if (isset($shipping['shipping_last_name']) && $shipping['shipping_last_name']['enabled'] == 0) {
        echo "- shipping_last_name disabled: OK\n";
    } else {
        echo "- shipping_last_name disabled: FAIL\n";
    }
    
    // Check Phone active and required
    if (isset($shipping['shipping_phone']) && $shipping['shipping_phone']['required'] == 1) {
        echo "- shipping_phone active and required: OK\n";
    } else {
        echo "- shipping_phone active and required: FAIL\n";
    }
    
    // Check Postcode disabled
    if (isset($shipping['shipping_postcode']) && $shipping['shipping_postcode']['enabled'] == 0) {
        echo "- shipping_postcode disabled: OK\n";
    } else {
        echo "- shipping_postcode disabled: FAIL\n";
    }
    
    // Check Tipo Documento
    if (isset($shipping['shipping_tipo_documento'])) {
        echo "- shipping_tipo_documento radio button added: OK\n";
        echo "  - Type: " . $shipping['shipping_tipo_documento']['type'] . "\n";
        echo "  - Options: " . implode(', ', array_keys($shipping['shipping_tipo_documento']['options'])) . "\n";
    } else {
        echo "- shipping_tipo_documento radio button added: FAIL\n";
    }
}
echo "==================================================\n";
