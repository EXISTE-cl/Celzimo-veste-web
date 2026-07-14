<?php
/**
 * Script de activación de tema programática
 */

// Cargar WordPress
require_once(__DIR__ . '/wp-load.php');

// Verificar token de seguridad
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

// Activar tema
switch_theme('celzimo-theme');

echo "TEMA_ACTIVADO_EXITOSAMENTE";
