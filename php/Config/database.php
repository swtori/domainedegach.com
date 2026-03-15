<?php

/**
 * Config : accès base de données (connexion PDO/MySQL), paramètres, constantes.
 */

define('DB_HOST', 'db5019965364.hosting-data.io');
define('DB_PORT', 3306);
define('DB_NAME', 'dbGach');
define('DB_USER', 'dbu1225268');
define('DB_CHARSET', 'utf8mb4');

// Mot de passe : à renseigner (idéalement via variable d'environnement en prod, ne pas commiter)
define('DB_PASS', '');

/**
 * Retourne une instance PDO ou null si la connexion échoue.
 *
 * @return PDO|null
 */
function getPdo()
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    try {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_PORT,
            DB_NAME,
            DB_CHARSET
        );
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        $pdo = null;
    }

    return $pdo;
}
