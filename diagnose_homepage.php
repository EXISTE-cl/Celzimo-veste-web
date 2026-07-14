<?php
/**
 * Diagnose why the homepage is not showing the custom theme.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$results = [];

// 1. Check active theme
$theme = wp_get_theme();
$results['active_theme'] = $theme->get('Name') . ' (stylesheet: ' . get_stylesheet() . ', template: ' . get_template() . ')';

// 2. Check reading settings
$results['show_on_front']  = get_option('show_on_front');   // 'page' or 'posts'
$results['page_on_front']  = get_option('page_on_front');   // ID of the static front page
$results['page_for_posts'] = get_option('page_for_posts');  // ID of the blog page

// 3. Get all published pages
$pages = get_posts(['post_type' => 'page', 'numberposts' => 20, 'post_status' => 'publish']);
$results['pages'] = [];
foreach ($pages as $page) {
    $results['pages'][] = 'ID ' . $page->ID . ': ' . $page->post_title . ' (slug: ' . $page->post_name . ')';
}

// 4. Check if front-page.php exists in theme
$fp = get_template_directory() . '/front-page.php';
$results['front_page_php_exists'] = file_exists($fp) ? '✅ Existe' : '❌ NO existe';

// 5. Check if the "coming soon" redirect is still active
$cs_option = get_option('celzimo_under_construction', null);
$results['under_construction_option'] = $cs_option;

// 6. Check WP maintenance mode
$maint = file_exists(ABSPATH . '.maintenance');
$results['maintenance_file'] = $maint ? '⚠️ .maintenance file exists!' : 'No maintenance file';

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
