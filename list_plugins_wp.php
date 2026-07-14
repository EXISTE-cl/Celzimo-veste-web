<?php
/**
 * Diagnostic script to list all installed and active WordPress plugins.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

$all_plugins = get_plugins();
$active_plugins = get_option('active_plugins');

echo "INSTALLED PLUGINS:\n";
foreach ($all_plugins as $plugin_file => $data) {
    $status = in_array($plugin_file, $active_plugins) ? 'ACTIVE' : 'INACTIVE';
    echo "- {$data['Name']} (Version: {$data['Version']}) - [{$plugin_file}] - Status: {$status}\n";
}
