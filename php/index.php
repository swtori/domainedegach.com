<?php

/**
 * Point d'entrée : charge le controller qui gère la requête et la vue.
 */

// Session sécurisée (avant tout affichage)
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(array(
        'httponly' => true,
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ));
    session_start();
}

require_once __DIR__ . '/Controller/requests.php';
