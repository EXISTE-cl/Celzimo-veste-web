<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

require_once(ABSPATH . 'wp-admin/includes/plugin.php');
$plugin = 'popup-maker/popup-maker.php';

if (is_plugin_active($plugin)) {
    deactivate_plugins($plugin);
    echo "Popup Maker DESACTIVADO.\n";
} else {
    echo "Popup Maker ya estaba desactivado.\n";
}
