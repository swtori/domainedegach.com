<?php

/**
 * Model : tout ce qui parle à la base pour les réservations (SELECT/INSERT/UPDATE/DELETE).
 */

/**
 * Retourne toutes les réservations (BDD ou données de repli).
 *
 * @return array<int, array{id: int, dateIn: string, dateOut: string, idChambre: int, idClient: int}>
 */
function reservationModel_getAll(): array
{
    $pdo = getPdo();
    if ($pdo !== null) {
        $stmt = $pdo->query('SELECT id, dateIn, dateOut, idChambre, idClient FROM RESERVATIONS ORDER BY id');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $i => $row) {
            $rows[$i]['id'] = (int) $row['id'];
            $rows[$i]['idChambre'] = (int) $row['idChambre'];
            $rows[$i]['idClient'] = (int) $row['idClient'];
        }
        return $rows;
    }

    return [
        ['id' => 1, 'dateIn' => '2024-07-10', 'dateOut' => '2024-07-12', 'idChambre' => 1, 'idClient' => 1],
        ['id' => 2, 'dateIn' => '2024-08-01', 'dateOut' => '2024-08-05', 'idChambre' => 2, 'idClient' => 2],
    ];
}
