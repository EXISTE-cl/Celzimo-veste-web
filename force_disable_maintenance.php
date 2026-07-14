<?php
/**
 * Quick fix: ensure site is fully public.
 * Disable any coming soon plugins that might still be active.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$results = [];

// 1. Deactivate FunnelBuilder (it may have coming soon mode)
$funnel = 'funnel-builder/funnel-builder.php';
if (is_plugin_active($funnel)) {
    deactivate_plugins($funnel);
    $results['funnel_builder'] = '🔇 FunnelBuilder desactivado (podría tener coming soon)';
} else {
    $results['funnel_builder'] = 'FunnelBuilder ya estaba inactivo';
}

// 2. Check/disable any coming soon specific options
$cs_options = [
    'seedprod_coming_soon_page_id',
    'seedprod_maintenance_mode',
    'seedprod_coming_soon_mode',
    'under_construction_activate',
    'under_construction_mode',
    'maintenance_mode_enable',
    'wp_maintenance_mode',
];
foreach ($cs_options as $opt) {
    $val = get_option($opt, null);
    if ($val !== null && $val !== false && $val !== '0' && $val !== 0) {
        update_option($opt, 0);
        $results['disabled'][] = $opt . ' (was: ' . $val . ')';
    }
}

// 3. Confirm reading settings
$results['show_on_front']  = get_option('show_on_front');
$results['page_on_front']  = get_option('page_on_front');

// 4. Confirm active plugins
$results['active_plugins'] = get_option('active_plugins');

// 5. Delete .maintenance file if exists
$maint_file = ABSPATH . '.maintenance';
if (file_exists($maint_file)) {
    unlink($maint_file);
    $results['maintenance_file'] = '✅ Archivo .maintenance eliminado';
} else {
    $results['maintenance_file'] = 'Sin archivo .maintenance';
}

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
