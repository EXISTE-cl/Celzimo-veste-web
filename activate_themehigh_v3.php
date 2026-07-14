<?php
/**
 * Activate the correct ThemeHigh Checkout Field Editor main file.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

$themehigh_slug = 'woo-checkout-field-editor-pro/checkout-form-designer.php';

// Clear plugin cache
wp_clean_plugins_cache();

if (!is_plugin_active($themehigh_slug)) {
    $activate_result = activate_plugin($themehigh_slug);
    if (is_wp_error($activate_result)) {
        die("Error activating plugin: " . $activate_result->get_error_message());
    }
    echo "SUCCESSFULLY_ACTIVATED_THEMEHIGH_V3";
} else {
    echo "ALREADY_ACTIVE";
}
