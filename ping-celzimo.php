<?php
header('Content-Type: text/plain');
echo "PHP_OK\n";
echo "mysqli=" . (class_exists('mysqli') ? 'yes' : 'no') . "\n";
