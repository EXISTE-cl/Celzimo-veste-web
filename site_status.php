<?php
/**
 * Estado completo del sitio en producción.
 */
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') die("No autorizado.");

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$r = [];
$r['theme']         = get_stylesheet();
$r['show_on_front'] = get_option('show_on_front');
$r['page_on_front'] = get_option('page_on_front');
$r['currency']      = get_woocommerce_currency();
$r['base_country']  = WC()->countries->get_base_country();
$r['wc_decimals']   = wc_get_price_decimals();

// Plugins activos
$active = get_option('active_plugins', []);
$wanted = [
    'fluid-checkout/fluid-checkout.php',
    'comunas-de-chile-para-woocommerce/woocommerce-comunas.php',
    'woo-checkout-field-editor-pro/checkout-form-designer.php',
    'popup-maker/popup-maker.php',
    'side-cart-woocommerce/xoo-wsc-main.php',
    'woocommerce/woocommerce.php',
];
foreach ($wanted as $p) {
    $r['plugins'][basename(dirname($p))] = in_array($p, $active) ? 'ON' : 'OFF';
}

// Popup actual
$popups = get_posts(['post_type'=>'popup','numberposts'=>5,'post_status'=>'publish']);
foreach ($popups as $p) {
    $s = get_post_meta($p->ID, '_pum_settings', true);
    $r['popups'][] = ['id'=>$p->ID,'title'=>$p->post_title,'size'=>$s['size']??'?'];
}

// Páginas WC
$r['cart_url']     = wc_get_cart_url();
$r['checkout_url'] = wc_get_checkout_url();

// Productos disponibles
$prods = wc_get_products(['limit'=>1,'status'=>'publish']);
$r['products_count'] = count(wc_get_products(['limit'=>-1,'status'=>'publish']));
$r['sample_product_id'] = $prods ? $prods[0]->get_id() : 0;

echo json_encode($r, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
