<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') die("No autorizado.");

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$plugin = 'side-cart-woocommerce/xoo-wsc-main.php';
if (is_plugin_active($plugin)) {
    deactivate_plugins($plugin);
    echo "✅ Side Cart WooCommerce desactivado.";
} else {
    echo "ℹ️ Plugin ya estaba inactivo.";
}
