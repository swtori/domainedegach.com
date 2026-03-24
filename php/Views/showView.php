<?php
/** @var array $chambres */
/** @var array $clients */
/** @var array $reservations */
/** @var bool $dbConnected */
/** @var array $currentUser */
/** @var string $baseUrl */
/** @var array|null $editClient */
/** @var array|null $editReservation */

$chambreById = array();
foreach ($chambres as $ch) {
    $chambreById[(int) $ch['id']] = isset($ch['designation']) ? $ch['designation'] : '';
}
$clientById = array();
foreach ($clients as $cl) {
    $nom = trim((isset($cl['username']) ? $cl['username'] : '') . ' ' . (isset($cl['lastname']) ? $cl['lastname'] : ''));
    $clientById[(int) $cl['id']] = $nom;
}

$flashSuccess = isset($_GET['success']) ? (string) $_GET['success'] : '';
$flashError = isset($_GET['error']) ? (string) $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domaine de Gach — Chambres, clients, réservations</title>
    <style>
        :root {
            --bg: #f0ebe3;
            --card: #fff;
            --accent: #4a6741;
            --accent-hover: #3d5536;
            --text: #2a2a2a;
            --muted: #666;
            --ok: #1e6b3a;
            --err: #b32d2d;
            --line: #e8e4dc;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: system-ui, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.5;
            min-height: 100vh;
        }
        .page { padding: 1.5rem 1rem 3rem; }
        .wrap {
            max-width: 900px;
            margin: 0 auto;
        }
        .top {
            text-align: center;
            margin-bottom: 1.75rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--line);
        }
        .top h1 {
            margin: 0 0 0.4rem;
            font-size: clamp(1.5rem, 4vw, 1.85rem);
            font-weight: 650;
            letter-spacing: -0.02em;
            color: var(--accent);
        }
        .top .sub { color: var(--muted); font-size: 0.95rem; }
        .top a { color: var(--accent); font-weight: 600; text-decoration: none; }
        .top a:hover { text-decoration: underline; }
        .card {
            background: var(--card);
            border-radius: 14px;
            padding: 1.35rem 1.4rem 1.5rem;
            margin-bottom: 1.15rem;
            box-shadow: 0 6px 28px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .card h2 {
            margin: 0 0 0.85rem;
            font-size: 1.12rem;
            color: var(--accent);
            font-weight: 650;
        }
        .card h3 {
            margin: 0 0 0.75rem;
            font-size: 0.98rem;
            color: #444;
            font-weight: 600;
        }
        .flash {
            padding: 0.65rem 0.9rem;
            border-radius: 10px;
            margin: 0 0 1rem;
            font-size: 0.9rem;
        }
        .flash--ok { background: #e3f2e6; color: var(--ok); }
        .flash--err { background: #fdecef; color: var(--err); }
        .hint { color: var(--muted); font-size: 0.9rem; font-style: italic; margin: 0.5rem 0 0; }
        .db-down {
            background: #fff8e6;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 1rem;
            border: 1px solid #f0e0c0;
        }
        .data-table-wrap { overflow-x: auto; margin-top: 0.35rem; -webkit-overflow-scrolling: touch; }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        .data-table th, .data-table td {
            padding: 0.55rem 0.6rem;
            text-align: left;
            border-bottom: 1px solid var(--line);
        }
        .data-table thead th {
            background: #f7f5f1;
            color: #444;
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .data-table tbody tr:hover { background: #faf9f7; }
        .form-grid label {
            display: block;
            margin-bottom: 0.7rem;
            font-size: 0.88rem;
            color: #444;
        }
        .form-grid input, .form-grid select {
            display: block;
            width: 100%;
            max-width: 340px;
            margin-top: 0.2rem;
            padding: 0.5rem 0.65rem;
            border: 1px solid #d0ccc4;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
        }
        .form-grid select { max-width: 100%; }
        .btn-row { margin-top: 0.9rem; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; }
        button[type="submit"] {
            padding: 0.5rem 1.1rem;
            border-radius: 8px;
            border: none;
            font-size: 0.9rem;
            cursor: pointer;
            font-family: inherit;
            font-weight: 600;
            background: var(--accent);
            color: #fff;
        }
        button[type="submit"]:hover { background: var(--accent-hover); }
        .btn--danger {
            padding: 0.35rem 0.65rem;
            font-size: 0.82rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-family: inherit;
            background: #c44;
            color: #fff;
            font-weight: 600;
        }
        .btn--danger:hover { background: var(--err); }
        .btn-link {
            display: inline-block;
            color: var(--accent);
            font-weight: 600;
            text-decoration: none;
            font-size: 0.88rem;
            margin-right: 0.25rem;
        }
        .btn-link:hover { text-decoration: underline; }
        .actions-cell { white-space: nowrap; }
        .actions-cell form { display: inline-block; vertical-align: middle; margin-left: 0.2rem; }
        .empty { color: var(--muted); margin: 0.25rem 0 0; }
    </style>
</head>
<body>
<div class="page">
<div class="wrap">
    <header class="top">
        <h1>Domaine de Gach</h1>
        <p class="sub">Connecté en tant que <strong><?php echo htmlspecialchars(isset($currentUser['username']) ? $currentUser['username'] : '', ENT_QUOTES, 'UTF-8'); ?></strong>
            — <a href="?action=logout">Déconnexion</a></p>
    </header>

    <?php if (isset($dbConnected) && !$dbConnected): ?>
        <p class="db-down">Service temporairement indisponible. Réessayez plus tard.</p>
    <?php endif; ?>

    <section class="card">
        <h2>Chambres</h2>
        <?php if (empty($chambres)): ?>
            <p class="empty">Aucune chambre.</p>
        <?php else: ?>
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Désignation</th>
                            <th>Prix</th>
                            <th>Capacité max</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chambres as $c): ?>
                            <tr>
                                <td><?php echo (int) $c['id']; ?></td>
                                <td><?php echo htmlspecialchars(isset($c['designation']) ? $c['designation'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo number_format((float) (isset($c['prix']) ? $c['prix'] : 0), 2, ',', ' '); ?> €</td>
                                <td><?php echo (int) (isset($c['capaciteMax']) ? $c['capaciteMax'] : 0); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <section class="card">
        <h2>Clients</h2>
        <?php
        if ($flashSuccess === 'client_add') {
            echo '<p class="flash flash--ok">Client ajouté.</p>';
        }
        if ($flashSuccess === 'client_update') {
            echo '<p class="flash flash--ok">Client modifié.</p>';
        }
        if ($flashSuccess === 'client_delete') {
            echo '<p class="flash flash--ok">Client supprimé.</p>';
        }
        if ($flashError === 'client_validation') {
            echo '<p class="flash flash--err">Données invalides (prénom, nom, téléphone ou email).</p>';
        }
        if ($flashError === 'client_email_dup') {
            echo '<p class="flash flash--err">Cet email est déjà utilisé par un autre client.</p>';
        }
        if ($flashError === 'client_db') {
            echo '<p class="flash flash--err">L\'opération n\'a pas abouti. Réessayez.</p>';
        }
        ?>

        <?php if (isset($editClient) && is_array($editClient)): ?>
            <h3>Modifier le client #<?php echo (int) $editClient['id']; ?></h3>
            <form method="post" action="" class="form-grid">
                <input type="hidden" name="action" value="update_client">
                <input type="hidden" name="id" value="<?php echo (int) $editClient['id']; ?>">
                <label>Prénom <input type="text" name="username" required maxlength="255" value="<?php echo htmlspecialchars($editClient['username'], ENT_QUOTES, 'UTF-8'); ?>"></label>
                <label>Nom <input type="text" name="lastname" required maxlength="255" value="<?php echo htmlspecialchars($editClient['lastname'], ENT_QUOTES, 'UTF-8'); ?>"></label>
                <label>Tél <input type="text" name="tel" required maxlength="20" value="<?php echo htmlspecialchars($editClient['tel'], ENT_QUOTES, 'UTF-8'); ?>"></label>
                <label>Email <input type="email" name="email" required maxlength="70" value="<?php echo htmlspecialchars($editClient['email'], ENT_QUOTES, 'UTF-8'); ?>"></label>
                <div class="btn-row">
                    <button type="submit">Enregistrer</button>
                    <a class="btn-link" href="<?php echo htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8'); ?>">Annuler</a>
                </div>
            </form>
        <?php else: ?>
            <h3>Nouveau client</h3>
            <form method="post" action="" class="form-grid">
                <input type="hidden" name="action" value="add_client">
                <label>Prénom <input type="text" name="username" required maxlength="255"></label>
                <label>Nom <input type="text" name="lastname" required maxlength="255"></label>
                <label>Tél <input type="text" name="tel" required maxlength="20"></label>
                <label>Email <input type="email" name="email" required maxlength="70"></label>
                <div class="btn-row">
                    <button type="submit">Ajouter un client</button>
                </div>
            </form>
        <?php endif; ?>

        <?php if (empty($clients)): ?>
            <p class="empty">Aucun client.</p>
        <?php else: ?>
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Tél</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $c): ?>
                            <tr>
                                <td><?php echo (int) $c['id']; ?></td>
                                <td><?php echo htmlspecialchars(isset($c['username']) ? $c['username'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(isset($c['lastname']) ? $c['lastname'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(isset($c['tel']) ? $c['tel'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(isset($c['email']) ? $c['email'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="actions-cell">
                                    <a class="btn-link" href="<?php echo htmlspecialchars($baseUrl . '?edit_client=' . (int) $c['id'], ENT_QUOTES, 'UTF-8'); ?>">Modifier</a>
                                    <form method="post" action="" onsubmit="return confirm('Supprimer ce client ?');">
                                        <input type="hidden" name="action" value="delete_client">
                                        <input type="hidden" name="id" value="<?php echo (int) $c['id']; ?>">
                                        <button type="submit" class="btn--danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <section class="card">
        <h2>Réservations</h2>
        <?php
        if ($flashSuccess === 'reservation_add') {
            echo '<p class="flash flash--ok">Réservation créée.</p>';
        }
        if ($flashSuccess === 'reservation_update') {
            echo '<p class="flash flash--ok">Réservation modifiée.</p>';
        }
        if ($flashSuccess === 'reservation_delete') {
            echo '<p class="flash flash--ok">Réservation supprimée.</p>';
        }
        if ($flashError === 'reservation_validation') {
            echo '<p class="flash flash--err">Dates ou sélection invalides (vérifiez les dates et les identifiants).</p>';
        }
        if ($flashError === 'reservation_overlap') {
            echo '<p class="flash flash--err">Cette chambre est déjà réservée sur tout ou partie de cette période.</p>';
        }
        if ($flashError === 'reservation_db') {
            echo '<p class="flash flash--err">L\'opération sur la réservation a échoué. Réessayez.</p>';
        }
        ?>

        <?php if (isset($editReservation) && is_array($editReservation)): ?>
            <h3>Modifier la réservation #<?php echo (int) $editReservation['id']; ?></h3>
            <form method="post" action="" class="form-grid">
                <input type="hidden" name="action" value="update_reservation">
                <input type="hidden" name="id" value="<?php echo (int) $editReservation['id']; ?>">
                <label>Date d’entrée <input type="date" name="date_in" required value="<?php echo htmlspecialchars($editReservation['dateIn'], ENT_QUOTES, 'UTF-8'); ?>"></label>
                <label>Date de sortie <input type="date" name="date_out" required value="<?php echo htmlspecialchars($editReservation['dateOut'], ENT_QUOTES, 'UTF-8'); ?>"></label>
                <label>Chambre
                    <select name="id_chambre" required>
                        <?php foreach ($chambres as $ch): ?>
                            <option value="<?php echo (int) $ch['id']; ?>"<?php echo ((int) $editReservation['idChambre'] === (int) $ch['id']) ? ' selected' : ''; ?>><?php echo htmlspecialchars($ch['designation'], ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Client
                    <select name="id_client" required>
                        <?php foreach ($clients as $cl): ?>
                            <option value="<?php echo (int) $cl['id']; ?>"<?php echo ((int) $editReservation['idClient'] === (int) $cl['id']) ? ' selected' : ''; ?>><?php echo htmlspecialchars(trim($cl['username'] . ' ' . $cl['lastname']), ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <div class="btn-row">
                    <button type="submit">Enregistrer</button>
                    <a class="btn-link" href="<?php echo htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8'); ?>">Annuler</a>
                </div>
            </form>
        <?php elseif (!empty($chambres) && !empty($clients)): ?>
            <h3>Nouvelle réservation</h3>
            <form method="post" action="" class="form-grid">
                <input type="hidden" name="action" value="add_reservation">
                <label>Date d’entrée <input type="date" name="date_in" required></label>
                <label>Date de sortie <input type="date" name="date_out" required></label>
                <label>Chambre
                    <select name="id_chambre" required>
                        <?php foreach ($chambres as $ch): ?>
                            <option value="<?php echo (int) $ch['id']; ?>"><?php echo htmlspecialchars($ch['designation'], ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Client
                    <select name="id_client" required>
                        <?php foreach ($clients as $cl): ?>
                            <option value="<?php echo (int) $cl['id']; ?>"><?php echo htmlspecialchars(trim($cl['username'] . ' ' . $cl['lastname']), ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <div class="btn-row">
                    <button type="submit">Ajouter la réservation</button>
                </div>
            </form>
        <?php else: ?>
            <p class="hint">Ajoutez au moins une chambre et un client pour créer une réservation.</p>
        <?php endif; ?>

        <?php if (empty($reservations)): ?>
            <p class="empty">Aucune réservation.</p>
        <?php else: ?>
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Date entrée</th>
                            <th>Date sortie</th>
                            <th>Chambre</th>
                            <th>Client</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $r): ?>
                            <tr>
                                <td><?php echo (int) $r['id']; ?></td>
                                <td><?php echo htmlspecialchars(isset($r['dateIn']) ? $r['dateIn'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(isset($r['dateOut']) ? $r['dateOut'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(isset($chambreById[(int) $r['idChambre']]) ? $chambreById[(int) $r['idChambre']] : ('#' . (int) $r['idChambre']), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(isset($clientById[(int) $r['idClient']]) ? $clientById[(int) $r['idClient']] : ('#' . (int) $r['idClient']), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="actions-cell">
                                    <a class="btn-link" href="<?php echo htmlspecialchars($baseUrl . '?edit_reservation=' . (int) $r['id'], ENT_QUOTES, 'UTF-8'); ?>">Modifier</a>
                                    <form method="post" action="" onsubmit="return confirm('Supprimer cette réservation ?');">
                                        <input type="hidden" name="action" value="delete_reservation">
                                        <input type="hidden" name="id" value="<?php echo (int) $r['id']; ?>">
                                        <button type="submit" class="btn--danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</div>
</div>
</body>
</html>
