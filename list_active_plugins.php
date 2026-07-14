<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$plugins = get_option('active_plugins');
foreach ($plugins as $plugin) {
    echo $plugin . "\n";
}
