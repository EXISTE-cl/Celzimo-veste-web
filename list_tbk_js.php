<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(WP_CONTENT_DIR . '/plugins/transbank-webpay-plus-rest/'));
foreach ($it as $f) {
    if ($f->isFile() && $f->getExtension() === 'js') {
        echo $f->getPathname() . "\n";
    }
}
