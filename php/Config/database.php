<?php

/**
 * Config BDD : aucun secret ici (fichier versionné).
 * Tous les identifiants sont lus uniquement depuis un fichier .env (non versionné).
 */

/**
 * Chemins possibles du .env (hébergement : racine du site ou dossier php/).
 *
 * @return array<int, string>
 */
function database_envCandidates()
{
    $candidates = array(
        dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . '.env',
        dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env',
    );

    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $candidates[] = rtrim((string) $_SERVER['DOCUMENT_ROOT'], '/\\') . DIRECTORY_SEPARATOR . '.env';
        $candidates[] = dirname(rtrim((string) $_SERVER['DOCUMENT_ROOT'], '/\\')) . DIRECTORY_SEPARATOR . '.env';
    }

    return array_values(array_unique($candidates));
}

/**
 * Charge le premier fichier .env trouvé.
 */
function database_loadEnvFile()
{
    static $loaded = false;
    if ($loaded) {
        return;
    }
    $loaded = true;

    foreach (database_envCandidates() as $envFile) {
        if (!is_readable($envFile)) {
            continue;
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            continue;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }
            $pos = strpos($line, '=');
            if ($pos === false) {
                continue;
            }
            $key = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));
            if ($value !== '' && ($value[0] === '"' || $value[0] === "'")) {
                $quote = $value[0];
                if (substr($value, -1) === $quote) {
                    $value = substr($value, 1, -1);
                }
            }
            if ($key !== '') {
                $_ENV[$key] = $value;
                putenv($key . '=' . $value);
            }
        }

        return;
    }
}

/**
 * @return string
 */
function database_env($key)
{
    if (isset($_ENV[$key])) {
        return (string) $_ENV[$key];
    }
    $v = getenv($key);
    return $v !== false ? (string) $v : '';
}

database_loadEnvFile();

define('DB_HOST', database_env('DB_HOST'));
define('DB_PORT', (int) database_env('DB_PORT') ?: 3306);
define('DB_NAME', database_env('DB_NAME'));
define('DB_USER', database_env('DB_USER'));
define('DB_CHARSET', database_env('DB_CHARSET') !== '' ? database_env('DB_CHARSET') : 'utf8mb4');

/**
 * Retourne une instance PDO ou null si la connexion échoue.
 */
function getPdo()
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $dbPass = database_env('DB_PASS');

    if (DB_HOST === '' || DB_NAME === '' || DB_USER === '') {
        return null;
    }

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
        $pdo = null;
    }

    return $pdo;
}
