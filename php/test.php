<?php
/**
 * Test minimal : vérifie que PHP répond. Supprimer après débogage.
 */
header('Content-Type: text/plain; charset=utf-8');
ini_set('display_errors', '1');
error_reporting(E_ALL);
echo "OK - PHP fonctionne\n";
echo "PHP " . PHP_VERSION . "\n";
