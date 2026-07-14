<?php
/**
 * Check ThemeHigh classes.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

if (class_exists('THWCFD_Utils')) {
    echo "THWCFD_Utils class exists.\n";
    $billing_fields = THWCFD_Utils::get_billing_fields();
    echo "Billing fields: " . (is_array($billing_fields) ? count($billing_fields) : "not array") . "\n";
    if (is_array($billing_fields)) {
        print_r(array_keys($billing_fields));
    }
} else {
    echo "THWCFD_Utils does not exist.\n";
}
