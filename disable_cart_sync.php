<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

require_once(ABSPATH . 'wp-admin/includes/plugin.php');
$plugin = 'wc-cart-sync/wc-cart-sync.php';

if (is_plugin_active($plugin)) {
    deactivate_plugins($plugin);
    echo "Cart Sync DESACTIVADO.\n";
} else {
    echo "Cart Sync ya estaba desactivado.\n";
}
