<?php
/**
 * Script to check WordPress pages and products
 */
require_once(__DIR__ . '/wp-load.php');

header('Content-Type: application/json');

$pages = get_pages();
$page_list = [];
foreach ($pages as $p) {
    $page_list[] = [
        'id' => $p->ID,
        'title' => $p->post_title,
        'slug' => $p->post_name,
        'status' => $p->post_status
    ];
}

$products = [];
if (class_exists('WooCommerce')) {
    $args = array(
        'status' => 'publish',
        'limit' => -1,
    );
    $wc_products = wc_get_products($args);
    foreach ($wc_products as $prod) {
        $products[] = [
            'id' => $prod->get_id(),
            'name' => $prod->get_name(),
            'sku' => $prod->get_sku(),
            'price' => $prod->get_price(),
            'regular_price' => $prod->get_regular_price(),
            'sale_price' => $prod->get_sale_price(),
            'image' => wp_get_attachment_image_url($prod->get_image_id(), 'full'),
            'categories' => wp_get_post_terms($prod->get_id(), 'product_cat', ['fields' => 'names'])
        ];
    }
}

echo json_encode([
    'success' => true,
    'pages' => $page_list,
    'products' => $products
], JSON_PRETTY_PRINT);
