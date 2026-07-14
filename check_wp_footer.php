<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') { die("No autorizado."); }

// Setup a mock checkout page request
global $post;
$checkout_page_id = wc_get_page_id('checkout');
$post = get_post($checkout_page_id);
setup_postdata($post);

ob_start();
wp_footer();
$footer = ob_get_clean();

if (strpos($footer, 'celzimo-redirect-telemetry') !== false) {
    echo "SUCCESS: Telemetry script is present in wp_footer!\n";
} else {
    echo "ERROR: Telemetry script is NOT present in wp_footer!\n";
    echo "Footer output length: " . strlen($footer) . "\n";
}
