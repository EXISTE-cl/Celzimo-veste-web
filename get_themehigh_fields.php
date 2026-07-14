<?php
/**
 * Get current ThemeHigh fields structure.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

// Get billing fields using the correct method
$billing_fields = THWCFD_Utils::get_fields('billing');
$shipping_fields = THWCFD_Utils::get_fields('shipping');

$result = [
    'billing_fields'  => [],
    'shipping_fields' => [],
];

if ($billing_fields) {
    foreach ($billing_fields as $key => $field) {
        $result['billing_fields'][$key] = [
            'type'     => isset($field->type) ? $field->type : (isset($field['type']) ? $field['type'] : '?'),
            'label'    => isset($field->label) ? $field->label : (isset($field['label']) ? $field['label'] : '?'),
            'enabled'  => isset($field->enabled) ? $field->enabled : (isset($field['enabled']) ? $field['enabled'] : '?'),
            'required' => isset($field->required) ? $field->required : (isset($field['required']) ? $field['required'] : '?'),
            'class'    => isset($field->class) ? implode(' ', (array)$field->class) : '?',
        ];
    }
}

if ($shipping_fields) {
    foreach ($shipping_fields as $key => $field) {
        $result['shipping_fields'][$key] = [
            'type'     => isset($field->type) ? $field->type : (isset($field['type']) ? $field['type'] : '?'),
            'label'    => isset($field->label) ? $field->label : (isset($field['label']) ? $field['label'] : '?'),
            'enabled'  => isset($field->enabled) ? $field->enabled : (isset($field['enabled']) ? $field['enabled'] : '?'),
            'required' => isset($field->required) ? $field->required : (isset($field['required']) ? $field['required'] : '?'),
        ];
    }
}

// Also print raw to understand structure
$result['raw_first_billing_field'] = print_r(reset($billing_fields), true);

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
