<?php
/**
 * Dump checkout fields options.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$options = array(
    'thwcfd_checkout_fields',
    'thwcfd_billing_fields',
    'thwcfd_shipping_fields',
    'thwcfd_additional_fields',
);

foreach ($options as $option) {
    $val = get_option($option);
    echo "$option:\n";
    if ($val) {
        print_r($val);
    } else {
        echo "Not found or empty\n";
    }
    echo "---------------------------\n";
}
