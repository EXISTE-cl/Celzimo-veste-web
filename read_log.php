<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$log_file = WP_CONTENT_DIR . '/checkout_debug.log';
if (file_exists($log_file)) {
    echo file_get_contents($log_file);
} else {
    echo 'LOG_EMPTY_OR_NOT_FOUND';
}
