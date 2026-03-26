# Architecture du Projet - Domaine de Gach

## Vue d'ensemble

Le projet est composé de :
- un **site vitrine** statique (HTML/CSS/JS)
- un **back-office PHP** en architecture **MVC légère** pour la gestion des clients et des réservations
- une base **MySQL** initialisée via `php/gachDb.sql`

## Arborescence utile

```text
domainedegach.com/
├── index.html
├── chambres.html
├── chambre-suite.html
├── chambre-denis.html
├── chambre-creole.html
├── chambre-geo.html
├── autour.html
├── localiser.html
├── contact.html
├── mentions-legales.html
├── README.md
├── ARCHITECTURE.md
├── css/
│   └── style.css
├── js/
│   ├── main.js
│   ├── carousel.js
│   └── contact.js
├── php/
│   ├── index.php
│   ├── content.php
│   ├── gachDb.sql
│   ├── modeleRelationnel.txt
│   ├── sql/
│   │   ├── MCD.jpg
│   │   └── diagrammeRelationnel.png
│   ├── Config/
│   │   └── database.php
│   ├── Controller/
│   │   └── requests.php
│   ├── Model/
│   │   ├── chambreModel.php
│   │   ├── clientModel.php
│   │   ├── reservationModel.php
│   │   └── userModel.php
│   ├── Validation/
│   │   └── validators.php
│   └── Views/
│       ├── loginView.php
│       └── showView.php
└── img/
    └── ... (ressources médias du site)
```

## Architecture MVC (back-office)

### 1) Point d'entrée
- `php/index.php`
- Démarre la session sécurisée et charge le contrôleur principal.

### 2) Contrôleur
- `php/Controller/requests.php`
- Rôle :
  - lire les requêtes `GET/POST`
  - appliquer les validations
  - appeler les modèles
  - gérer la connexion/déconnexion
  - rediriger avec messages de succès/erreur
  - choisir la vue à afficher

### 3) Modèles
- `php/Model/chambreModel.php` : lecture des chambres
- `php/Model/clientModel.php` : CRUD clients
- `php/Model/reservationModel.php` : CRUD réservations + vérification chevauchement
- `php/Model/userModel.php` : authentification admin (`USERS`, `password_hash`, `password_verify`)

### 4) Validation
- `php/Validation/validators.php`
- Validation serveur des champs : email, téléphone, dates, identifiants positifs, cohérence des périodes.

### 5) Vues
- `php/Views/loginView.php` : formulaire de connexion
- `php/Views/showView.php` : interface d'administration (chambres, clients, réservations)

### 6) Configuration BDD
- `php/Config/database.php`
- Connexion PDO MySQL avec :
  - constantes de connexion (`DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`)
  - mot de passe depuis `php/Config/env.local.php` (ou variable d'environnement)

## Schéma de données

- Script SQL : `php/gachDb.sql`
- Tables principales :
  - `CLIENTS`
  - `CHAMBRES`
  - `RESERVATIONS`
  - `USERS`
- Relations :
  - `RESERVATIONS.idClient` -> `CLIENTS.id`
  - `RESERVATIONS.idChambre` -> `CHAMBRES.id`

## Flux de traitement (résumé)

1. L'utilisateur ouvre `php/index.php`.
2. Le contrôleur vérifie la session.
3. En POST, il valide les données puis appelle les modèles.
4. Les modèles exécutent les requêtes SQL (PDO).
5. Le contrôleur redirige avec un message (`success` / `error`).
6. La vue `showView.php` affiche l'état courant.

## Sécurité déjà en place

- Requêtes préparées PDO
- Mots de passe hashés (`password_hash` / `password_verify`)
- Session avec cookie `httponly` et `samesite`
- Échappement HTML dans les vues (`htmlspecialchars`)
- Validations serveur sur les formulaires

