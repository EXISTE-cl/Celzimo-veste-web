<?php
/**
 * Install and activate Checkout Field Editor (ThemeHigh)
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');
include_once(ABSPATH . 'wp-admin/includes/file.php');

// Deactivate Jcodex plugin first
$jcodex_slug = 'woo-checkout-regsiter-field-editor/main.php';
if (is_plugin_active($jcodex_slug)) {
    deactivate_plugins($jcodex_slug);
    echo "Deactivated Jcodex Checkout Field Editor.\n";
}

// Download ThemeHigh plugin
$url = 'https://downloads.wordpress.org/plugin/woocommerce-checkout-field-editor.zip';
$tmp_file = download_url($url);

if (is_wp_error($tmp_file)) {
    die("Error downloading plugin: " . $tmp_file->get_error_message());
}

// Unzip
$plugins_dir = WP_PLUGIN_DIR;
WP_Filesystem();
$unzip_result = unzip_file($tmp_file, $plugins_dir);

if (is_wp_error($unzip_result)) {
    unlink($tmp_file);
    die("Error unzipping plugin: " . $unzip_result->get_error_message());
}

unlink($tmp_file);
echo "ThemeHigh Checkout Field Editor extracted successfully.\n";

// Activate ThemeHigh plugin
$themehigh_slug = 'woocommerce-checkout-field-editor/woocommerce-checkout-field-editor.php';
$activate_result = activate_plugin($themehigh_slug);

if (is_wp_error($activate_result)) {
    die("Error activating plugin: " . $activate_result->get_error_message());
}

echo "ThemeHigh Checkout Field Editor activated successfully!\n";
