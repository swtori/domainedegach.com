<?php

/**
 * Model : tout ce qui parle à la base pour les clients (SELECT/INSERT/UPDATE/DELETE).
 */

/**
 * Retourne tous les clients (BDD uniquement).
 */
function clientModel_getAll()
{
    $pdo = getPdo();
    if ($pdo === null) {
        return array();
    }
    try {
        $stmt = $pdo->query('SELECT id, username, lastname, tel, email FROM CLIENTS ORDER BY id');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $i => $row) {
            $rows[$i]['id'] = (int) $row['id'];
        }
        return $rows;
    } catch (PDOException $e) {
        return array();
    }
}

/**
 * Insère un client en BDD. Retourne true en cas de succès, false sinon.
 */
function clientModel_insert($username, $lastname, $tel, $email)
{
    $pdo = getPdo();
    if ($pdo === null) {
        return false;
    }
    try {
        $stmt = $pdo->prepare('INSERT INTO CLIENTS (username, lastname, tel, email) VALUES (?, ?, ?, ?)');
        $stmt->execute(array(
            trim($username),
            trim($lastname),
            trim($tel),
            trim($email),
        ));
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Supprime un client par id. Retourne true si une ligne a été supprimée, false sinon.
 */
function clientModel_delete($id)
{
    $pdo = getPdo();
    if ($pdo === null) {
        return false;
    }
    $id = (int) $id;
    if ($id <= 0) {
        return false;
    }
    try {
        $stmt = $pdo->prepare('DELETE FROM CLIENTS WHERE id = ?');
        $stmt->execute(array($id));
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        return false;
    }
}
