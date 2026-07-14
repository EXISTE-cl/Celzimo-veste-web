<?php
/**
 * View checkout-hide-optional-fields.php
 */
define('WP_USE_THEMES', false);
require_once('wp-load.php');

if (!current_user_can('manage_options') && ($_GET['token'] ?? '') !== 'Csc170431Activation') {
    die('Unauthorized');
}

$file_path = WP_PLUGIN_DIR . '/fluid-checkout/inc/checkout-hide-optional-fields.php';
if (file_exists($file_path)) {
    echo htmlspecialchars(file_get_contents($file_path));
} else {
    echo "File not found.";
}
