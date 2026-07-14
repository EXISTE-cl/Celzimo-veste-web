<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$log_file = WP_CONTENT_DIR . '/checkout_debug.log';
if (file_exists($log_file)) {
    echo "=== ULTIMAS 100 LÍNEAS DEL LOG ===\n";
    $lines = file($log_file);
    $last_lines = array_slice($lines, -150);
    echo implode("", $last_lines);
} else {
    echo "No existe el log.\n";
}
