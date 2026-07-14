<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$mu_dir = WP_CONTENT_DIR . '/mu-plugins';

$code = <<<'PHP'
<?php
$log_file = WP_CONTENT_DIR . '/checkout_debug.log';

if (isset($_GET['wc-ajax']) && $_GET['wc-ajax'] === 'checkout') {
    $time = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$time] === AJAX CHECKOUT RECIBIDO ===\n", FILE_APPEND);
}

if (isset($_GET['celzimo_log'])) {
    $time = date('Y-m-d H:i:s');
    $msg = isset($_POST['msg']) ? $_POST['msg'] : 'No msg';
    file_put_contents($log_file, "[$time] [FRONTEND JS] $msg\n", FILE_APPEND);
    die('OK');
}
PHP;

file_put_contents($mu_dir . '/checkout-logger.php', $code);
echo "Logger actualizado.\n";
