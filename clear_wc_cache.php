<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

// Limpiar transitorios de WooCommerce
wc_delete_expired_transients(true);
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_wc_ship_%'");
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_wc_ship_%'");

// Limpiar sesiones expiradas
$session_handler = new WC_Session_Handler();
$session_handler->cleanup_sessions();

echo "Caché y transitorios de WooCommerce limpiados.\n";
