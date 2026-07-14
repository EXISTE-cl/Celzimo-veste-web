<?php
/**
 * Dump wc_fields_ options.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No unauthorized access.");
}

$keys = array('billing', 'shipping', 'additional');
foreach ($keys as $key) {
    $option_name = 'wc_fields_' . $key;
    $val = get_option($option_name);
    echo "$option_name:\n";
    if ($val) {
        print_r($val);
    } else {
        echo "Empty or not set\n";
    }
    echo "----------------------------------------\n";
}
