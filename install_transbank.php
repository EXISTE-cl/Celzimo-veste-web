<?php
/**
 * Script: install_transbank.php
 * Descarga, instala y activa el plugin oficial de Transbank WebPay Plus
 * para WooCommerce, y lo configura en modo Integración (pruebas).
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

require_once(ABSPATH . 'wp-admin/includes/plugin.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/misc.php');
require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');

echo "=== INSTALACIÓN TRANSBANK WEBPAY PLUS ===\n\n";

// Slug y archivo principal del plugin (correcto según WordPress.org)
$plugin_slug  = 'transbank-webpay-plus-rest';
$plugin_file  = 'transbank-webpay-plus-rest/plugin.php';
$download_url = 'https://downloads.wordpress.org/plugin/transbank-webpay-plus-rest.latest-stable.zip';

$all_plugins = get_plugins();

// 1. Verificar si ya está instalado — buscar cualquier variante del slug
$found_file = null;
foreach ($all_plugins as $pf => $pd) {
    if (stripos($pf, 'transbank') !== false) {
        $found_file = $pf;
        echo "✓ Plugin ya instalado: {$pd['Name']} v{$pd['Version']} [{$pf}]\n";
        break;
    }
}

if (!$found_file) {
    echo "📦 Descargando plugin desde WordPress.org...\n";
    echo "   URL: {$download_url}\n";

    // Intentar primero via plugins_api
    $plugin_info = plugins_api('plugin_information', [
        'slug'   => $plugin_slug,
        'fields' => ['download_link' => true, 'short_description' => false, 'sections' => false],
    ]);

    if (!is_wp_error($plugin_info) && !empty($plugin_info->download_link)) {
        $download_url = $plugin_info->download_link;
        echo "   Plugin: {$plugin_info->name} v{$plugin_info->version}\n";
        echo "   URL actualizada: {$download_url}\n";
    } else {
        echo "   (Usando URL directa de descarga — fallback)\n";
    }

    $skin     = new WP_Ajax_Upgrader_Skin();
    $upgrader = new Plugin_Upgrader($skin);
    $result   = $upgrader->install($download_url);

    if (is_wp_error($result)) {
        echo "❌ Error durante instalación: " . $result->get_error_message() . "\n";
        exit;
    }

    if ($result === false) {
        $errors = $skin->get_errors();
        if (is_wp_error($errors) && $errors->has_errors()) {
            echo "❌ Error: " . $errors->get_error_message() . "\n";
        } else {
            echo "❌ Instalación falló sin mensaje de error específico.\n";
        }
        exit;
    }

    echo "✓ Plugin instalado correctamente.\n";

    // Refrescar lista de plugins
    $all_plugins = get_plugins();
    foreach ($all_plugins as $pf => $pd) {
        if (stripos($pf, 'transbank') !== false) {
            $found_file = $pf;
            echo "   Archivo del plugin: {$pf}\n";
            break;
        }
    }

    if (!$found_file) {
        echo "❌ No se encontró el plugin tras instalación. Verifica manualmente.\n";
        exit;
    }
}

// 2. Activar el plugin
$active_plugins = get_option('active_plugins', []);
if (!in_array($found_file, $active_plugins)) {
    $activation = activate_plugin($found_file);
    if (is_wp_error($activation)) {
        echo "❌ Error al activar: " . $activation->get_error_message() . "\n";
        exit;
    }
    echo "✓ Plugin activado correctamente.\n";
} else {
    echo "✓ Plugin ya estaba activo.\n";
}

// 3. Configurar en modo Integración (pruebas) con credenciales oficiales de Transbank
echo "\n⚙️  Configurando WebPay Plus en modo INTEGRACIÓN (pruebas)...\n";

// Opciones del plugin de Transbank (prefijo: woocommerce_transbank_webpay_plus_rest_)
$transbank_settings = [
    'enabled'          => 'yes',
    'title'            => 'Tarjeta de crédito o débito (WebPay)',
    'description'      => 'Paga de forma segura con tu tarjeta de crédito o débito a través de WebPay.',
    'environment'      => 'TEST',   // LIVE para producción
    'commerce_code'    => '597055555532',   // Código de prueba oficial Transbank
    'api_key_secret'   => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1',
];

update_option('woocommerce_transbank_webpay_plus_rest_settings', $transbank_settings);

echo "✓ Configuración guardada:\n";
foreach ($transbank_settings as $key => $value) {
    $display_val = ($key === 'api_key_secret') ? substr($value, 0, 10) . '...' : $value;
    echo "   {$key}: {$display_val}\n";
}

// 4. Verificar que quedó en WooCommerce payment gateways
$wc_gateways = WC()->payment_gateways();
echo "\n🔍 Verificando en WooCommerce gateways activos...\n";
$gateways = $wc_gateways->payment_gateways();
if (isset($gateways['transbank_webpay_plus_rest'])) {
    echo "✓ Transbank WebPay Plus aparece en gateways de WooCommerce.\n";
    echo "   Enabled: " . ($gateways['transbank_webpay_plus_rest']->enabled ?? 'N/A') . "\n";
} else {
    echo "⚠️  No aparece aún en gateways. Puede necesitar recarga de caché.\n";
}

// 5. Limpiar caché de WooCommerce
echo "\n🧹 Limpiando caché...\n";
wc_clear_cart_session();
if (function_exists('wc_delete_product_transients')) {
    wc_delete_product_transients();
}
echo "✓ Caché limpiada.\n";

echo "\n✅ PROCESO COMPLETADO\n";
echo "==========================================\n";
echo "Modo actual: INTEGRACIÓN (pruebas)\n";
echo "Para producción: actualiza environment=LIVE\n";
echo "   y usa tus credenciales reales de Transbank.\n";
echo "==========================================\n";
