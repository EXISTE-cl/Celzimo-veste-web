<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

require_once(ABSPATH . 'wp-admin/includes/plugin.php');

$plugins = [
    'woo-checkout-field-editor-pro/checkout-form-designer.php',
    'wc-cart-sync/wc-cart-sync.php'
];

foreach ($plugins as $plugin) {
    if (!is_plugin_active($plugin)) {
        activate_plugins($plugin);
        echo "Activado: $plugin\n";
    }
}
