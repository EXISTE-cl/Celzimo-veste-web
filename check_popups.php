<?php
/**
 * Check existing Popup Maker popups.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$popups = get_posts(array(
    'post_type' => 'popup',
    'posts_per_page' => -1,
    'post_status' => 'any'
));

echo "FOUND " . count($popups) . " POPUPS:\n";
foreach ($popups as $popup) {
    echo "- ID: {$popup->ID} | Title: {$popup->post_title} | Status: {$popup->post_status}\n";
    $settings = get_post_meta($popup->ID, '_pum_popup_settings', true);
    if ($settings) {
        print_r($settings);
    }
    echo "----------------------------------------\n";
}
