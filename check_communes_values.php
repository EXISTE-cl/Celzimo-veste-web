<?php
/**
 * Inspect communes data.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$file = WP_PLUGIN_DIR . '/comunas-de-chile-para-woocommerce/data/communes.php';
if (file_exists($file)) {
    $states = include($file);
    echo "CL STATES COUNT: " . count($states['CL'] ?? array()) . "\n";
    echo "FIRST 10 STATES:\n";
    $i = 0;
    foreach (($states['CL'] ?? array()) as $code => $name) {
        echo "- Code: $code | Name: $name\n";
        $i++;
        if ($i >= 10) break;
    }
} else {
    echo "File not found: $file\n";
}
