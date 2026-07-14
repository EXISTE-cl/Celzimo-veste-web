<?php
/**
 * Script de Importación de Productos Variables para WooCommerce
 * Celzimo Veste
 */

header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Variable') {
    die("Acceso no autorizado.");
}

echo "<h1>Importando Productos Variables a WooCommerce</h1>";

if (!class_exists('WooCommerce')) {
    die("Error: WooCommerce no está activo.");
}

function get_attachment_id_by_filename($url) {
    global $wpdb;
    $basename = basename($url);
    if (strpos($basename, '?') !== false) {
        $basename = explode('?', $basename)[0];
    }
    $attachment = $wpdb->get_col($wpdb->prepare(
        "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND guid LIKE %s",
        '%' . $wpdb->esc_like($basename) . '%'
    ));
    return !empty($attachment) ? (int)$attachment[0] : false;
}

$products_data = [
    [
        'sku' => 'JE57NE',
        'title' => 'JEANS RECTO EMILIA NEGRO',
        'brand' => 'Carola Miccono',
        'category' => 'jeans',
        'image' => 'carola_miconno-726p.jpg',
        'description' => 'Jeans Emilia en denim negro desgastado, 100% algodón, de calce recto y tiro medio. Su mezcla de texturas y lavado suave aporta un look contemporáneo y versátil, perfecto para usar durante todo el año. Un diseño cómodo, con estructura y un acabado deslavado que realza su carácter urbano y atemporal. Detalle de basta roll up. Modelo mide 1,67 y usa talla 36',
        'sizes' => ['40', '42'],
        'colors' => ['NEGRO'],
        'variants' => [
            [
                'sku' => 'JE57NE40',
                'price' => 33742,
                'compare_at_price' => 44990,
                'size' => '40',
                'color' => 'NEGRO',
                'stock' => 1
            ],
            [
                'sku' => 'JE57NE42',
                'price' => 33742,
                'compare_at_price' => 44990,
                'size' => '42',
                'color' => 'NEGRO',
                'stock' => 1
            ]
        ]
    ],
    [
        'sku' => 'JE40RA',
        'title' => 'Jeans culotte azul raw',
        'brand' => 'Carola Miccono',
        'category' => 'jeans',
        'image' => 'Jeans_raw_01.jpg',
        'description' => 'Calce Culotte 98,7%Algodón 1,3% Spandex, tiro alto ajustado en cadera, ancho en rodilla y bota de un largo medio que llega arriba del tobillo según el largo de pierna. Confeccionado en un denim elasticado y perfectamente lavado azul obscuro sin ningún tipo de desgaste .Se recomienda comprar la talla que utilizas frecuentemente. | Hecho en Chile.',
        'sizes' => ['40', '42', '46'],
        'colors' => ['Denim'],
        'variants' => [
            [
                'sku' => 'JE40RA40',
                'price' => 32242,
                'compare_at_price' => 42990,
                'size' => '40',
                'color' => 'Denim',
                'stock' => 1
            ],
            [
                'sku' => 'JE40RA42',
                'price' => 32242,
                'compare_at_price' => 42990,
                'size' => '42',
                'color' => 'Denim',
                'stock' => 1
            ],
            [
                'sku' => 'JE40RA46',
                'price' => 32242,
                'compare_at_price' => 42990,
                'size' => '46',
                'color' => 'Denim',
                'stock' => 1
            ]
        ]
    ],
    [
        'sku' => 'JE12GR',
        'title' => 'Jeans Wide Leg Stella New Gris',
        'brand' => 'Carola Miccono',
        'category' => 'jeans',
        'image' => 'jeans_stella_new_gris_01_f95c5ae0-5759-4646-8f89-d392673a49d5.jpg',
        'description' => 'Jeans pierna ancha recta, tiro alto. Diseñado y confeccionado en Chile | 100% Algodón',
        'sizes' => ['40', '44'],
        'colors' => ['GRIS'],
        'variants' => [
            [
                'sku' => 'JE12GR40',
                'price' => 33742,
                'compare_at_price' => 44990,
                'size' => '40',
                'color' => 'GRIS',
                'stock' => 1
            ],
            [
                'sku' => 'JE12GR44',
                'price' => 33742,
                'compare_at_price' => 44990,
                'size' => '44',
                'color' => 'GRIS',
                'stock' => 1
            ]
        ]
    ]
];

foreach ($products_data as $p) {
    echo "<h3>Procesando: " . htmlspecialchars($p['title']) . " (SKU: " . htmlspecialchars($p['sku']) . ")...</h3>";
    
    // 1. Buscar y borrar producto simple existente para evitar SKU duplicado
    $existing_id = wc_get_product_id_by_sku($p['sku']);
    if ($existing_id) {
        echo "- Eliminando producto simple anterior (ID: $existing_id) para migrar a variable...<br>";
        wp_delete_post($existing_id, true);
    }
    
    // 2. Crear producto variable
    $product = new WC_Product_Variable();
    $product->set_name($p['title']);
    $product->set_sku($p['sku']);
    $product->set_status('publish');
    $product->set_description($p['description']);
    
    // Configurar imagen destacada buscando en la biblioteca de medios
    $image_id = get_attachment_id_by_filename($p['image']);
    if ($image_id) {
        $product->set_image_id($image_id);
        echo "- Imagen vinculada de la galería (Media ID: $image_id).<br>";
    } else {
        echo "- <span style='color:orange;'>Imagen no encontrada en galería para: " . $p['image'] . "</span><br>";
    }
    
    // 3. Crear atributos de variación (Talla y Color)
    $attr_size = new WC_Product_Attribute();
    $attr_size->set_name('Talla');
    $attr_size->set_options($p['sizes']);
    $attr_size->set_position(0);
    $attr_size->set_visible(true);
    $attr_size->set_variation(true);
    
    $attr_color = new WC_Product_Attribute();
    $attr_color->set_name('Color');
    $attr_color->set_options($p['colors']);
    $attr_color->set_position(1);
    $attr_color->set_visible(true);
    $attr_color->set_variation(true);
    
    $product->set_attributes([$attr_size, $attr_color]);
    
    $parent_id = $product->save();
    echo "- Producto variable guardado con ID: $parent_id.<br>";
    
    // Asignar Categoría
    $category_slug = sanitize_title($p['category']);
    $category_term = get_term_by('slug', $category_slug, 'product_cat');
    if (!$category_term) {
        $new_term = wp_insert_term(ucfirst($p['category']), 'product_cat', ['slug' => $category_slug]);
        $term_id = !is_wp_error($new_term) ? $new_term['term_id'] : false;
    } else {
        $term_id = $category_term->term_id;
    }
    if ($term_id) {
        wp_set_object_terms($parent_id, (int)$term_id, 'product_cat');
    }
    
    // 4. Crear las Variaciones correspondientes
    foreach ($p['variants'] as $v) {
        $variation = new WC_Product_Variation();
        $variation->set_parent_id($parent_id);
        
        // El slug del atributo personalizado para la variación es el slug sanitizado en minúsculas
        $variation->set_attributes([
            'attribute_talla' => $v['size'],
            'attribute_color' => $v['color']
        ]);
        
        $variation->set_sku($v['sku']);
        $variation->set_regular_price($v['compare_at_price']);
        $variation->set_price($v['price']);
        $variation->set_sale_price($v['price']);
        $variation->set_manage_stock(true);
        $variation->set_stock_quantity($v['stock']);
        $variation->set_stock_status('instock');
        
        // Guardar variación
        $var_id = $variation->save();
        echo "- Creada variación SKU: " . htmlspecialchars($v['sku']) . " (Talla: " . $v['size'] . ", Color: " . $v['color'] . ") - ID: $var_id.<br>";
    }
    
    echo "<span style='color:green; font-weight:bold;'>✓ Producto variable importado completamente.</span><br><hr>";
}
?>
