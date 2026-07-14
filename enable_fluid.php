<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

require_once(ABSPATH . 'wp-admin/includes/plugin.php');
$plugin = 'fluid-checkout/fluid-checkout.php';

if (!is_plugin_active($plugin)) {
    activate_plugins($plugin);
    echo "Fluid Checkout ACTIVADO de nuevo.\n";
} else {
    echo "Fluid Checkout ya estaba activo.\n";
}
