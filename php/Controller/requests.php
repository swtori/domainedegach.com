<?php

/**
 * Controller : récupération $_GET / $_POST, traitement formulaires, appel des Models, choix de la View.
 */

require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Validation/validators.php';
require_once __DIR__ . '/../Model/chambreModel.php';
require_once __DIR__ . '/../Model/clientModel.php';
require_once __DIR__ . '/../Model/reservationModel.php';
require_once __DIR__ . '/../Model/userModel.php';
require_once __DIR__ . '/controllerHelpers.php';
require_once __DIR__ . '/chambreRequests.php';
require_once __DIR__ . '/clientRequests.php';
require_once __DIR__ . '/reservationRequests.php';

$baseUrl = controller_getBaseUrl();

// Déconnexion
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array();
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
    controller_redirect($baseUrl);
}

// Connexion (POST login)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = trim(controller_postString('username'));
    $password = controller_postString('password');
    if (validation_isNonEmptyString($username, 255) && $password !== '' && strlen($password) <= 4096) {
        $user = userModel_verifyLogin($username, $password);
        if ($user !== null) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            controller_redirect($baseUrl);
        }
    }
    $loginError = 'Identifiant ou mot de passe incorrect.';
    require __DIR__ . '/../Views/loginView.php';
    return;
}

// Accès réservé aux utilisateurs connectés
$loggedIn = isset($_SESSION['user_id'], $_SESSION['username']);
if (!$loggedIn) {
    $loginError = isset($loginError) ? $loginError : '';
    require __DIR__ . '/../Views/loginView.php';
    return;
}

// Traitement des actions (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = (string) $_POST['action'];

    // Dispatch par domaine : clients puis réservations.
    controller_handleClientAction($action, $baseUrl);
    controller_handleReservationAction($action, $baseUrl);
}

$dbConnected = (getPdo() !== null);
$currentUser = array('username' => $_SESSION['username']);

// Mode édition (GET)
$editClient = null;
if (isset($_GET['edit_client'])) {
    $eid = (int) $_GET['edit_client'];
    if ($eid > 0) {
        $editClient = clientModel_getById($eid);
    }
}

$editReservation = null;
if (isset($_GET['edit_reservation'])) {
    $rid = (int) $_GET['edit_reservation'];
    if ($rid > 0) {
        $editReservation = reservationModel_getById($rid);
    }
}

// Récupération des données via les Models
$chambres = controller_getChambres();
$clients = clientModel_getAll();
$reservations = reservationModel_getAll();

// Choix de la vue à afficher (pour l’instant une seule)
require __DIR__ . '/../Views/showView.php';

