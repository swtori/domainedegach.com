# Domaine de Gach

Site vitrine (HTML, CSS, JavaScript) pour un gîte / chambres d’hôtes, avec un **back-office PHP** connecté à **MySQL** pour gérer les **clients** et les **réservations**.

---

## Sommaire

- [Fonctionnalités](#fonctionnalités)
- [Stack technique](#stack-technique)
- [Structure du dépôt](#structure-du-dépôt)
- [Installation](#installation)
- [Configuration](#configuration)
- [Utilisation](#utilisation)
- [Base de données](#base-de-données)
- [Documentation et schémas](#documentation-et-schémas)
- [Tests](#tests)
- [Déploiement](#déploiement)
- [Sécurité](#sécurité)

---

## Fonctionnalités

### Site public

Pages statiques : accueil, chambres, contact, localisation, mentions légales, etc.

### Back-office (`php/index.php`)

| Domaine | Comportement |
|--------|----------------|
| **Authentification** | Connexion / déconnexion via la table `USERS` (hash bcrypt). |
| **Clients** | Création, modification, suppression ; contrôle email (format + unicité). |
| **Chambres** | **Consultation** uniquement dans l’interface (les lignes se gèrent en SQL ou outil MySQL). |
| **Réservations** | Création, modification, suppression ; dates validées côté serveur ; **refus** si la période **chevauche** une autre réservation sur la **même chambre**. |

---

## Stack technique

- **PHP** (sessions, PDO)
- **MySQL**
- **HTML / CSS / JavaScript** (vitrine)
- **PHPUnit** (tests unitaires optionnels, voir [Tests](#tests))

---

## Structure du dépôt

```text
├── index.html, chambres.html, …     # Pages vitrine
├── css/, js/, img/                  # Assets du site public
├── php/
│   ├── index.php                    # Point d’entrée du back-office
│   ├── gachDb.sql                   # Script de création / données d’exemple
│   ├── Config/                      # database.php, env.local.php (local)
│   ├── Controller/requests.php      # Contrôleur principal (MVC léger)
│   ├── Model/                       # Accès BDD (clients, chambres, réservations, users)
│   ├── Validation/validators.php    # Validation serveur
│   └── Views/                       # loginView, showView (admin)
├── tests/                           # PHPUnit + bootstrap SQLite
├── phpunit.xml
├── ARCHITECTURE.md                  # Détail MVC, flux, sécurité
└── README.md
```

Le dossier `php/sql/` contient aussi des fichiers de documentation SQL / modèle relationnel.

---

## Installation

### Prérequis

- PHP **8.x** recommandé, avec extensions **pdo** et **pdo_mysql**
- Serveur web (Apache, nginx + PHP-FPM, ou **XAMPP / WAMP / Laragon**)
- MySQL **5.7+** ou **MariaDB** équivalent

### Cloner le dépôt

```bash
git clone https://github.com/<votre-compte>/domaineDeGach.git
cd domaineDeGach
```

### Importer la base

1. Créer une base MySQL (vide ou nommée selon ta config).
2. Exécuter le script **`php/gachDb.sql`** (phpMyAdmin, client MySQL, etc.).

Le script cible par défaut une base nommée `gachDb` ; adapte `DB_NAME` dans `php/Config/database.php` si besoin.

### Compte administrateur

Le script SQL ne crée pas forcément d’utilisateur dans `USERS`. Pour en ajouter un :

1. Générer un hash (dans un terminal où `php` est disponible) :

   ```bash
   php -r "echo password_hash('VotreMotDePasse', PASSWORD_DEFAULT);"
   ```

2. Insérer en base :

   ```sql
   INSERT INTO USERS (username, password_hash)
   VALUES ('admin', 'COLLEZ_LE_HASH_ICI');
   ```

---

## Configuration

### Connexion MySQL

Éditer **`php/Config/database.php`** : `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`.

### Mot de passe MySQL (ne pas commiter)

Créer **`php/Config/env.local.php`** (déjà ignoré par Git si configuré dans `.gitignore`) :

```php
<?php
$dbPass = 'votre_mot_de_passe_mysql';
```

Alternative : variable d’environnement **`GACH_DB_PASS`**.

---

## Utilisation

1. Démarrer le serveur web avec la racine du projet accessible.
2. Ouvrir **`php/index.php`** dans le navigateur.
3. Se connecter avec le compte défini dans `USERS`.
4. Gérer clients et réservations depuis l’interface.

Sans ligne dans **`CHAMBRES`**, le formulaire de réservation n’apparaît pas : insérer au moins une chambre (voir section Jeux d’essais dans `php/gachDb.sql` ou requête `INSERT` manuelle).

---

## Base de données

| Table | Rôle |
|-------|------|
| `CLIENTS` | Clients du gîte |
| `CHAMBRES` | Chambres (prix, capacité) |
| `RESERVATIONS` | Liens client + chambre + période |
| `USERS` | Comptes back-office |

Clés étrangères : `RESERVATIONS` → `CLIENTS`, `CHAMBRES`.

La logique **anti-chevauchement** est dans **`php/Model/reservationModel.php`** (`reservationModel_hasOverlap`).

---

## Documentation et schémas

- **Architecture détaillée** : [`ARCHITECTURE.md`](ARCHITECTURE.md)
- **Modèle relationnel (texte)** : `php/sql/modeleRelationnel.txt`
- **Script SQL** principal : `php/gachDb.sql`

Pour des diagrammes (MCD / relationnel), voir ce qui est versionné dans `php/sql/` ou les livrables de ton dossier E4.

---

## Tests

### Tests manuels

Grille indicative (connexion, CRUD client, CRUD réservation, dates invalides, email dupliqué, chevauchement de réservations) : voir aussi la section correspondante dans ce fichier pour la reprise en oral.

### PHPUnit

Le projet inclut **`phpunit.xml`**, **`tests/bootstrap.php`** (SQLite en mémoire) et **`tests/ReservationModelTest.php`** (ex. détection de chevauchement).

Installation rapide à la racine du dépôt :

```bash
composer require --dev phpunit/phpunit
```

Puis :

```bash
vendor/bin/phpunit
```

(Si tu n’utilises pas Composer, tu peux lancer la suite avec un **PHAR PHPUnit** en pointant le même `phpunit.xml`.)

---

## Déploiement

- Le dépôt peut contenir un workflow **GitHub Pages** (`.github/workflows/static.yml`) : il publie surtout le **site statique** ; le **PHP + MySQL** doit tourner sur un **hébergeur compatible PHP** (OVH, alwaysdata, serveur perso, etc.).

---

## Sécurité

Mesures déjà en place : requêtes **PDO préparées**, mots de passe utilisateurs avec **`password_hash`**, cookies de session **`httponly`** / **`samesite`**, échappement **`htmlspecialchars`** dans les vues, **validation serveur** dans `Validation/validators.php`.

Bonnes pratiques : ne pas versionner **`env.local.php`**, utiliser **HTTPS** en production, mots de passe forts pour `USERS` et MySQL.

---

## Licence / contexte

Projet réalisé dans le cadre du **BTS SIO** (option SLAM). Adapter crédits et dépôt distant selon ton organisation.
