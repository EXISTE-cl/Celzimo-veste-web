<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

global $wpdb;
$results = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE '%_key%' OR option_name LIKE '%_token%' OR option_name LIKE '%credentials%'");
echo "=== CREDENCIALES ENCONTRADAS ===\n";
foreach ($results as $row) {
    $name = $row->option_name;
    $val = $row->option_value;
    $display = strlen($val) > 20 ? substr($val, 0, 5) . '... (' . strlen($val) . ' chars)' : $val;
    echo "$name: $display\n";
}
