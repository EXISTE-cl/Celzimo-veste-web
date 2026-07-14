<?php
require_once(__DIR__ . '/wp-load.php');
if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') die("No autorizado.");

// Simulate visiting the homepage
$GLOBALS['wp']->parse_request();
$GLOBALS['wp']->query_posts();
$GLOBALS['wp']->register_globals();

echo "IS_FRONT_PAGE: " . (is_front_page() ? 'YES' : 'NO') . "\n";
echo "IS_HOME: " . (is_home() ? 'YES' : 'NO') . "\n";
echo "QUERIED OBJECT: " . print_r(get_queried_object(), true) . "\n";

// Check what template WordPress would use
$template = get_template_directory();
echo "\nTemplate hierarchy for front page:\n";
$candidates = [
    $template . '/front-page.php',
    $template . '/home.php', 
    $template . '/index.php',
];
foreach ($candidates as $c) {
    echo basename($c) . ': ' . (file_exists($c) ? 'EXISTS' : 'missing') . "\n";
}

// The real issue: page ID 94 might not be triggering front-page.php
// front-page.php triggers when: show_on_front=page AND page_on_front is set
echo "\nshow_on_front: " . get_option('show_on_front') . "\n";
echo "page_on_front: " . get_option('page_on_front') . "\n";

// Check if homepage URL resolves correctly  
echo "home_url: " . home_url() . "\n";
echo "site_url: " . site_url() . "\n";
