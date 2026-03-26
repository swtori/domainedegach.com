# Domaine de Gach (BTS SIO SLAM) — Back-office + Base de données

Projet “Domaine de Gach” : un site vitrine en HTML/CSS/JS, avec un **back-office** (PHP) pour gérer :
- les **clients**
- les **chambres** (consultation)
- les **réservations** (création / modification / suppression)

La persistance est assurée par **MySQL** via **PDO** (requêtes préparées) et une base de données créée par `php/gachDb.sql`.

## Fonctionnalités (côté back-office)

Sur la page `php/index.php` :
- Connexion via la table `USERS` (mot de passe stocké avec `password_hash`).
- CRUD des `CLIENTS`.
- CRUD des `RESERVATIONS`.
- Règle métier : refus de création/modification si la **chambre est déjà réservée** sur une période qui chevauche.

## Prérequis

- PHP (avec extensions PDO et driver MySQL)
- MySQL
- Un navigateur

## Installation (local / autre MySQL)

### 1) Import de la base

1. Crée une base MySQL vide (optionnel) puis exécute le script :
   - `php/gachDb.sql`
2. Le script crée et utilise une base nommée **`gachDb`** (avec tables `CLIENTS`, `CHAMBRES`, `RESERVATIONS` et `USERS`).

> Si ta base s’appelle autrement, adapte soit la configuration PHP (`DB_NAME`), soit le script SQL.

### 2) Configuration de la connexion BDD

1. Ouvre `php/Config/database.php`.
2. Vérifie / adapte :
   - `DB_HOST`
   - `DB_PORT`
   - `DB_NAME`
   - `DB_USER`

3. Le mot de passe est lu depuis un fichier `env.local.php` (prioritaire) :
   - `php/Config/env.local.php` (recommandé ; déjà ignoré par Git via le `.gitignore`)
   - (optionnel) `php/env.local.php`

Dans `env.local.php`, mets par exemple :

```php
<?php
$dbPass = 'TON_MOT_DE_PASSE_DB';
```

> Le fichier d’exemple n’est pas fourni : crée-le localement et ne le commit pas si tu utilises Git.

## Création d’un compte admin (USERS)

Le script `php/gachDb.sql` ne crée pas d’utilisateur admin.

1. Génère un hash de mot de passe (remplace `VotreMotDePasse`) :

```powershell
php -r "echo password_hash('VotreMotDePasse', PASSWORD_DEFAULT);"
```

2. Insère ensuite l’admin dans MySQL :

```sql
INSERT INTO USERS (username, password_hash)
VALUES ('admin', 'COLLE_LE_HASH_ICI');
```

## Utilisation

1. Lance ton serveur web PHP (Apache/nginx + PHP, ou XAMPP/WAMP).
2. Ouvre :
   - `php/index.php`
3. Connecte-toi avec le compte admin.
4. Utilise les formulaires pour gérer `CLIENTS` et `RESERVATIONS`.

## Schémas (documentation)

- Modèle relationnel (notation “Table(x,...)”) : `php/modeleRelationnel.txt`
- Diagramme relationnel (image) : `php/diagrammeRelationnel.png`
- Script SQL : `php/gachDb.sql`

## Notes (cohérence MCD / BDD)

Le projet stocke les réservations dans `RESERVATIONS` avec :
- `idClient` (FK vers `CLIENTS`)
- `idChambre` (FK vers `CHAMBRES`)

La règle métier anti-chevauchement est implémentée dans `php/Model/reservationModel.php`.

## Tests et validation (manuels + tentative PHPUnit)

Je me suis inspiré d’une démarche “tests” pour sécuriser les points clés.
J’ai **essayé d’ajouter quelques tests PHPUnit**, mais j’ai **laissé tomber** faute de pouvoir installer/mettre en place l’outil (PHAR/commande `phpunit`) à temps sur ma machine.

Un test unitaire est néanmoins conservé dans `tests/ReservationModelTest.php` avec une config `phpunit.xml` et un `tests/bootstrap.php` (BDD SQLite de test isolée), si tu veux le relancer plus tard.

Pour l’oral, la validation se fait surtout via une **grille de tests manuels** (reproductible) :
- Connexion admin : login OK / login KO
- Création client : saisie valide / email invalide / email déjà utilisé
- Mise à jour client : modification d’un champ / email déjà utilisé (refus)
- Création réservation : dates valides avec chambre disponible
- Création réservation : refus si la période chevauche une réservation existante pour la même chambre
- Modification réservation : même règle de chevauchement
- Suppression réservation / client : suppression confirmée

