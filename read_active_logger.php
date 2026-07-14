<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$file = WP_CONTENT_DIR . '/mu-plugins/checkout-logger.php';
if (file_exists($file)) {
    echo file_get_contents($file);
} else {
    echo "FILE_NOT_FOUND";
}
