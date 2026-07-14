<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { 
    die(json_encode(["success" => false, "error" => "No autorizado."])); 
}

global $wpdb;
$charset_collate = $wpdb->get_charset_collate();

// 1. Tabla de log de actividad de usuario personalizada (CZ custom log)
$table_activity = $wpdb->prefix . 'custom_user_activity_log';
$sql_activity = "CREATE TABLE IF NOT EXISTS `$table_activity` (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  user_id bigint(20) NOT NULL,
  action_type varchar(100) NOT NULL,
  description text NOT NULL,
  ip_address varchar(45) NOT NULL,
  created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  PRIMARY KEY  (id),
  KEY user_id (user_id)
) $charset_collate;";

// 2. Tabla de facturas digitales y transacciones históricas (CZ custom invoices)
$table_invoices = $wpdb->prefix . 'custom_pdf_invoices_registry';
$sql_invoices = "CREATE TABLE IF NOT EXISTS `$table_invoices` (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  order_id bigint(20) NOT NULL,
  user_id bigint(20) NOT NULL,
  invoice_number varchar(100) NOT NULL,
  pdf_path varchar(255) NOT NULL,
  created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY order_id (order_id),
  KEY user_id (user_id)
) $charset_collate;";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

try {
    dbDelta($sql_activity);
    dbDelta($sql_invoices);
    
    echo json_encode([
        "success" => true,
        "message" => "Tablas personalizadas creadas o actualizadas con éxito.",
        "tables" => [$table_activity, $table_invoices]
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
