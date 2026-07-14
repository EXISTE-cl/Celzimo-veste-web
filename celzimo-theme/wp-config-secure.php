<?php
/**
 * Configuración de Seguridad y HTTPS para Celzimo Veste
 * 
 * Agrega estas líneas a tu archivo 'wp-config.php' en la raíz de tu instalación 
 * de WordPress, justo antes de la línea:
 * /* ¡Eso es todo, deja de editar! Feliz blogging. */
 */

// 1. Forzar HTTPS en la URL del sitio y el panel de administración
define( 'WP_HOME', 'https://' . $_SERVER['HTTP_HOST'] );
define( 'WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST'] );
define( 'FORCE_SSL_ADMIN', true );

// 2. Seguridad adicional para Cookies bajo HTTPS
if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
    $_SERVER['HTTPS'] = 'on';
}

// 3. Desactivar el editor de archivos en el panel de control (Evita inyecciones de código)
define( 'DISALLOW_FILE_EDIT', true );

// 4. Bloqueo de solicitudes externas no deseadas (opcional)
// define( 'WP_HTTP_BLOCK_EXTERNAL', true );
// define( 'WP_ACCESSIBLE_HOSTS', 'api.wordpress.org,photos.state.gov' );
