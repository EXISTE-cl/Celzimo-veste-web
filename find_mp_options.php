<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

global $wpdb;
$results = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE option_name LIKE '%mercadopago%' OR option_name LIKE '%mp_%'");
echo "=== OPCIONES ENCONTRADAS ===\n";
foreach ($results as $row) {
    $name = $row->option_name;
    $val = get_option($name);
    // Enmascarar valores largos o que parezcan claves
    if (is_array($val) || is_object($val)) {
        $display = 'Array/Object (' . serialize($val) . ')';
    } else {
        $display = strlen($val) > 20 ? substr($val, 0, 5) . '... (' . strlen($val) . ' chars)' : $val;
    }
    echo "$name: $display\n";
}
