<?php
/**
 * Fix WordPress reading settings:
 * 1. Create a "Inicio" static page if it doesn't exist
 * 2. Set it as the front page (show_on_front = page)
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$results = [];

// 1. Check if a "Inicio" page already exists
$home_page = get_page_by_path('inicio');
if (!$home_page) {
    // Create it
    $page_id = wp_insert_post([
        'post_title'   => 'Inicio',
        'post_name'    => 'inicio',
        'post_content' => '',
        'post_status'  => 'publish',
        'post_type'    => 'page',
    ]);
    $results['page_created'] = 'Página "Inicio" creada con ID ' . $page_id;
} else {
    $page_id = $home_page->ID;
    $results['page_found'] = 'Página "Inicio" ya existe con ID ' . $page_id;
}

// 2. Set reading settings: static front page
update_option('show_on_front', 'page');
update_option('page_on_front', $page_id);
update_option('page_for_posts', 0);

$results['show_on_front']  = get_option('show_on_front');
$results['page_on_front']  = get_option('page_on_front');

// 3. Flush rewrite rules
flush_rewrite_rules();
$results['rewrite_flushed'] = '✅ Reglas de reescritura actualizadas';

// 4. Make sure Under Construction mode is OFF
update_option('celzimo_under_construction', 0);
delete_option('celzimo_under_construction');
$results['under_construction'] = '✅ Modo under construction desactivado';

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
