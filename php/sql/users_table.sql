-- Table des utilisateurs pour l'authentification (back-office)
-- Exécuter une fois sur la base dbs15409847 (ou gachDb).

CREATE TABLE IF NOT EXISTS USERS (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
