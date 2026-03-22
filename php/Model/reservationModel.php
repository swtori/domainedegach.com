<?php

/**
 * Model : tout ce qui parle à la base pour les réservations (SELECT/INSERT/UPDATE/DELETE).
 */

/**
 * Retourne toutes les réservations (BDD uniquement).
 */
function reservationModel_getAll()
{
    $pdo = getPdo();
    if ($pdo === null) {
        return array();
    }
    try {
        $stmt = $pdo->query('SELECT id, dateIn, dateOut, idChambre, idClient FROM RESERVATIONS ORDER BY id');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $i => $row) {
            $rows[$i]['id'] = (int) $row['id'];
            $rows[$i]['idChambre'] = (int) $row['idChambre'];
            $rows[$i]['idClient'] = (int) $row['idClient'];
        }
        return $rows;
    } catch (PDOException $e) {
        return array();
    }
}

/**
 * Retourne une réservation par id ou null.
 */
function reservationModel_getById($id)
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
        $stmt = $pdo->prepare('SELECT id, dateIn, dateOut, idChambre, idClient FROM RESERVATIONS WHERE id = ? LIMIT 1');
        $stmt->execute(array($id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $row['id'] = (int) $row['id'];
        $row['idChambre'] = (int) $row['idChambre'];
        $row['idClient'] = (int) $row['idClient'];
        return $row;
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * True si une autre réservation sur la même chambre chevauche [dateIn, dateOut) (exclut $excludeReservationId si fourni).
 */
function reservationModel_hasOverlap($idChambre, $dateIn, $dateOut, $excludeReservationId = null)
{
    $pdo = getPdo();
    if ($pdo === null) {
        return true;
    }
    $idChambre = (int) $idChambre;
    if ($idChambre <= 0) {
        return true;
    }
    $sql = 'SELECT COUNT(*) FROM RESERVATIONS WHERE idChambre = ? AND dateOut > ? AND dateIn < ?';
    $params = array($idChambre, $dateIn, $dateOut);
    if ($excludeReservationId !== null) {
        $sql .= ' AND id <> ?';
        $params[] = (int) $excludeReservationId;
    }
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        return true;
    }
}

/**
 * Vérifie que la chambre et le client existent.
 */
function reservationModel_foreignKeysExist($idChambre, $idClient)
{
    $pdo = getPdo();
    if ($pdo === null) {
        return false;
    }
    $idChambre = (int) $idChambre;
    $idClient = (int) $idClient;
    if ($idChambre <= 0 || $idClient <= 0) {
        return false;
    }
    try {
        $s1 = $pdo->prepare('SELECT 1 FROM CHAMBRES WHERE id = ? LIMIT 1');
        $s1->execute(array($idChambre));
        if (!$s1->fetchColumn()) {
            return false;
        }
        $s2 = $pdo->prepare('SELECT 1 FROM CLIENTS WHERE id = ? LIMIT 1');
        $s2->execute(array($idClient));
        return (bool) $s2->fetchColumn();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * @return bool
 */
function reservationModel_insert($dateIn, $dateOut, $idChambre, $idClient)
{
    $pdo = getPdo();
    if ($pdo === null) {
        return false;
    }
    try {
        $stmt = $pdo->prepare('INSERT INTO RESERVATIONS (dateIn, dateOut, idChambre, idClient) VALUES (?, ?, ?, ?)');
        $stmt->execute(array($dateIn, $dateOut, (int) $idChambre, (int) $idClient));
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * @return bool
 */
function reservationModel_update($id, $dateIn, $dateOut, $idChambre, $idClient)
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
        $stmt = $pdo->prepare('UPDATE RESERVATIONS SET dateIn = ?, dateOut = ?, idChambre = ?, idClient = ? WHERE id = ?');
        $stmt->execute(array($dateIn, $dateOut, (int) $idChambre, (int) $idClient, $id));
        if ($stmt->rowCount() > 0) {
            return true;
        }
        $chk = $pdo->prepare('SELECT id FROM RESERVATIONS WHERE id = ? LIMIT 1');
        $chk->execute(array($id));
        return (bool) $chk->fetchColumn();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * @return bool
 */
function reservationModel_delete($id)
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
        $stmt = $pdo->prepare('DELETE FROM RESERVATIONS WHERE id = ?');
        $stmt->execute(array($id));
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        return false;
    }
}
