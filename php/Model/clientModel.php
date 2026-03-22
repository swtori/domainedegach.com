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

/**
 * Retourne un client par id ou null.
 */
function clientModel_getById($id)
{
    $pdo = getPdo();
    if ($pdo === null) {
        return null;
    }
    $id = (int) $id;
    if ($id <= 0) {
        return null;
    }
    try {
        $stmt = $pdo->prepare('SELECT id, username, lastname, tel, email FROM CLIENTS WHERE id = ? LIMIT 1');
        $stmt->execute(array($id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $row['id'] = (int) $row['id'];
        return $row;
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * True si un autre client (id différent) utilise déjà cet email.
 */
function clientModel_emailTakenByOther($email, $excludeId)
{
    $pdo = getPdo();
    if ($pdo === null) {
        return true;
    }
    $excludeId = (int) $excludeId;
    try {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM CLIENTS WHERE email = ? AND id <> ?');
        $stmt->execute(array(trim($email), $excludeId));
        return (int) $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        return true;
    }
}

/**
 * Met à jour un client. Retourne true si une ligne a été modifiée.
 */
function clientModel_update($id, $username, $lastname, $tel, $email)
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
        $stmt = $pdo->prepare('UPDATE CLIENTS SET username = ?, lastname = ?, tel = ?, email = ? WHERE id = ?');
        $stmt->execute(array(
            trim($username),
            trim($lastname),
            trim($tel),
            trim($email),
            $id,
        ));
        if ($stmt->rowCount() > 0) {
            return true;
        }
        $chk = $pdo->prepare('SELECT id FROM CLIENTS WHERE id = ? LIMIT 1');
        $chk->execute(array($id));
        return (bool) $chk->fetchColumn();
    } catch (PDOException $e) {
        return false;
    }
}
