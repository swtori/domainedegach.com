<?php
/**
 * Débogage 500 : charge le site étape par étape pour trouver l'erreur. Supprimer après débogage.
 */
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', '1');
error_reporting(E_ALL);

echo "<pre>\n";
echo "1. Démarrage OK\n";
flush();

$base = __DIR__;

echo "2. Base: $base\n";
flush();

if (!is_file($base . '/Config/database.php')) {
    echo "ERREUR: Config/database.php introuvable (vérifier la casse: Config avec C majuscule)\n";
    exit;
}
echo "3. Config/database.php trouvé\n";
flush();

require_once $base . '/Config/database.php';
echo "4. Config chargée\n";
flush();

$pdo = getPdo();
echo "5. getPdo() = " . ($pdo ? 'connexion OK' : 'null (pas de BDD)') . "\n";
flush();

if (!is_file($base . '/Model/chambreModel.php')) {
    echo "ERREUR: Model/chambreModel.php introuvable (vérifier la casse: Model avec M majuscule)\n";
    exit;
}
require_once $base . '/Model/chambreModel.php';
echo "6. chambreModel chargé\n";
flush();

require_once $base . '/Model/clientModel.php';
require_once $base . '/Model/reservationModel.php';
echo "7. Tous les models chargés\n";
flush();

$chambres = chambreModel_getAll();
$clients = clientModel_getAll();
$reservations = reservationModel_getAll();
echo "8. Données récupérées (chambres: " . count($chambres) . ", clients: " . count($clients) . ", résa: " . count($reservations) . ")\n";
flush();

if (!is_file($base . '/Controller/requests.php')) {
    echo "ERREUR: Controller/requests.php introuvable\n";
    exit;
}
echo "9. Chargement du controller...\n";
flush();

require_once $base . '/Controller/requests.php';
echo "10. Fin (si tu vois ceci, le controller a inclus la vue)\n";
echo "</pre>\n";
