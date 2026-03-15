<?php

/**
 * Model : tout ce qui parle à la base pour les chambres (SELECT/INSERT/UPDATE/DELETE).
 */

/**
 * Retourne toutes les chambres (BDD ou données de repli).
 *
 * @return array<int, array{id: int, designation: string, prix: float, capaciteMax: int}>
 */
function chambreModel_getAll(): array
{
    $pdo = getPdo();
    if ($pdo !== null) {
        $stmt = $pdo->query('SELECT id, designation, prix, capaciteMax FROM CHAMBRES ORDER BY id');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $i => $row) {
            $rows[$i]['id'] = (int) $row['id'];
            $rows[$i]['prix'] = (float) $row['prix'];
            $rows[$i]['capaciteMax'] = (int) $row['capaciteMax'];
        }
        return $rows;
    }

    return [
        ['id' => 1, 'designation' => 'LA SUITE', 'prix' => 80.00, 'capaciteMax' => 4],
        ['id' => 2, 'designation' => 'DENIS', 'prix' => 70.00, 'capaciteMax' => 2],
        ['id' => 3, 'designation' => 'CRÉOLE', 'prix' => 70.00, 'capaciteMax' => 3],
        ['id' => 4, 'designation' => 'GEO', 'prix' => 70.00, 'capaciteMax' => 3],
    ];
}
