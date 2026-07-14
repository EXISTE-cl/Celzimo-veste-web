<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$paths = [
    ABSPATH . 'error_log',
    WP_CONTENT_DIR . '/debug.log',
    ABSPATH . 'wp-admin/error_log'
];

foreach ($paths as $path) {
    if (file_exists($path)) {
        echo "=== ERROR LOG: $path ===\n";
        // Show last 50 lines
        $lines = file($path);
        $last_lines = array_slice($lines, -50);
        echo implode("", $last_lines);
        echo "\n\n";
    } else {
        echo "No existe: $path\n";
    }
}
