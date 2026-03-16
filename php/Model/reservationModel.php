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
