<?php

/**
 * Helpers du contrôleur principal.
 */

/**
 * Retourne l'URL de base du back-office.
 */
function controller_getBaseUrl()
{
    $baseUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($baseUrl === null || $baseUrl === '') {
        return '/php/index.php';
    }

    return $baseUrl;
}

/**
 * Redirection simple vers l'URL de base avec query string optionnelle.
 */
function controller_redirect($baseUrl, $query = '')
{
    header('Location: ' . $baseUrl . $query);
    exit;
}

/**
 * Retourne une valeur POST sous forme de chaîne.
 */
function controller_postString($key)
{
    return isset($_POST[$key]) ? (string) $_POST[$key] : '';
}

