<?php

/**
 * Model : tout ce qui parle à la base pour les chambres (SELECT/INSERT/UPDATE/DELETE).
 */

/**
 * Retourne toutes les chambres (BDD uniquement).
 */
function chambreModel_getAll()
{
    $pdo = getPdo();
    if ($pdo === null) {
        return array();
    }
    try {
        $stmt = $pdo->query('SELECT id, designation, prix, capaciteMax FROM CHAMBRES ORDER BY id');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $i => $row) {
            $rows[$i]['id'] = (int) $row['id'];
            $rows[$i]['prix'] = (float) $row['prix'];
            $rows[$i]['capaciteMax'] = (int) $row['capaciteMax'];
        }
        return $rows;
    } catch (PDOException $e) {
        return array();
    }
}
