<?php

/**
 * Model : accès BDD pour l'authentification (table USERS).
 */

/**
 * Retourne l'utilisateur par son login, ou null.
 */
function userModel_getByUsername($username)
{
    $pdo = getPdo();
    if ($pdo === null) {
        return null;
    }
    try {
        $stmt = $pdo->prepare('SELECT id, username, password_hash FROM USERS WHERE username = ? LIMIT 1');
        $stmt->execute(array(trim((string) $username)));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row : null;
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * Vérifie identifiant + mot de passe. Retourne les infos utilisateur si OK, null sinon.
 */
function userModel_verifyLogin($username, $password)
{
    $user = userModel_getByUsername($username);
    if ($user === null) {
        return null;
    }
    if (!function_exists('password_verify')) {
        return null;
    }
    if (password_verify((string) $password, $user['password_hash']) !== true) {
        return null;
    }
    return array('id' => (int) $user['id'], 'username' => $user['username']);
}

/**
 * Compte le nombre d'utilisateurs (pour savoir si on peut créer le premier admin).
 */
function userModel_count()
{
    $pdo = getPdo();
    if ($pdo === null) {
        return 0;
    }
    try {
        $stmt = $pdo->query('SELECT COUNT(*) FROM USERS');
        return (int) $stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}

/**
 * Crée un utilisateur (mot de passe hashé avec password_hash).
 * Retourne true en cas de succès.
 */
function userModel_create($username, $password)
{
    $pdo = getPdo();
    if ($pdo === null || !function_exists('password_hash')) {
        return false;
    }
    $username = trim((string) $username);
    if ($username === '') {
        return false;
    }
    $hash = password_hash((string) $password, PASSWORD_DEFAULT);
    if ($hash === false) {
        return false;
    }
    try {
        $stmt = $pdo->prepare('INSERT INTO USERS (username, password_hash) VALUES (?, ?)');
        $stmt->execute(array($username, $hash));
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
