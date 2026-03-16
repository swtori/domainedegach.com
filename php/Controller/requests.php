<?php

/**
 * Controller : récupération $_GET / $_POST, traitement formulaires, appel des Models, choix de la View.
 */

require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Model/chambreModel.php';
require_once __DIR__ . '/../Model/clientModel.php';
require_once __DIR__ . '/../Model/reservationModel.php';
require_once __DIR__ . '/../Model/userModel.php';

$baseUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($baseUrl === null || $baseUrl === '') {
    $baseUrl = '/php/index.php';
}

// Déconnexion
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array();
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
    header('Location: ' . $baseUrl);
    exit;
}

// Connexion (POST login)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $user = null;
    if ($username !== '' && $password !== '') {
        $user = userModel_verifyLogin($username, $password);
    }
    if ($user !== null) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: ' . $baseUrl);
        exit;
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

// Traitement des actions client (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_client') {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
        $tel = isset($_POST['tel']) ? $_POST['tel'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        if ($username !== '' && $lastname !== '' && $tel !== '' && $email !== '') {
            $ok = clientModel_insert($username, $lastname, $tel, $email);
            header('Location: ' . $baseUrl . ($ok ? '?added=1' : '?error_add=1'));
        } else {
            header('Location: ' . $baseUrl);
        }
        exit;
    }
    if ($_POST['action'] === 'delete_client' && isset($_POST['id'])) {
        $ok = clientModel_delete($_POST['id']);
        header('Location: ' . $baseUrl . ($ok ? '?deleted=1' : '?error_delete=1'));
        exit;
    }
}

$dbConnected = (getPdo() !== null);
$currentUser = array('username' => $_SESSION['username']);

// Récupération des données via les Models
$chambres = chambreModel_getAll();
$clients = clientModel_getAll();
$reservations = reservationModel_getAll();

// Choix de la vue à afficher (pour l’instant une seule)
require __DIR__ . '/../Views/showView.php';
