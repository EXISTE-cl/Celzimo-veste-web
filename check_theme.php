<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$theme = wp_get_theme();
echo "Active Theme: " . $theme->get('Name') . " (Folder: " . $theme->get_stylesheet() . ")\n";
