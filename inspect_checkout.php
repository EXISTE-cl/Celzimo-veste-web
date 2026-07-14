<?php
require_once('wp-load.php');

if ( function_exists('WC') ) {
    ob_start();
    echo do_shortcode('[woocommerce_checkout]');
    $html = ob_get_clean();

    echo "RAW HTML:\n";
    echo $html;
}
?>
