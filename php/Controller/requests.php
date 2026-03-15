<?php

/**
 * Controller : récupération $_GET / $_POST, traitement formulaires, appel des Models, choix de la View.
 */

require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Model/chambreModel.php';
require_once __DIR__ . '/../Model/clientModel.php';
require_once __DIR__ . '/../Model/reservationModel.php';

// Récupération des données via les Models
$chambres = chambreModel_getAll();
$clients = clientModel_getAll();
$reservations = reservationModel_getAll();

// Choix de la vue à afficher (pour l’instant une seule)
require __DIR__ . '/../Views/showView.php';
