<?php
define('DB_NAME', 'dffeecfb_celzimo');
define('DB_USER', 'dffeecfb_celzimo');
define('DB_PASSWORD', 'Csc170431*');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

define('AUTH_KEY', '236fdbc0b667c2c2c56be7db6c07e99ec08892aa9a9dc470cee28d612d36cc25');
define('SECURE_AUTH_KEY', '26c7ac7f316fc95eb0814156458d2056dc1ff113021a7b7e06c59638abe27cd4');
define('LOGGED_IN_KEY', 'f65f8c07f19bfbe539de42c59ca397ba47479471e5432ef7f3418e2688013c1a');
define('NONCE_KEY', '0276052545f7fcf6ece0f372ddccabcb086cb6352c6d2026631fddbac6b75e37');
define('AUTH_SALT', 'e2f1d47b31a0ea7a493a2642d7983fec759d9ccf70fa576f2ea60e7953b75d11');
define('SECURE_AUTH_SALT', 'd119a062473b7b0daaf04b70a77af8887fcb4c4a594a7a31d12a9bc82709ce03');
define('LOGGED_IN_SALT', 'f5d230bacb1961479fc6523bfc50ea5dc116355f0bfeb39947708a41422fd213');
define('NONCE_SALT', '3b6eba1af27e149915b78c2a5a2abbfaf7fe63aa941f2c7a091cde32961c2837');

$table_prefix = 'cz_';

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';


// Forzar HTTPS y redirecciones en URLs de WordPress
define( 'WP_HOME', 'https://' . $_SERVER['HTTP_HOST'] );
define( 'WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST'] );
define( 'FORCE_SSL_ADMIN', true );
if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
    $_SERVER['HTTPS'] = 'on';
}
define( 'DISALLOW_FILE_EDIT', true );
