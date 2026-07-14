<?php
/**
 * Check Popup Maker popup 92 settings and configure it correctly.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$popup_id = 92;
$settings = get_post_meta($popup_id, '_pum_settings', true);
$popup = get_post($popup_id);

echo "<h2>Popup ID $popup_id: " . esc_html($popup->post_title) . "</h2>";
echo "<pre>Settings:\n" . print_r($settings, true) . "</pre>";

// Fix the popup settings if needed
$new_settings = wp_parse_args([
    'size'               => 'medium',       // medium = ~640px
    'responsive_full_width' => true,
    'overlay_disabled'   => false,
    'stackable'          => false,
    'scrollable_content' => true,
    'close_button_delay' => 0,
    'close_on_overlay_click' => true,
    'close_on_esc_press'    => true,
    'close_on_f4_press'     => false,
    'disable_on_mobile'     => false,
    'disable_scrolling'     => true,
    'position_fixed'        => true,
    'overlay_zindex'        => 1999999999,
    'container_zindex'      => 1999999999,
    'animation_type'        => 'fade',
    'animation_speed'       => '350',
    'animation_origin'      => 'center top',
    'trigger'               => 'click',
], $settings ?: []);

// Update with correct width
update_post_meta($popup_id, '_pum_settings', $new_settings);
echo "<p>✅ Popup configurado correctamente</p>";
echo "<pre>Updated settings:\n" . print_r($new_settings, true) . "</pre>";
