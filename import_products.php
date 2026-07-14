<?php
/**
 * Script de Importación Temporal de Productos para WooCommerce
 * Celzimo Veste
 */

// Definir cabecera para ver los resultados en tiempo real
header('Content-Type: text/html; charset=utf-8');

// Forzar visualización de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar WordPress
$wp_load_path = __DIR__ . '/wp-load.php';
if (!file_exists($wp_load_path)) {
    die("Error: No se pudo cargar wp-load.php. Este script debe estar en la raíz de tu sitio de WordPress.");
}
require_once($wp_load_path);

// Verificar token de seguridad de migración automatizada
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Migration') {
    die("Acceso no autorizado.");
}

echo "<h1>Migración del Catálogo de Productos a WooCommerce</h1>";

// Verificar si WooCommerce está activo
if (!class_exists('WooCommerce')) {
    die("<div style='color:red; font-weight:bold; padding:10px; border:1px solid red; background:#ffebeb;'>WooCommerce no está activo. Por favor, activa WooCommerce en el panel de plugins antes de ejecutar la migración de productos.</div>");
}

// Datos de productos extraídos de data.js
$products_data = [
    [
        'sku' => 'JE57NE',
        'title' => 'JEANS RECTO EMILIA NEGRO',
        'brand' => 'Carola Miccono',
        'price' => '33742',
        'compare_at_price' => '44990',
        'category' => 'jeans',
        'image' => 'https://cdn.shopify.com/s/files/1/0884/6534/2763/files/carola_miconno-726p.jpg?v=1764774426',
        'description' => 'Jeans Emilia en denim negro desgastado, 100% algodón, de calce recto y tiro medio. Su mezcla de texturas y lavado suave aporta un look contemporáneo y versátil, perfecto para usar durante todo el año. Un diseño cómodo, con estructura y un acabado deslavado que realza su carácter urbano y atemporal. Detalle de basta roll up. Modelo mide 1,67 y usa talla 36'
    ],
    [
        'sku' => 'JE40RA',
        'title' => 'Jeans culotte azul raw',
        'brand' => 'Carola Miccono',
        'price' => '32242',
        'compare_at_price' => '42990',
        'category' => 'jeans',
        'image' => 'https://cdn.shopify.com/s/files/1/0884/6534/2763/files/Jeans_raw_01.jpg?v=1734705515',
        'description' => 'Calce Culotte 98,7%Algodón 1,3% Spandex, tiro alto ajustado en cadera, ancho en rodilla y bota de un largo medio que llega arriba del tobillo según el largo de pierna. Confeccionado en un denim elasticado y perfectamente lavado azul obscuro sin ningún tipo de desgaste .Se recomienda comprar la talla que utilizas frecuentemente. | Hecho en Chile.'
    ],
    [
        'sku' => 'JE12GR',
        'title' => 'Jeans Wide Leg Stella New Gris',
        'brand' => 'Carola Miccono',
        'price' => '33742',
        'compare_at_price' => '44990',
        'category' => 'jeans',
        'image' => 'https://cdn.shopify.com/s/files/1/0884/6534/2763/files/jeans_stella_new_gris_01_f95c5ae0-5759-4646-8f89-d392673a49d5.jpg?v=1734706061',
        'description' => 'Jeans pierna ancha recta, tiro alto. Diseñado y confeccionado en Chile | 100% Algodón'
    ]
];

// Incluir librerías necesarias de administración para sideload de imágenes
require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');

foreach ($products_data as $p) {
    echo "<h3>Procesando: " . htmlspecialchars($p['title']) . " (SKU: " . htmlspecialchars($p['sku']) . ")...</h3>";
    
    // 1. Verificar si el producto ya existe por SKU
    $product_id = wc_get_product_id_by_sku($p['sku']);
    
    if ($product_id) {
        echo "<span style='color:orange;'>El producto ya existe (ID: $product_id). Saltando creación.</span><br>";
        continue;
    }
    
    // 2. Crear producto nuevo
    $product = new WC_Product_Simple();
    $product->set_name($p['title']);
    $product->set_sku($p['sku']);
    $product->set_status('publish');
    $product->set_description($p['description']);
    
    // Precios
    $product->set_regular_price($p['compare_at_price']);
    $product->set_sale_price($p['price']);
    $product->set_price($p['price']);
    
    // Stock e inventario
    $product->set_manage_stock(true);
    $product->set_stock_quantity(10);
    $product->set_stock_status('instock');
    
    // Guardar para obtener el ID antes de la imagen y categorías
    $new_id = $product->save();
    
    if ($new_id) {
        echo "<span style='color:green; font-weight:bold;'>✓ Creado exitosamente con ID: $new_id.</span><br>";
        
        // 3. Asignar categoría
        $category_slug = sanitize_title($p['category']);
        $category_term = get_term_by('slug', $category_slug, 'product_cat');
        
        if (!$category_term) {
            $new_term = wp_insert_term(ucfirst($p['category']), 'product_cat', [
                'slug' => $category_slug
            ]);
            if (!is_wp_error($new_term)) {
                $term_id = $new_term['term_id'];
            } else {
                $term_id = false;
            }
        } else {
            $term_id = $category_term->term_id;
        }
        
        if ($term_id) {
            wp_set_object_terms($new_id, (int)$term_id, 'product_cat');
            echo "- Categoría '" . htmlspecialchars($p['category']) . "' asignada.<br>";
        }
        
        // 4. Registrar Marca como atributo del producto
        wp_set_object_terms($new_id, 'Carola Miccono', 'pa_brand'); // Atributo de marca si aplica
        
        // 5. Descargar y adjuntar imagen desde CDN (Comentado para evitar Timeouts 503 en hosting compartido)
        /*
        echo "- Descargando imagen destacada desde CDN Shopify...<br>";
        $image_url = $p['image'];
        $image_id = media_sideload_image($image_url, $new_id, $p['title'], 'id');
        
        if (!is_wp_error($image_id) && is_numeric($image_id)) {
            set_post_thumbnail($new_id, $image_id);
            echo "<span style='color:green;'>- Imagen descargada e insertada como destacada (Media ID: $image_id).</span><br>";
        } else {
            $err_msg = is_wp_error($image_id) ? $image_id->get_error_message() : "Error desconocido";
            echo "<span style='color:red;'>- No se pudo asociar la imagen destacada: $err_msg. Se usará el placeholder.</span><br>";
        }
        */
        echo "- Omitida descarga remota de imagen (evita timeout). Puedes asignarla manualmente o mediante el importador CSV.<br>";
    } else {
        echo "<span style='color:red; font-weight:bold;'>✗ Error al guardar el producto.</span><br>";
    }
    
    echo "<hr>";
}

echo "<h2>¡Migración del catálogo completada!</h2>";
echo "<p style='color:green; font-weight:bold;'>Ahora puedes borrar este archivo (import_products.php) de la raíz de tu sitio por motivos de seguridad.</p>";
