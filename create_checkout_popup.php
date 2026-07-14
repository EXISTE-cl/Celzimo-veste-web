<?php
/**
 * Create or update the checkout modal popup in Popup Maker.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

// Find default popup theme ID
$themes = get_posts(array(
    'post_type' => 'popup_theme',
    'posts_per_page' => 1,
    'post_status' => 'publish'
));
$theme_id = !empty($themes) ? $themes[0]->ID : 0;
echo "Found theme ID: $theme_id\n";

// Check if popup already exists
$existing = get_posts(array(
    'post_type' => 'popup',
    'title' => 'Finalizar Compra',
    'posts_per_page' => 1,
    'post_status' => 'any'
));

$popup_data = array(
    'post_title'   => 'Finalizar Compra',
    'post_content' => '[woocommerce_checkout]',
    'post_status'  => 'publish',
    'post_type'    => 'popup'
);

if (!empty($existing)) {
    $popup_id = $existing[0]->ID;
    $popup_data['ID'] = $popup_id;
    wp_update_post($popup_data);
    echo "Updated existing popup ID: $popup_id\n";
} else {
    $popup_id = wp_insert_post($popup_data);
    echo "Created new popup ID: $popup_id\n";
}

// Configure Popup Maker settings
$popup_settings = array(
    'triggers' => array(
        array(
            'type' => 'click_open',
            'settings' => array(
                'extra_selectors' => '.checkout-trigger',
                'cookie_name' => '',
            )
        )
    ),
    'display' => array(
        'size' => 'large',
        'custom_width' => '95%',
        'custom_width_unit' => '%',
        'custom_height' => 'auto',
        'custom_height_unit' => 'px',
        'custom_position' => 'center',
        'location' => 'center',
        'position_top' => 100,
        'position_left' => 0,
        'position_bottom' => 0,
        'position_right' => 0,
        'position_from' => 'monitor',
        'animation_type' => 'fade',
        'animation_speed' => 350,
        'animation_origin' => 'center top',
        'overlay_disabled' => false,
        'stackable' => false,
        'disable_reposition' => false,
        'scrollable_content' => false,
        'theme_id' => $theme_id,
    ),
    'close' => array(
        'overlay_click' => true,
        'esc_press' => true,
        'f4_press' => false,
        'text' => '',
        'button_delay' => '0',
    ),
    'cookies' => array(),
    'conditions' => array()
);

update_post_meta($popup_id, '_pum_popup_settings', $popup_settings);
echo "POPUP_CONFIGURED_SUCCESSFULLY";
