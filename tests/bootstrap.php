<?php

declare(strict_types=1);

/**
 * Bootstrap PHPUnit : BDD de test SQLite en mémoire + chargement des modèles.
 */

function getPdo(): ?PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $pdo = new PDO('sqlite::memory:');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');

    $pdo->exec(
        'CREATE TABLE CLIENTS (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL,
            lastname TEXT NOT NULL,
            tel TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE
        )'
    );

    $pdo->exec(
        'CREATE TABLE CHAMBRES (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            designation TEXT NOT NULL,
            prix NUMERIC NOT NULL,
            capaciteMax INTEGER NOT NULL
        )'
    );

    $pdo->exec(
        'CREATE TABLE RESERVATIONS (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            dateIn TEXT NOT NULL,
            dateOut TEXT NOT NULL,
            idChambre INTEGER NOT NULL,
            idClient INTEGER NOT NULL,
            FOREIGN KEY (idClient) REFERENCES CLIENTS(id) ON DELETE CASCADE,
            FOREIGN KEY (idChambre) REFERENCES CHAMBRES(id) ON DELETE CASCADE
        )'
    );

    $pdo->exec(
        'CREATE TABLE USERS (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        )'
    );

    return $pdo;
}

function test_resetDatabase(): void
{
    $pdo = getPdo();
    if ($pdo === null) {
        throw new RuntimeException('Impossible de créer la BDD de test.');
    }

    $pdo->exec('DELETE FROM RESERVATIONS');
    $pdo->exec('DELETE FROM USERS');
    $pdo->exec('DELETE FROM CLIENTS');
    $pdo->exec('DELETE FROM CHAMBRES');
    $pdo->exec('DELETE FROM sqlite_sequence');

    $stmtRoom = $pdo->prepare('INSERT INTO CHAMBRES (designation, prix, capaciteMax) VALUES (?, ?, ?)');
    $stmtRoom->execute(array('Suite Test', 100.00, 4));

    $stmtClient = $pdo->prepare('INSERT INTO CLIENTS (username, lastname, tel, email) VALUES (?, ?, ?, ?)');
    $stmtClient->execute(array('Client', 'Initial', '0600000000', 'client.initial@example.com'));

    $stmtUser = $pdo->prepare('INSERT INTO USERS (username, password_hash) VALUES (?, ?)');
    $stmtUser->execute(array('admin', password_hash('secret123', PASSWORD_DEFAULT)));
}

require_once __DIR__ . '/../php/Model/chambreModel.php';
require_once __DIR__ . '/../php/Model/clientModel.php';
require_once __DIR__ . '/../php/Model/reservationModel.php';
require_once __DIR__ . '/../php/Model/userModel.php';
