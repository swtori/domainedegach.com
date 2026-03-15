<?php

// Mini base de données simulée pour les tests
// Correspond à la structure définie dans gachDb.sql

// ---------- TABLE CLIENTS ----------
$CLIENTS = [
    [
        'id' => 1,
        'username' => 'dupont',
        'lastname' => 'Dupont',
        'tel' => '0600000001',
        'email' => 'dupont@example.com',
    ],
    [
        'id' => 2,
        'username' => 'martin',
        'lastname' => 'Martin',
        'tel' => '0600000002',
        'email' => 'martin@example.com',
    ],
];

// ---------- TABLE CHAMBRES ----------
$CHAMBRES = [
    [
        'id' => 1,
        'designation' => 'LA SUITE',
        'prix' => 80.00,
        'capaciteMax' => 4,
    ],
    [
        'id' => 2,
        'designation' => 'DENIS',
        'prix' => 70.00,
        'capaciteMax' => 2,
    ],
    [
        'id' => 3,
        'designation' => 'CRÉOLE',
        'prix' => 70.00,
        'capaciteMax' => 3,
    ],
    [
        'id' => 4,
        'designation' => 'GEO',
        'prix' => 70.00,
        'capaciteMax' => 3,
    ],
];

// ---------- TABLE RESERVATIONS ----------
// Respecte la contrainte CHECK (dateOut > dateIn)
$RESERVATIONS = [
    [
        'id' => 1,
        'dateIn' => '2024-07-10',
        'dateOut' => '2024-07-12',
        'idChambre' => 1,
        'idClient' => 1,
    ],
    [
        'id' => 2,
        'dateIn' => '2024-08-01',
        'dateOut' => '2024-08-05',
        'idChambre' => 2,
        'idClient' => 2,
    ],
];

// ---------- FONCTIONS UTILITAIRES POUR LES TESTS ----------

function fakeDb_findChambreById(int $id): ?array
{
    global $CHAMBRES;
    foreach ($CHAMBRES as $chambre) {
        if ($chambre['id'] === $id) {
            return $chambre;
        }
    }
    return null;
}

function fakeDb_findClientById(int $id): ?array
{
    global $CLIENTS;
    foreach ($CLIENTS as $client) {
        if ($client['id'] === $id) {
            return $client;
        }
    }
    return null;
}

function fakeDb_findReservationsByChambre(int $idChambre): array
{
    global $RESERVATIONS;
    return array_values(array_filter($RESERVATIONS, function ($res) use ($idChambre) {
        return $res['idChambre'] === $idChambre;
    }));
}

function fakeDb_findReservationsByClient(int $idClient): array
{
    global $RESERVATIONS;
    return array_values(array_filter($RESERVATIONS, function ($res) use ($idClient) {
        return $res['idClient'] === $idClient;
    }));
}


