<?php

/**
 * Config : accès base de données (connexion PDO/MySQL), paramètres, constantes.
 */

define('DB_HOST', 'db5019965364.hosting-data.io');
define('DB_PORT', 3306);
define('DB_NAME', 'dbs15409847');
define('DB_USER', 'dbu1225268');
define('DB_CHARSET', 'utf8mb4');

// Mot de passe : fichier env.local.php prioritaire, sinon variable d'environnement GACH_DB_PASS
$dbPass = '';
$envLocal = __DIR__ . DIRECTORY_SEPARATOR . 'env.local.php';
if (file_exists($envLocal)) {
    include $envLocal;
    if (isset($dbPass)) {
        $dbPass = (string) $dbPass;
    } else {
        $dbPass = '';
    }
}
if ($dbPass === '' && getenv('GACH_DB_PASS') !== false && getenv('GACH_DB_PASS') !== '') {
    $dbPass = getenv('GACH_DB_PASS');
}
if ($dbPass === '' && isset($_ENV['GACH_DB_PASS']) && $_ENV['GACH_DB_PASS'] !== '') {
    $dbPass = $_ENV['GACH_DB_PASS'];
}

$GLOBALS['_pdo_last_error'] = null;

/** Retourne l'erreur de la dernière tentative de connexion PDO, ou null. */
function getPdoError()
{
    return isset($GLOBALS['_pdo_last_error']) ? $GLOBALS['_pdo_last_error'] : null;
}

/**
 * Retourne une instance PDO ou null si la connexion échoue.
 */
function getPdo()
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $GLOBALS['_pdo_last_error'] = null;
    try {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_PORT,
            DB_NAME,
            DB_CHARSET
        );
        $pdo = new PDO($dsn, DB_USER, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        $GLOBALS['_pdo_last_error'] = $e->getMessage();
        $pdo = null;
    }

    return $pdo;
}
