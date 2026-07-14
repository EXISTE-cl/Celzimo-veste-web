<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') die("No autorizado.");

// Limpiar todos los cachés
wp_cache_flush();

// Limpiar caché de transients
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_%'");

// Flush rewrite rules
flush_rewrite_rules(true);

// Confirmar configuración final
echo "CACHE LIMPIADO\n";
echo "THEME: " . get_stylesheet() . "\n";
echo "SHOW_ON_FRONT: " . get_option('show_on_front') . "\n";
echo "PAGE_ON_FRONT: " . get_option('page_on_front') . "\n";
echo "HOME_URL: " . home_url() . "\n";
echo "FRONT-PAGE.PHP: " . (file_exists(get_template_directory() . '/front-page.php') ? 'OK' : 'MISSING') . "\n";
echo "INDEX.PHP: " . (file_exists(get_template_directory() . '/index.php') ? 'OK' : 'MISSING') . "\n";
echo "LISTO\n";
