<?php
/**
 * Search Fluid Checkout progress bar.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$dir = WP_PLUGIN_DIR . '/fluid-checkout';

function search_in_dir($dir, $pattern) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $file) {
        if ($file->isDir()) continue;
        if (pathinfo($file->getPathname(), PATHINFO_EXTENSION) !== 'php') continue;
        
        $content = file_get_contents($file->getPathname());
        if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
            echo "File: " . str_replace(WP_PLUGIN_DIR, '', $file->getPathname()) . "\n";
            foreach ($matches[0] as $match) {
                $offset = $match[1];
                $snippet = substr($content, max(0, $offset - 100), 200);
                echo "  Snippet: ... " . esc_html(str_replace("\n", " ", $snippet)) . " ...\n";
            }
            echo "--------------------------------------------------\n";
        }
    }
}

search_in_dir($dir, '/progress/i');
