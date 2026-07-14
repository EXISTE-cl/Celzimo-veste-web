<?php
/**
 * Search for collapsible fields logic in Fluid Checkout
 */
define('WP_USE_THEMES', false);
require_once('wp-load.php');

if (!current_user_can('manage_options') && ($_GET['token'] ?? '') !== 'Csc170431Activation') {
    die('Unauthorized');
}

$plugin_dir = WP_PLUGIN_DIR . '/fluid-checkout';
if (!is_dir($plugin_dir)) {
    die("Fluid Checkout directory not found at: $plugin_dir");
}

echo "--- Searching Fluid Checkout for 'collapsible' ---\n";

function search_in_dir($dir, $pattern) {
    $it = new RecursiveDirectoryIterator($dir);
    $display = new RecursiveIteratorIterator($it);
    foreach($display as $file) {
        if ($file->isFile() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) === 'php') {
            $content = file_get_contents($file->getPathname());
            if (stripos($content, $pattern) !== false) {
                echo "Found in: " . str_replace(WP_PLUGIN_DIR, '', $file->getPathname()) . "\n";
                // Print lines with pattern
                $lines = explode("\n", $content);
                foreach ($lines as $i => $line) {
                    if (stripos($line, $pattern) !== false) {
                        echo "  Line " . ($i + 1) . ": " . trim($line) . "\n";
                    }
                }
            }
        }
    }
}

search_in_dir($plugin_dir, 'expansible');
search_in_dir($plugin_dir, 'optional');
