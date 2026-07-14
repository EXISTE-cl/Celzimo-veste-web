<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$mu_dir = WP_CONTENT_DIR . '/mu-plugins';

$code = <<<'PHP'
<?php
$log_file = WP_CONTENT_DIR . '/checkout_debug.log';

if (isset($_GET['wc-ajax'])) {
    $action = $_GET['wc-ajax'];
    if ($action === 'checkout' || $action === 'update_order_review') {
        $time = date('Y-m-d H:i:s');
        file_put_contents($log_file, "[$time] === AJAX $action RECIBIDO ===\n", FILE_APPEND);
        file_put_contents($log_file, "POST: " . print_r($_POST, true) . "\n", FILE_APPEND);

        ob_start();
        
        add_action('shutdown', function() use ($log_file, $time, $action) {
            $output = ob_get_clean();
            // Log raw output (truncated to 500 chars if too long)
            $log_out = strlen($output) > 1000 ? substr($output, 0, 1000) . '... [TRUNCATED]' : $output;
            file_put_contents($log_file, "[$time] === RESPUESTA $action ENVIADA ===\n$log_out\n\n", FILE_APPEND);
            echo $output;
        }, 0);
    }
}

if (isset($_GET['celzimo_log'])) {
    $time = date('Y-m-d H:i:s');
    $msg = isset($_POST['msg']) ? $_POST['msg'] : 'No msg';
    file_put_contents($log_file, "[$time] [FRONTEND JS] $msg\n", FILE_APPEND);
    die('OK');
}
PHP;

file_put_contents($mu_dir . '/checkout-logger.php', $code);
echo "Logger v4 (Full AJAX logger) instalado.\n";
