<?php
/**
 * Reflect ThemeHigh class methods.
 */
require_once(__DIR__ . '/wp-load.php');

if (!isset($_GET['token']) || $_GET['token'] !== 'Csc170431Activation') {
    die("No autorizado.");
}

if (class_exists('THWCFD_Utils')) {
    echo "THWCFD_Utils METHODS:\n";
    $ref = new ReflectionClass('THWCFD_Utils');
    foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        echo "- " . $method->getName() . "\n";
    }
} else {
    echo "THWCFD_Utils does not exist.\n";
}
