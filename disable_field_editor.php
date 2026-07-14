<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

require_once(ABSPATH . 'wp-admin/includes/plugin.php');
$plugin = 'woo-checkout-field-editor-pro/checkout-form-designer.php';

if (is_plugin_active($plugin)) {
    deactivate_plugins($plugin);
    echo "Checkout Field Editor Pro DESACTIVADO.\n";
} else {
    echo "Checkout Field Editor Pro ya estaba desactivado.\n";
}
