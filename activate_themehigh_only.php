<?php
/**
 * Activate the already unzipped ThemeHigh Checkout Field Editor plugin.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

$themehigh_slug = 'woocommerce-checkout-field-editor/woocommerce-checkout-field-editor.php';

// Clear plugin cache
wp_clean_plugins_cache();

if (!is_plugin_active($themehigh_slug)) {
    $activate_result = activate_plugin($themehigh_slug);
    if (is_wp_error($activate_result)) {
        die("Error activating plugin: " . $activate_result->get_error_message());
    }
    echo "SUCCESSFULLY_ACTIVATED_THEMEHIGH";
} else {
    echo "ALREADY_ACTIVE";
}
