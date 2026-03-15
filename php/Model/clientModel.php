<?php

/**
 * Model : tout ce qui parle à la base pour les clients (SELECT/INSERT/UPDATE/DELETE).
 */

/**
 * Retourne tous les clients (BDD ou données de repli).
 *
 * @return array<int, array{id: int, username: string, lastname: string, tel: string, email: string}>
 */
function clientModel_getAll(): array
{
    $pdo = getPdo();
    if ($pdo !== null) {
        $stmt = $pdo->query('SELECT id, username, lastname, tel, email FROM CLIENTS ORDER BY id');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $i => $row) {
            $rows[$i]['id'] = (int) $row['id'];
        }
        return $rows;
    }

    return [
        ['id' => 1, 'username' => 'dupont', 'lastname' => 'Dupont', 'tel' => '0600000001', 'email' => 'dupont@example.com'],
        ['id' => 2, 'username' => 'martin', 'lastname' => 'Martin', 'tel' => '0600000002', 'email' => 'martin@example.com'],
    ];
}
