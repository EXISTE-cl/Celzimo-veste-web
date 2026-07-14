<?php
/**
 * Script to fetch regions and comunas of Chile from DPA API or another source
 */
define('WP_USE_THEMES', false);
require_once('wp-load.php');

if (!current_user_can('manage_options') && ($_GET['token'] ?? '') !== 'Csc170431Activation') {
    die('Unauthorized');
}

echo "--- Fetching Regions and Comunas of Chile ---\n";

// Let's try to get regions and comunas from DPA API
$regions_url = 'https://apis.modernizacion.cl/dpa/regiones';
$response = wp_remote_get($regions_url, ['sslverify' => false, 'timeout' => 15]);

if (is_wp_error($response)) {
    echo "WP Error fetching regions: " . $response->get_error_message() . "\n";
    
    // Let's try an alternative source if DPA is down
    $alt_url = 'https://raw.githubusercontent.com/gonzalo-infante/regiones-y-comunas-de-chile/master/regiones-y-comunas-de-chile.json'; // wait, master might be main, let's try it
    echo "Trying alternative github URL...\n";
    $response = wp_remote_get('https://raw.githubusercontent.com/gonzalo-infante/regiones-y-comunas-de-chile/master/regiones-y-comunas-de-chile.json', ['sslverify' => false]);
    if (is_wp_error($response)) {
        echo "WP Error alt: " . $response->get_error_message() . "\n";
    } else {
        echo "Alt Response Code: " . wp_remote_retrieve_response_code($response) . "\n";
        echo substr(wp_remote_retrieve_body($response), 0, 500) . "\n";
    }
} else {
    $code = wp_remote_retrieve_response_code($response);
    echo "Response Code: $code\n";
    $body = wp_remote_retrieve_body($response);
    $regions = json_decode($body, true);
    
    if (empty($regions)) {
        echo "Failed to decode regions JSON. Body: " . substr($body, 0, 200) . "\n";
    } else {
        echo "Successfully fetched " . count($regions) . " regions.\n";
        // Fetch comunas for each region
        $data = [];
        foreach ($regions as $r) {
            $reg_code = $r['codigo'];
            $reg_name = $r['nombre'];
            echo "Fetching comunas for $reg_name ($reg_code)...\n";
            $comunas_url = "https://apis.modernizacion.cl/dpa/regiones/$reg_code/comunas";
            $c_response = wp_remote_get($comunas_url, ['sslverify' => false, 'timeout' => 15]);
            if (!is_wp_error($c_response)) {
                $c_body = wp_remote_retrieve_body($c_response);
                $comunas = json_decode($c_body, true);
                if (!empty($comunas)) {
                    $comunas_list = [];
                    foreach ($comunas as $c) {
                        $comunas_list[] = $c['nombre'];
                    }
                    sort($comunas_list);
                    $data[$reg_name] = $comunas_list;
                }
            }
        }
        
        echo "RESULTING DATA:\n";
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
        
        // Write to a local file in the uploads dir or theme
        $theme_dir = get_stylesheet_directory();
        file_put_contents($theme_dir . '/chile-regions-comunas.json', json_encode($data, JSON_UNESCAPED_UNICODE));
        echo "Saved to theme/chile-regions-comunas.json\n";
    }
}
