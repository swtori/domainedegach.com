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
if (!file_exists($envLocal)) {
    $envLocal = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'env.local.php';
}
if (file_exists($envLocal)) {
    define('GACH_ENV_LOADED', true);
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

/**
 * Retourne une instance PDO ou null si la connexion échoue.
 * Aucune erreur système n'est exposée (prod).
 */
function getPdo()
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    global $dbPass;
    try {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_PORT,
            DB_NAME,
            DB_CHARSET
        );
        $pdo = new PDO($dsn, DB_USER, isset($dbPass) ? $dbPass : '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        $pdo = null;
    }

    return $pdo;
}
