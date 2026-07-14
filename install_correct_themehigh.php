<?php
/**
 * Install the correct ThemeHigh Checkout Field Editor.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');
include_once(ABSPATH . 'wp-admin/includes/file.php');

// Delete incorrect Solwin directory if it exists
$wrong_dir = WP_PLUGIN_DIR . '/woocommerce-checkout-field-editor';
if (is_dir($wrong_dir)) {
    // Recursively delete folder
    WP_Filesystem();
    global $wp_filesystem;
    $wp_filesystem->delete($wrong_dir, true);
    echo "Deleted wrong Solwin plugin folder.\n";
}

// Download correct ThemeHigh plugin
$url = 'https://downloads.wordpress.org/plugin/checkout-field-editor-for-woocommerce.zip';
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
echo "ThemeHigh Checkout Field Editor unzipped successfully.\n";

// Clear cache and activate
wp_clean_plugins_cache();
$themehigh_slug = 'checkout-field-editor-for-woocommerce/checkout-field-editor-for-woocommerce.php';
$activate_result = activate_plugin($themehigh_slug);

if (is_wp_error($activate_result)) {
    die("Error activating plugin: " . $activate_result->get_error_message());
}

echo "ThemeHigh Checkout Field Editor activated successfully!\n";
