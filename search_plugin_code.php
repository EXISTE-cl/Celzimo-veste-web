<?php
/**
 * Search the plugin code for option names and structures.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

$dir = WP_PLUGIN_DIR . '/woo-checkout-field-editor-pro';

function search_in_dir($dir, $pattern) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $file) {
        if ($file->isDir()) continue;
        if (pathinfo($file->getPathname(), PATHINFO_EXTENSION) !== 'php') continue;
        
        $content = file_get_contents($file->getPathname());
        if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
            echo "File: " . str_replace(WP_PLUGIN_DIR, '', $file->getPathname()) . "\n";
            foreach ($matches[0] as $match) {
                // Print a small snippet around the match
                $offset = $match[1];
                $snippet = substr($content, max(0, $offset - 100), 200);
                echo "  Match: " . trim($match[0]) . "\n";
                echo "  Snippet: ... " . esc_html(str_replace("\n", " ", $snippet)) . " ...\n";
            }
            echo "--------------------------------------------------\n";
        }
    }
}

echo "SEARCH FOR update_option:\n";
search_in_dir($dir, '/update_option\s*\(\s*[\'"][^\'"]+[\'"]/i');
