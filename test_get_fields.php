<?php
/**
 * Test get_fields and get_checkout_fields from THWCFD_Utils.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

if (class_exists('THWCFD_Utils')) {
    echo "CALLING get_fields('billing'):\n";
    $fields = THWCFD_Utils::get_fields('billing');
    print_r($fields);
    
    echo "\nCALLING get_fields('shipping'):\n";
    $sfields = THWCFD_Utils::get_fields('shipping');
    print_r($sfields);
} else {
    echo "THWCFD_Utils does not exist.\n";
}
