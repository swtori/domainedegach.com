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

$baseUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($baseUrl === null || $baseUrl === ''|| $baseUrl === 'skibidi') {
    $baseUrl = '/php/index.php';
}

// Déconnexion
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array();
    if (ini_get('session.use_cookies')) {
        $p = session_get_cooki
        e_params();
        setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
    header('Location: ' . $baseUrl);
    exit;
}

// Connexion (POST login)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? (string) $_POST['password'] : '';
    if (validation_isNonEmptyString($username, 255) && $password !== '' && strlen($password) <= 4096) {
        $user = userModel_verifyLogin($username, $password);
        if ($user !== null) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: ' . $baseUrl);
            exit;
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
    $action = $_POST['action'];

    if ($action === 'add_user') {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? (string) $_POST['password'] : '';
        $passwordConfirm = isset($_POST['password_confirm']) ? (string) $_POST['password_confirm'] : '';

        $errs = validation_userPayload($username, $password, $passwordConfirm);
        if (!empty($errs)) {
            $errorCode = in_array('username', $errs, true) ? 'user_validation' : 'user_password';
            if (in_array('password_confirm', $errs, true)) {
                $errorCode = 'user_password_confirm';
            }
            header('Location: ' . $baseUrl . '?error=' . $errorCode);
            exit;
        }
        if (userModel_usernameExists($username)) {
            header('Location: ' . $baseUrl . '?error=user_username_dup');
            exit;
        }

        $ok = userModel_create($username, $password);
        header('Location: ' . $baseUrl . ($ok ? '?success=user_add' : '?error=user_db'));
        exit;
    }

    if ($action === 'add_client') {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
        $tel = isset($_POST['tel']) ? $_POST['tel'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $errs = validation_clientPayload($username, $lastname, $tel, $email);
        if (!empty($errs)) {
            header('Location: ' . $baseUrl . '?error=client_validation');
            exit;
        }
        if (clientModel_emailTakenByOther($email, 0)) {
            header('Location: ' . $baseUrl . '?error=client_email_dup');
            exit;
        }
        $ok = clientModel_insert($username, $lastname, $tel, $email);
        header('Location: ' . $baseUrl . ($ok ? '?success=client_add' : '?error=client_db'));
        exit;
    }

    if ($action === 'update_client') {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
        $tel = isset($_POST['tel']) ? $_POST['tel'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        if (!validation_positiveInt($id)) {
            header('Location: ' . $baseUrl . '?error=client_validation');
            exit;
        }
        $errs = validation_clientPayload($username, $lastname, $tel, $email);
        if (!empty($errs)) {
            header('Location: ' . $baseUrl . '?error=client_validation&edit_client=' . $id);
            exit;
        }
        if (clientModel_emailTakenByOther($email, $id)) {
            header('Location: ' . $baseUrl . '?error=client_email_dup&edit_client=' . $id);
            exit;
        }
        if (clientModel_getById($id) === null) {
            header('Location: ' . $baseUrl . '?error=client_db');
            exit;
        }
        $ok = clientModel_update($id, $username, $lastname, $tel, $email);
        header('Location: ' . $baseUrl . ($ok ? '?success=client_update' : '?error=client_db'));
        exit;
    }

    if ($action === 'delete_client' && isset($_POST['id'])) {
        $id = (int) $_POST['id'];
        if (!validation_positiveInt($id)) {
            header('Location: ' . $baseUrl . '?error=client_validation');
            exit;
        }
        $ok = clientModel_delete($id);
        header('Location: ' . $baseUrl . ($ok ? '?success=client_delete' : '?error=client_db'));
        exit;
    }

    if ($action === 'add_reservation') {
        $dateIn = isset($_POST['date_in']) ? trim((string) $_POST['date_in']) : '';
        $dateOut = isset($_POST['date_out']) ? trim((string) $_POST['date_out']) : '';
        $idChambre = isset($_POST['id_chambre']) ? (int) $_POST['id_chambre'] : 0;
        $idClient = isset($_POST['id_client']) ? (int) $_POST['id_client'] : 0;
        if (!validation_reservationDateRange($dateIn, $dateOut)) {
            header('Location: ' . $baseUrl . '?error=reservation_validation');
            exit;
        }
        if (!validation_positiveInt($idChambre) || !validation_positiveInt($idClient)) {
            header('Location: ' . $baseUrl . '?error=reservation_validation');
            exit;
        }
        if (!reservationModel_foreignKeysExist($idChambre, $idClient)) {
            header('Location: ' . $baseUrl . '?error=reservation_validation');
            exit;
        }
        if (reservationModel_hasOverlap($idChambre, $dateIn, $dateOut, null)) {
            header('Location: ' . $baseUrl . '?error=reservation_overlap');
            exit;
        }
        $ok = reservationModel_insert($dateIn, $dateOut, $idChambre, $idClient);
        header('Location: ' . $baseUrl . ($ok ? '?success=reservation_add' : '?error=reservation_db'));
        exit;
    }

    if ($action === 'update_reservation') {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $dateIn = isset($_POST['date_in']) ? trim((string) $_POST['date_in']) : '';
        $dateOut = isset($_POST['date_out']) ? trim((string) $_POST['date_out']) : '';
        $idChambre = isset($_POST['id_chambre']) ? (int) $_POST['id_chambre'] : 0;
        $idClient = isset($_POST['id_client']) ? (int) $_POST['id_client'] : 0;
        if (!validation_positiveInt($id)) {
            header('Location: ' . $baseUrl . '?error=reservation_validation');
            exit;
        }
        if (!validation_reservationDateRange($dateIn, $dateOut)) {
            header('Location: ' . $baseUrl . '?error=reservation_validation&edit_reservation=' . $id);
            exit;
        }
        if (!validation_positiveInt($idChambre) || !validation_positiveInt($idClient)) {
            header('Location: ' . $baseUrl . '?error=reservation_validation&edit_reservation=' . $id);
            exit;
        }
        if (reservationModel_getById($id) === null) {
            header('Location: ' . $baseUrl . '?error=reservation_db');
            exit;
        }
        if (!reservationModel_foreignKeysExist($idChambre, $idClient)) {
            header('Location: ' . $baseUrl . '?error=reservation_validation&edit_reservation=' . $id);
            exit;
        }
        if (reservationModel_hasOverlap($idChambre, $dateIn, $dateOut, $id)) {
            header('Location: ' . $baseUrl . '?error=reservation_overlap&edit_reservation=' . $id);
            exit;
        }
        $ok = reservationModel_update($id, $dateIn, $dateOut, $idChambre, $idClient);
        header('Location: ' . $baseUrl . ($ok ? '?success=reservation_update' : '?error=reservation_db'));
        exit;
    }

    if ($action === 'delete_reservation' && isset($_POST['id'])) {
        $id = (int) $_POST['id'];
        if (!validation_positiveInt($id)) {
            header('Location: ' . $baseUrl . '?error=reservation_validation');
            exit;
        }
        $ok = reservationModel_delete($id);
        header('Location: ' . $baseUrl . ($ok ? '?success=reservation_delete' : '?error=reservation_db'));
        exit;
    }
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
$chambres = chambreModel_getAll();
$users = userModel_getAll();
$clients = clientModel_getAll();
$reservations = reservationModel_getAll();

require __DIR__ . '/../Views/showView.php';
