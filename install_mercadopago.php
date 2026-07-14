<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

echo "Instalando Mercado Pago...\n";
$output = shell_exec('wp plugin install woocommerce-mercadopago --activate');
echo $output;
