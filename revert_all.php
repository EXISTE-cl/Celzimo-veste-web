<?php
/**
 * REVERT — Deshace todos los cambios del master_setup.php
 */
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') die("No autorizado.");

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$r = [];

/* 1. Revertir ajustes de WooCommerce a valores originales */
update_option('woocommerce_default_country',        'CL');
update_option('woocommerce_currency',               'CLP');
update_option('woocommerce_price_decimals',         '0');
update_option('woocommerce_price_thousand_sep',     '.');
update_option('woocommerce_price_decimal_sep',      ',');
// Restaurar envío a todos los países (más seguro que solo CL)
delete_option('woocommerce_ship_to_countries');
delete_option('woocommerce_specific_ship_to_countries');
$r['wc'] = '✅ WooCommerce revertido';

/* 2. Revertir popup ID 92 — contenido limpio sin shortcode problemático */
$popup_id = 92;
wp_update_post([
    'ID'           => $popup_id,
    'post_content' => '[woocommerce_checkout]',
    'post_status'  => 'publish',
]);
// Limpiar triggers del popup (dejarlo sin trigger automático)
$pum_settings = [
    'size'                   => 'medium',
    'overlay_disabled'       => false,
    'scrollable_content'     => true,
    'close_button_delay'     => 0,
    'close_on_overlay_click' => true,
    'close_on_esc_press'     => true,
    'disable_scrolling'      => true,
    'position_fixed'         => true,
    'animation_type'         => 'fade',
    'animation_speed'        => '350',
    'triggers'               => [],
    'cookies'                => [],
];
update_post_meta($popup_id, '_pum_settings', $pum_settings);
$r['popup'] = '✅ Popup revertido (sin triggers automáticos)';

/* 3. Revertir ThemeHigh fields — restaurar campos estándar de WooCommerce */
if (class_exists('THWCFD_Utils')) {
    // Billing — restaurar campos a estado original (habilitados por defecto)
    $billing = THWCFD_Utils::get_fields('billing');
    $restore_billing = [
        'billing_first_name' => ['label'=>'Nombre','placeholder'=>'','required'=>true,'enabled'=>true,'class'=>['form-row-first']],
        'billing_last_name'  => ['label'=>'Apellidos','required'=>true,'enabled'=>true,'class'=>['form-row-last']],
        'billing_phone'      => ['label'=>'Teléfono','placeholder'=>'','required'=>true,'enabled'=>true,'class'=>['form-row-wide']],
        'billing_country'    => ['enabled'=>true,'required'=>true],
        'billing_state'      => ['label'=>'Estado / Región','required'=>true,'enabled'=>true,'class'=>['form-row-wide']],
        'billing_city'       => ['label'=>'Ciudad','enabled'=>true,'required'=>true,'class'=>['form-row-wide']],
        'billing_address_1'  => ['label'=>'Dirección','placeholder'=>'','required'=>true,'enabled'=>true,'class'=>['form-row-wide']],
        'billing_postcode'   => ['label'=>'Código postal','enabled'=>false,'required'=>false],
        'billing_address_2'  => ['enabled'=>false,'required'=>false],
        'billing_company'    => ['enabled'=>false,'required'=>false],
    ];
    foreach ($restore_billing as $key => $props) {
        if (isset($billing[$key])) {
            foreach ($props as $pk => $pv) {
                $billing[$key][$pk] = $pv;
            }
        }
    }
    // Mantener billing_tipo_documento si existe pero dejarlo desactivado
    if (isset($billing['billing_tipo_documento'])) {
        $billing['billing_tipo_documento']['enabled'] = false;
    }
    THWCFD_Utils::update_fields('billing', $billing);

    // Shipping — restaurar
    $shipping = THWCFD_Utils::get_fields('shipping');
    $restore_shipping = [
        'shipping_first_name' => ['label'=>'Nombre','placeholder'=>'','required'=>true,'enabled'=>true,'class'=>['form-row-first']],
        'shipping_last_name'  => ['label'=>'Apellidos','required'=>true,'enabled'=>true,'class'=>['form-row-last']],
        'shipping_phone'      => ['label'=>'Teléfono','placeholder'=>'','required'=>false,'enabled'=>true,'class'=>['form-row-wide']],
        'shipping_country'    => ['enabled'=>true,'required'=>true],
        'shipping_state'      => ['label'=>'Estado / Región','required'=>true,'enabled'=>true,'class'=>['form-row-wide']],
        'shipping_city'       => ['label'=>'Ciudad','enabled'=>true,'required'=>true,'class'=>['form-row-wide']],
        'shipping_address_1'  => ['label'=>'Dirección','placeholder'=>'','required'=>true,'enabled'=>true,'class'=>['form-row-wide']],
        'shipping_postcode'   => ['enabled'=>false,'required'=>false],
        'shipping_address_2'  => ['enabled'=>false,'required'=>false],
    ];
    foreach ($restore_shipping as $key => $props) {
        if (isset($shipping[$key])) {
            foreach ($props as $pk => $pv) {
                $shipping[$key][$pk] = $pv;
            }
        }
    }
    if (isset($shipping['shipping_tipo_documento'])) {
        $shipping['shipping_tipo_documento']['enabled'] = false;
    }
    THWCFD_Utils::update_fields('shipping', $shipping);
    $r['themehigh'] = '✅ ThemeHigh fields revertidos';
} else {
    $r['themehigh'] = '⚠️ THWCFD_Utils no encontrado';
}

/* 4. Revertir Fluid Checkout settings */
delete_option('fc_checkout_layout');
delete_option('fc_enable_checkout_layout');
delete_option('fc_billing_address_display_disabled');
delete_option('fc_shipping_address_same_as_billing');
$r['fluid_checkout'] = '✅ Fluid Checkout opciones revertidas';

/* 5. Limpiar caché */
wp_cache_flush();
flush_rewrite_rules(true);
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
$r['cache'] = '✅ Caché limpiado';

echo json_encode($r, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
