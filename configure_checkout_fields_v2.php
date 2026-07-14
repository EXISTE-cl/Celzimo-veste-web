<?php
/**
 * Update checkout fields in ThemeHigh database settings.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

if (!class_exists('THWCFD_Utils')) {
    die("THWCFD_Utils not found.");
}

$shipping_fields = THWCFD_Utils::get_fields('shipping');
$billing_fields = THWCFD_Utils::get_fields('billing');

function customize_fields_array_v2(&$fields, $prefix) {
    // 1. Nombre Completo (left column, 50% width)
    if (isset($fields[$prefix . '_first_name'])) {
        $fields[$prefix . '_first_name']['label'] = 'Nombre Completo';
        $fields[$prefix . '_first_name']['placeholder'] = 'Juan Pérez';
        $fields[$prefix . '_first_name']['class'] = array('form-row-first'); // 50% width left
        $fields[$prefix . '_first_name']['required'] = 1;
        $fields[$prefix . '_first_name']['priority'] = 10;
    }
    
    // Disable last name
    if (isset($fields[$prefix . '_last_name'])) {
        $fields[$prefix . '_last_name']['enabled'] = 0;
        $fields[$prefix . '_last_name']['required'] = 0;
    }
    
    // 2. Teléfono de contacto (right column, 50% width)
    if (isset($fields[$prefix . '_phone'])) {
        $fields[$prefix . '_phone']['label'] = 'Teléfono de contacto';
        $fields[$prefix . '_phone']['placeholder'] = '+56';
        $fields[$prefix . '_phone']['required'] = 1;
        $fields[$prefix . '_phone']['class'] = array('form-row-last'); // 50% width right
        $fields[$prefix . '_phone']['priority'] = 20;
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

    // 6. Dirección de Despacho (Address 1)
    if (isset($fields[$prefix . '_address_1'])) {
        $fields[$prefix . '_address_1']['label'] = 'Dirección de Despacho';
        $fields[$prefix . '_address_1']['placeholder'] = 'Av. Providencia 1234, Depto 402';
        $fields[$prefix . '_address_1']['required'] = 1;
        $fields[$prefix . '_address_1']['class'] = array('form-row-wide');
        $fields[$prefix . '_address_1']['priority'] = 60;
    }
    
    // Disable Address 2
    if (isset($fields[$prefix . '_address_2'])) {
        $fields[$prefix . '_address_2']['enabled'] = 0;
        $fields[$prefix . '_address_2']['required'] = 0;
    }

    // Disable Postcode
    if (isset($fields[$prefix . '_postcode'])) {
        $fields[$prefix . '_postcode']['enabled'] = 0;
        $fields[$prefix . '_postcode']['required'] = 0;
    }

    // 7. Radio button Tipo de Documento
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

customize_fields_array_v2($shipping_fields, 'shipping');
customize_fields_array_v2($billing_fields, 'billing');

THWCFD_Utils::update_fields('shipping', $shipping_fields);
THWCFD_Utils::update_fields('billing', $billing_fields);

echo "FIELDS_V2_CONFIGURED_SUCCESSFULLY";
