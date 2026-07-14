<?php
/**
 * MASTER SETUP SCRIPT — Celzimo Veste Checkout Modal
 * Configura popup, ThemeHigh fields, WC options y ajustes finales.
 */
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') die("No autorizado.");

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$r = [];

/* =========================================================
   1. WooCommerce Settings
   ========================================================= */
update_option('woocommerce_default_country',        'CL');
update_option('woocommerce_currency',               'CLP');
update_option('woocommerce_price_decimals',         '0');
update_option('woocommerce_price_thousand_sep',     '.');
update_option('woocommerce_price_decimal_sep',      ',');
update_option('woocommerce_ship_to_countries',      'specific');
update_option('woocommerce_specific_ship_to_countries', ['CL']);
update_option('woocommerce_calc_taxes',             'yes');
update_option('woocommerce_enable_checkout_login_reminder', 'no');
update_option('woocommerce_registration_generate_password', 'yes');
$r['wc_settings'] = '✅ WooCommerce configurado (CLP, CL, 0 decimales)';

/* =========================================================
   2. Popup Maker — Configurar popup ID 92 correctamente
   ========================================================= */
$popup_id = 92;

// Actualizar contenido con [woocommerce_checkout]
wp_update_post([
    'ID'           => $popup_id,
    'post_content' => '[woocommerce_checkout]',
    'post_status'  => 'publish',
]);

// Configurar settings del popup
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
    'animation_origin'       => 'center top',
    'overlay_zindex'         => 1999999999,
    'container_zindex'       => 1999999999,
    // Triggers
    'triggers'               => [
        [
            'type'         => 'click_open',
            'settings'     => [
                'extra_selectors' => '.checkout-trigger, a.checkout-button, .checkout-button, .xoo-wsc-ft-btn-checkout, .side-cart-checkout-button',
                'do_default'      => false,
            ],
        ],
    ],
    // Cookies (no auto-open)
    'cookies' => [],
];
update_post_meta($popup_id, '_pum_settings', $pum_settings);

// Popup theme — usar tema por defecto de PUM o el que exista
$themes = get_posts(['post_type' => 'popup_theme', 'numberposts' => 1, 'post_status' => 'publish']);
if ($themes) {
    update_post_meta($popup_id, '_pum_theme_id', $themes[0]->ID);
}

$r['popup'] = '✅ Popup ID 92 configurado — trigger: .checkout-trigger — contenido: [woocommerce_checkout]';

/* =========================================================
   3. ThemeHigh — Configurar campos via THWCFD_Utils
   ========================================================= */
if (class_exists('THWCFD_Utils')) {

    // Billing fields
    $billing = THWCFD_Utils::get_fields('billing');

    $billing_updates = [
        'billing_first_name' => ['label'=>'Nombre Completo','placeholder'=>'Juan Pérez','required'=>true,'enabled'=>true,'class'=>['form-row-first'],'priority'=>10],
        'billing_last_name'  => ['enabled'=>false,'required'=>false,'priority'=>15],
        'billing_phone'      => ['label'=>'Teléfono de contacto','placeholder'=>'+56 9 XXXX XXXX','required'=>true,'enabled'=>true,'class'=>['form-row-last'],'priority'=>20],
        'billing_country'    => ['enabled'=>false,'required'=>false,'priority'=>25],
        'billing_state'      => ['label'=>'Región / Comuna','required'=>true,'enabled'=>true,'class'=>['form-row-wide'],'priority'=>30],
        'billing_city'       => ['enabled'=>false,'required'=>false,'priority'=>35],
        'billing_address_1'  => ['label'=>'Dirección de Despacho','placeholder'=>'Av. Providencia 1234, Depto 402','required'=>true,'enabled'=>true,'class'=>['form-row-wide'],'priority'=>40],
        'billing_address_2'  => ['enabled'=>false,'required'=>false,'priority'=>45],
        'billing_postcode'   => ['enabled'=>false,'required'=>false,'priority'=>50],
        'billing_email'      => ['label'=>'Correo electrónico','required'=>true,'enabled'=>true,'class'=>['form-row-wide'],'priority'=>55],
        'billing_company'    => ['enabled'=>false,'required'=>false,'priority'=>60],
    ];

    foreach ($billing_updates as $key => $props) {
        if (isset($billing[$key])) {
            foreach ($props as $pk => $pv) {
                $billing[$key][$pk] = $pv;
            }
        }
    }

    // Ensure billing_tipo_documento exists as a radio field
    if (!isset($billing['billing_tipo_documento'])) {
        $billing['billing_tipo_documento'] = [
            'type'        => 'radio',
            'label'       => 'Tipo de Documento',
            'options'     => ['Boleta'=>'Boleta','Factura'=>'Factura'],
            'default'     => 'Boleta',
            'required'    => true,
            'enabled'     => true,
            'custom'      => true,
            'class'       => ['form-row-wide','tipo-documento-cards'],
            'priority'    => 65,
            'show_in_email' => true,
            'show_in_order' => true,
        ];
    } else {
        $billing['billing_tipo_documento']['enabled']  = true;
        $billing['billing_tipo_documento']['class']    = ['form-row-wide','tipo-documento-cards'];
        $billing['billing_tipo_documento']['priority'] = 65;
    }

    THWCFD_Utils::update_fields('billing', $billing);

    // Shipping fields — mirror of billing but WITHOUT tipo_documento
    $shipping = THWCFD_Utils::get_fields('shipping');
    $shipping_updates = [
        'shipping_first_name' => ['label'=>'Nombre Completo','placeholder'=>'Juan Pérez','required'=>true,'enabled'=>true,'class'=>['form-row-first'],'priority'=>10],
        'shipping_last_name'  => ['enabled'=>false,'required'=>false,'priority'=>15],
        'shipping_phone'      => ['label'=>'Teléfono de contacto','placeholder'=>'+56 9 XXXX XXXX','required'=>true,'enabled'=>true,'class'=>['form-row-last'],'priority'=>20],
        'shipping_country'    => ['enabled'=>false,'required'=>false,'priority'=>25],
        'shipping_state'      => ['label'=>'Región / Comuna','required'=>true,'enabled'=>true,'class'=>['form-row-wide'],'priority'=>30],
        'shipping_city'       => ['enabled'=>false,'required'=>false,'priority'=>35],
        'shipping_address_1'  => ['label'=>'Dirección de Despacho','placeholder'=>'Av. Providencia 1234, Depto 402','required'=>true,'enabled'=>true,'class'=>['form-row-wide'],'priority'=>40],
        'shipping_address_2'  => ['enabled'=>false,'required'=>false,'priority'=>45],
        'shipping_postcode'   => ['enabled'=>false,'required'=>false,'priority'=>50],
    ];
    foreach ($shipping_updates as $key => $props) {
        if (isset($shipping[$key])) {
            foreach ($props as $pk => $pv) {
                $shipping[$key][$pk] = $pv;
            }
        }
    }
    // Remove tipo_documento from shipping if present
    unset($shipping['shipping_tipo_documento']);

    THWCFD_Utils::update_fields('shipping', $shipping);

    $r['themehigh'] = '✅ ThemeHigh fields configurados (billing + shipping sin tipo_documento)';
} else {
    $r['themehigh'] = '❌ THWCFD_Utils class not found';
}

/* =========================================================
   4. Fluid Checkout Settings
   ========================================================= */
update_option('fc_checkout_layout',                    'multi-step');
update_option('fc_enable_checkout_layout',              'yes');
update_option('fc_hide_optional_fields_link',           'yes');
update_option('fc_enable_coupon_code_section',          'no');
update_option('fc_billing_address_display_disabled',    'no');
update_option('fc_shipping_address_same_as_billing',    'yes');
$r['fluid_checkout'] = '✅ Fluid Checkout configurado (multi-step, ship=billing)';

/* =========================================================
   5. Flush rewrite rules & cache
   ========================================================= */
flush_rewrite_rules(true);
wp_cache_flush();
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
$r['cache'] = '✅ Caché limpiado, rewrite rules actualizadas';

/* =========================================================
   6. Resumen final
   ========================================================= */
$r['popup_id']     = $popup_id;
$r['cart_url']     = wc_get_cart_url();
$r['checkout_url'] = wc_get_checkout_url();

echo json_encode($r, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
