<?php
/**
 * Diagnostic script to get detailed plugin metadata.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');
$all_plugins = get_plugins();

foreach ($all_plugins as $plugin_file => $data) {
    if (strpos($plugin_file, 'checkout') !== false || strpos($plugin_file, 'editor') !== false) {
        echo "Plugin File: {$plugin_file}\n";
        echo "Name: {$data['Name']}\n";
        echo "PluginURI: {$data['PluginURI']}\n";
        echo "Version: {$data['Version']}\n";
        echo "Description: {$data['Description']}\n";
        echo "Author: {$data['Author']}\n";
        echo "AuthorURI: {$data['AuthorURI']}\n";
        echo "TextDomain: {$data['TextDomain']}\n";
        echo "-------------------------------------\n";
    }
}
