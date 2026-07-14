<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') die("No autorizado.");

// Check for PHP errors in theme
$errors = error_get_last();
echo "LAST PHP ERROR: " . print_r($errors, true) . "\n";

// Check active theme
echo "ACTIVE THEME: " . get_stylesheet() . "\n";
echo "SHOW ON FRONT: " . get_option('show_on_front') . "\n";
echo "PAGE ON FRONT: " . get_option('page_on_front') . "\n";

// Check if front-page.php is syntactically valid
$fp = get_template_directory() . '/front-page.php';
echo "FRONT-PAGE.PHP: " . ($fp) . "\n";
echo "FILE EXISTS: " . (file_exists($fp) ? 'YES' : 'NO') . "\n";

// Check functions.php for errors
$fn = get_template_directory() . '/functions.php';
ob_start();
$result = shell_exec("php -l " . escapeshellarg($fn) . " 2>&1");
ob_end_clean();
echo "FUNCTIONS.PHP SYNTAX: " . ($result ?: 'shell_exec not available') . "\n";

// Try to parse functions.php manually
$content = file_get_contents($fn);
$token_result = token_get_all($content);
echo "FUNCTIONS.PHP TOKENS: " . count($token_result) . " tokens parsed OK\n";
