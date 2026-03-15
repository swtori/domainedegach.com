<?php

/**
 * Point d'entrée : charge le controller qui gère la requête et la vue.
 */

// Débogage 500 : afficher l'erreur PHP (à retirer en production)
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/Controller/requests.php';
