<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "=== WOOCOMMERCE LOGS ===\n";
$log_dir = WP_CONTENT_DIR . '/uploads/wc-logs/';
if (is_dir($log_dir)) {
    $files = scandir($log_dir);
    $tbk_logs = [];
    $fatal_logs = [];
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        if (strpos($file, 'transbank') !== false || strpos($file, 'webpay') !== false) {
            $tbk_logs[] = $file;
        }
        if (strpos($file, 'fatal-errors') !== false) {
            $fatal_logs[] = $file;
        }
    }
    
    echo "\n--- Transbank Logs ---\n";
    rsort($tbk_logs);
    foreach (array_slice($tbk_logs, 0, 3) as $log) {
        echo "Archivo: {$log}\n";
        $content = file_get_contents($log_dir . $log);
        // Mostrar últimas 15 líneas
        $lines = explode("\n", trim($content));
        $last_lines = array_slice($lines, -15);
        echo implode("\n", $last_lines) . "\n\n";
    }

    echo "\n--- Fatal Errors Logs ---\n";
    rsort($fatal_logs);
    foreach (array_slice($fatal_logs, 0, 1) as $log) {
        echo "Archivo: {$log}\n";
        $content = file_get_contents($log_dir . $log);
        $lines = explode("\n", trim($content));
        $last_lines = array_slice($lines, -20);
        echo implode("\n", $last_lines) . "\n\n";
    }
} else {
    echo "El directorio de logs no existe: {$log_dir}\n";
}
