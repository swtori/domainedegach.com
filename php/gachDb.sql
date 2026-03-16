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
