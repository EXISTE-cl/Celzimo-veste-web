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
    file_put_contents($log_file, "Datos POST: " . print_r($_POST, true) . "\n", FILE_APPEND);

    // Iniciar captura de buffer para ver la respuesta final
    ob_start();
    
    add_action('shutdown', function() use ($log_file, $time) {
        $output = ob_get_clean();
        file_put_contents($log_file, "[$time] === RESPUESTA ENVIADA AL CLIENTE ===\n$output\n\n", FILE_APPEND);
        echo $output;
    }, 0);
}
PHP;

file_put_contents($mu_dir . '/checkout-logger.php', $code);
echo "Logger de respuesta instalado.\n";
