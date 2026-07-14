<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No unauthorized."); }

$log_file = WP_CONTENT_DIR . '/checkout_debug.log';
$res = file_put_contents($log_file, "--- TEST WRITE ---\n", FILE_APPEND);
if ($res === false) {
    echo "ERROR: Cannot write to $log_file\n";
    $err = error_get_last();
    print_r($err);
} else {
    echo "SUCCESS: Wrote $res bytes to $log_file\n";
}
