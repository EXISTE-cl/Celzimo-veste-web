<?php
/**
 * Configure WooCommerce & ThemeHigh checkout fields.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

if (!class_exists('THWCFD_Utils')) {
    die("THWCFD_Utils not found.");
}

// Ensure default WooCommerce settings are Chile / CLP
update_option('woocommerce_default_country', 'CL');
update_option('woocommerce_currency', 'CLP');
update_option('woocommerce_price_num_decimals', 0);

$shipping_fields = THWCFD_Utils::get_fields('shipping');
$billing_fields = THWCFD_Utils::get_fields('billing');

function customize_fields_array(&$fields, $prefix) {
    // 1. Nombre Completo (override first_name)
    if (isset($fields[$prefix . '_first_name'])) {
        $fields[$prefix . '_first_name']['label'] = 'Nombre Completo';
        $fields[$prefix . '_first_name']['class'] = array('form-row-wide');
        $fields[$prefix . '_first_name']['required'] = 1;
        $fields[$prefix . '_first_name']['priority'] = 10;
    }
    
    // Disable last name (make it not required and disabled)
    if (isset($fields[$prefix . '_last_name'])) {
        $fields[$prefix . '_last_name']['enabled'] = 0;
        $fields[$prefix . '_last_name']['required'] = 0;
    }
    
    // 2. Teléfono +56 (custom phone field configuration)
    if (isset($fields[$prefix . '_phone'])) {
        $fields[$prefix . '_phone']['label'] = 'Teléfono';
        $fields[$prefix . '_phone']['placeholder'] = '+56 9 1234 5678';
        $fields[$prefix . '_phone']['required'] = 1;
        $fields[$prefix . '_phone']['class'] = array('form-row-wide');
        $fields[$prefix . '_phone']['priority'] = 20;
    } else {
        // If shipping_phone doesn't exist, we add it
        $fields[$prefix . '_phone'] = array(
            'type' => 'tel',
            'label' => 'Teléfono',
            'placeholder' => '+56 9 1234 5678',
            'required' => 1,
            'class' => array('form-row-wide'),
            'validate' => array('phone'),
            'custom' => 1,
            'enabled' => 1,
            'show_in_email' => 1,
            'show_in_order' => 1,
            'priority' => 20
        );
    }
    
    // 3. Country (CL by default)
    if (isset($fields[$prefix . '_country'])) {
        $fields[$prefix . '_country']['default'] = 'CL';
        $fields[$prefix . '_country']['enabled'] = 1;
        $fields[$prefix . '_country']['priority'] = 30;
    }

    // 4. Region (State)
    if (isset($fields[$prefix . '_state'])) {
        $fields[$prefix . '_state']['label'] = 'Región';
        $fields[$prefix . '_state']['required'] = 1;
        $fields[$prefix . '_state']['class'] = array('form-row-wide');
        $fields[$prefix . '_state']['priority'] = 40;
    }

    // 5. Comuna (City)
    if (isset($fields[$prefix . '_city'])) {
        $fields[$prefix . '_city']['label'] = 'Comuna';
        $fields[$prefix . '_city']['required'] = 1;
        $fields[$prefix . '_city']['class'] = array('form-row-wide');
        $fields[$prefix . '_city']['priority'] = 50;
    }

    // 6. Dirección (Address 1)
    if (isset($fields[$prefix . '_address_1'])) {
        $fields[$prefix . '_address_1']['label'] = 'Dirección (Calle, número, depto/casa)';
        $fields[$prefix . '_address_1']['placeholder'] = 'Ej: Av. Providencia 1234, Depto 41';
        $fields[$prefix . '_address_1']['required'] = 1;
        $fields[$prefix . '_address_1']['class'] = array('form-row-wide');
        $fields[$prefix . '_address_1']['priority'] = 60;
    }
    
    // Disable Address 2 (since Address 1 is configured to contain everything)
    if (isset($fields[$prefix . '_address_2'])) {
        $fields[$prefix . '_address_2']['enabled'] = 0;
        $fields[$prefix . '_address_2']['required'] = 0;
    }

    // Disable Postcode (Código Postal)
    if (isset($fields[$prefix . '_postcode'])) {
        $fields[$prefix . '_postcode']['enabled'] = 0;
        $fields[$prefix . '_postcode']['required'] = 0;
    }

    // 7. Add Boleta/Factura radio button (custom cards field)
    $fields[$prefix . '_tipo_documento'] = array(
        'type' => 'radio',
        'label' => 'Tipo de Documento',
        'placeholder' => '',
        'required' => 1,
        'class' => array('form-row-wide', 'tipo-documento-cards'),
        'options' => array(
            'boleta' => 'Boleta',
            'factura' => 'Factura'
        ),
        'default' => 'boleta',
        'custom' => 1,
        'enabled' => 1,
        'show_in_email' => 1,
        'show_in_order' => 1,
        'priority' => 70,
        'index' => 1110
    );
}

customize_fields_array($shipping_fields, 'shipping');
customize_fields_array($billing_fields, 'billing');

// Save changes back to database options
THWCFD_Utils::update_fields('shipping', $shipping_fields);
THWCFD_Utils::update_fields('billing', $billing_fields);

echo "FIELDS_CONFIGURED_SUCCESSFULLY";
