<?php
/**
 * Configure ThemeHigh Checkout Fields with correct option key.
 * Plugin: woo-checkout-field-editor-pro/checkout-form-designer.php
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$results = [];

// Check which option keys ThemeHigh uses
$possible_option_keys = [
    'thwcfe_checkout_fields',
    'thwcfd_section_fields',
    'thwcfd_checkout_fields',
    'thwcfd_options',
    'thwcfe_sections',
    'checkout_fields',
];

foreach ($possible_option_keys as $key) {
    $val = get_option($key, null);
    if ($val !== null && $val !== false) {
        $results['found_options'][$key] = gettype($val) . ' — ' . (is_array($val) ? count($val) . ' entries' : substr(print_r($val, true), 0, 100));
    }
}

// Let's also check class and methods available
if (class_exists('THWCFD_Utils')) {
    $results['class'] = 'THWCFD_Utils found';
    $results['methods'] = get_class_methods('THWCFD_Utils');
} elseif (class_exists('THWCFE_Utils')) {
    $results['class'] = 'THWCFE_Utils found';
    $results['methods'] = get_class_methods('THWCFE_Utils');
} else {
    // search all classes
    $allClasses = get_declared_classes();
    $thClasses = array_filter($allClasses, function($c) {
        return stripos($c, 'THWCF') !== false || stripos($c, 'checkout_field') !== false;
    });
    $results['th_classes'] = array_values($thClasses);
}

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
