<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$mu_dir = WP_CONTENT_DIR . '/mu-plugins';
if (!is_dir($mu_dir)) {
    mkdir($mu_dir, 0755, true);
}

$code = <<<'PHP'
<?php
$log_file = WP_CONTENT_DIR . '/checkout_debug.log';

if (isset($_GET['wc-ajax']) && $_GET['wc-ajax'] === 'checkout') {
    $time = date('Y-m-d H:i:s');
    $post_data = print_r($_POST, true);
    
    file_put_contents($log_file, "[$time] === NUEVO INTENTO DE CHECKOUT ===\n", FILE_APPEND);
    file_put_contents($log_file, "Datos enviados:\n$post_data\n", FILE_APPEND);
    
    // Capturar la respuesta JSON justo antes de que se envíe al navegador
    // Solo en WooCommerce más reciente existe filter woocommerce_send_json_response, pero mejor capturar en php_shutdown si es necesario, 
    // o hacer un hack interceptando json_encode.
}

// Hook a cuando WooCommerce agrega un error
add_action('woocommerce_add_error', function($error) use ($log_file) {
    if (isset($_GET['wc-ajax']) && $_GET['wc-ajax'] === 'checkout') {
        $time = date('Y-m-d H:i:s');
        file_put_contents($log_file, "[$time] WC_ERROR: $error\n", FILE_APPEND);
    }
});

add_filter('woocommerce_checkout_process', function() use ($log_file) {
    if (isset($_GET['wc-ajax']) && $_GET['wc-ajax'] === 'checkout') {
        $time = date('Y-m-d H:i:s');
        file_put_contents($log_file, "[$time] woocommerce_checkout_process SE DISPARÓ (El formulario pasó la validación inicial JS)\n", FILE_APPEND);
    }
});

PHP;

file_put_contents($mu_dir . '/checkout-logger.php', $code);

// Limpiar el log anterior si existe
if (file_exists(WP_CONTENT_DIR . '/checkout_debug.log')) {
    unlink(WP_CONTENT_DIR . '/checkout_debug.log');
}

echo "Logger instalado en mu-plugins.\n";
