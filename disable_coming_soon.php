<?php
/**
 * Script para desactivar el modo "Próximamente" de WooCommerce
 */

// Cargar WordPress
require_once(__DIR__ . '/wp-load.php');

// Verificar token de seguridad
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Live') {
    die("No autorizado.");
}

// Desactivar modo coming soon de WooCommerce
update_option('woocommerce_coming_soon', 'no');
update_option('woocommerce_store_status', 'live');

// Opcional: Limpiar cachés de WordPress si existieran plugins activos
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

echo "MODO_COMING_SOON_DESACTIVADO_EXITOSAMENTE";
