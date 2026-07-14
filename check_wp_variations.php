<?php
/**
 * Script to check WordPress product variations and attributes
 */
require_once(__DIR__ . '/wp-load.php');

header('Content-Type: application/json');

$products = [];
if (class_exists('WooCommerce')) {
    $args = array(
        'status' => 'publish',
        'limit' => -1,
    );
    $wc_products = wc_get_products($args);
    foreach ($wc_products as $prod) {
        $attributes_data = [];
        foreach ($prod->get_attributes() as $attr_slug => $attr) {
            $attributes_data[$attr_slug] = [
                'name' => $attr->get_name(),
                'options' => $attr->get_options(),
                'is_taxonomy' => $attr->is_taxonomy()
            ];
        }

        $variations_data = [];
        if ($prod->is_type('variable')) {
            $variations = $prod->get_available_variations();
            foreach ($variations as $var) {
                $variations_data[] = [
                    'id' => $var['variation_id'],
                    'attributes' => $var['attributes'],
                    'display_price' => $var['display_price'],
                    'display_regular_price' => $var['display_regular_price'],
                    'max_qty' => $var['max_qty'],
                    'is_in_stock' => $var['is_in_stock']
                ];
            }
        }

        $products[] = [
            'id' => $prod->get_id(),
            'name' => $prod->get_name(),
            'sku' => $prod->get_sku(),
            'type' => $prod->get_type(),
            'attributes' => $attributes_data,
            'variations' => $variations_data
        ];
    }
}

echo json_encode([
    'success' => true,
    'products' => $products
], JSON_PRETTY_PRINT);
