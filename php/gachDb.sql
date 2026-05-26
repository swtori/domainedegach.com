-- ######################################################
-- # 📦 gachDb.sql
-- # 
-- # Auteur      : author
-- # Date        : 2024-06-21
-- # Description : Initialisation de la base de données
-- #               pour le site Domaine de Gach.
-- ######################################################
-- # 📦 gachDb.sql
-- # Tables :
-- # - CLIENTS : Utilisateurs du site
-- # - CHAMBRES : Chambres du site
-- # - RESERVATIONS : Réservations des chambres
-- # - USERS : Comptes back-office (authentification)
-- ######################################################

CREATE DATABASE IF NOT EXISTS gachDb;
USE gachDb;

CREATE TABLE CLIENTS (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    tel VARCHAR(20) NOT NULL,
    email VARCHAR(70) NOT NULL UNIQUE
);

CREATE TABLE CHAMBRES (
    id INT PRIMARY KEY AUTO_INCREMENT,
    designation VARCHAR(255) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    capaciteMax INT NOT NULL
);

CREATE TABLE RESERVATIONS (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dateIn DATE NOT NULL,
    dateOut DATE NOT NULL,
    idChambre INT NOT NULL,
    idClient INT NOT NULL,
    FOREIGN KEY (idClient) REFERENCES CLIENTS(id) ON DELETE CASCADE,
    FOREIGN KEY (idChambre) REFERENCES CHAMBRES(id) ON DELETE CASCADE
);

-- Table USERS : comptes pour la connexion au back-office (créée à l'installation)
CREATE TABLE IF NOT EXISTS USERS (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ######################################################
-- # Jeux d'essais (données de démonstration)
-- # Réimport : exécuter d’abord les DELETE ci-dessous (ordre FK),
-- # puis les INSERT, ou repartir d’une base vide avec ce script entier.
-- ######################################################

INSERT INTO CHAMBRES (designation, prix, capaciteMax) VALUES
    ('Suite', 125.00, 4),
    ('Chambre Denis', 89.00, 2),
    ('Chambre Créole', 95.00, 2),
    ('Chambre Géo', 85.00, 2);

INSERT INTO CLIENTS (username, lastname, tel, email) VALUES
    ('Marie', 'Durand', '0612345678', 'marie.durand@example.com'),
    ('Jean', 'Bernard', '0623456789', 'jean.bernard@example.com'),
    ('Sophie', 'Martin', '0634567890', 'sophie.martin@example.com'),
    ('Luc', 'Petit', '0645678901', 'luc.petit@example.com'),
    ('Camille', 'Roux', '0656789012', 'camille.roux@example.com');

-- idChambre / idClient : sous-requêtes sur désignation et email (uniques)
-- Insertion des réservations de démonstration, réparties par chambres et clients pour une meilleure lisibilité
INSERT INTO RESERVATIONS (dateIn, dateOut, idChambre, idClient) VALUES
    -- Réservation de la Suite par Marie Durand
    ('2025-06-10', '2025-06-14',
        (SELECT id FROM CHAMBRES WHERE designation = 'Suite' LIMIT 1),
        (SELECT id FROM CLIENTS WHERE email = 'marie.durand@example.com' LIMIT 1)
    ),

    -- Chambre Denis réservée par Jean Bernard
    ('2025-07-01', '2025-07-05',
        (SELECT id FROM CHAMBRES WHERE designation = 'Chambre Denis' LIMIT 1),
        (SELECT id FROM CLIENTS WHERE email = 'jean.bernard@example.com' LIMIT 1)
    ),

    -- Chambre Créole réservée à nouveau par Marie Durand
    ('2025-07-20', '2025-07-22',
        (SELECT id FROM CHAMBRES WHERE designation = 'Chambre Créole' LIMIT 1),
        (SELECT id FROM CLIENTS WHERE email = 'marie.durand@example.com' LIMIT 1)
    ),

    -- Chambre Géo réservée par Sophie Martin
    ('2025-08-15', '2025-08-21',
        (SELECT id FROM CHAMBRES WHERE designation = 'Chambre Géo' LIMIT 1),
        (SELECT id FROM CLIENTS WHERE email = 'sophie.martin@example.com' LIMIT 1)
    ),

    -- Chambre Denis par Luc Petit
    ('2026-04-05', '2026-04-08',
        (SELECT id FROM CHAMBRES WHERE designation = 'Chambre Denis' LIMIT 1),
        (SELECT id FROM CLIENTS WHERE email = 'luc.petit@example.com' LIMIT 1)
    ),

    -- Suite réservée par Camille Roux
    ('2026-05-12', '2026-05-18',
        (SELECT id FROM CHAMBRES WHERE designation = 'Suite' LIMIT 1),
        (SELECT id FROM CLIENTS WHERE email = 'camille.roux@example.com' LIMIT 1)
    );
