<?php
/** @var array $chambres */
/** @var array $clients */
/** @var array $reservations */
/** @var bool $dbConnected */
/** @var array $currentUser */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domaine de Gach — Chambres, clients, réservations</title>
</head>
<body>
    <h1>Domaine de Gach</h1>
    <p>Connecté en tant que <strong><?php echo htmlspecialchars(isset($currentUser['username']) ? $currentUser['username'] : '', ENT_QUOTES, 'UTF-8'); ?></strong> — <a href="?action=logout">Déconnexion</a></p>
    <?php if (isset($dbConnected) && !$dbConnected): ?>
        <p style="background:#fcc; padding:0.5em;"><strong>Base de données non connectée.</strong> Vérifier <code>Config/database.php</code> (mot de passe dans <code>DB_PASS</code>, nom de la base <code>dbs15409847</code>, tables <code>CLIENTS</code> / <code>CHAMBRES</code> / <code>RESERVATIONS</code> en majuscules).<?php if (function_exists('getPdoError') && getPdoError() !== null): ?> <br>Erreur MySQL/PDO : <code><?php echo htmlspecialchars(getPdoError(), ENT_QUOTES, 'UTF-8'); ?></code><?php endif; ?></p>
    <?php endif; ?>

    <h2>Chambres</h2>
    <?php if (empty($chambres)): ?>
        <p>Aucune chambre.</p>
    <?php else: ?>
        <table>
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
    <?php endif; ?>

    <h2>Clients</h2>
    <?php if (isset($_GET['added']) && $_GET['added'] === '1'): ?>
        <p style="color:green;">Client ajouté.</p>
    <?php endif; ?>
    <?php if (isset($_GET['error_add']) && $_GET['error_add'] === '1'): ?>
        <p style="color:red;">Erreur lors de l'ajout (connexion BDD ou email déjà utilisé).</p>
    <?php endif; ?>
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] === '1'): ?>
        <p style="color:green;">Client supprimé.</p>
    <?php endif; ?>
    <?php if (isset($_GET['error_delete']) && $_GET['error_delete'] === '1'): ?>
        <p style="color:red;">Erreur lors de la suppression.</p>
    <?php endif; ?>
    <form method="post" action="">
        <input type="hidden" name="action" value="add_client">
        <label>Prénom <input type="text" name="username" required maxlength="255"></label>
        <label>Nom <input type="text" name="lastname" required maxlength="255"></label>
        <label>Tél <input type="text" name="tel" required maxlength="20"></label>
        <label>Email <input type="email" name="email" required maxlength="70"></label>
        <button type="submit">Ajouter un client</button>
    </form>
    <?php if (empty($clients)): ?>
        <p>Aucun client.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Tél</th>
                    <th>Email</th>
                    <th>Action</th>
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
                        <td>
                            <form method="post" action="" style="display:inline;" onsubmit="return confirm('Supprimer ce client ?');">
                                <input type="hidden" name="action" value="delete_client">
                                <input type="hidden" name="id" value="<?php echo (int) $c['id']; ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h2>Réservations</h2>
    <?php if (empty($reservations)): ?>
        <p>Aucune réservation.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Date entrée</th>
                    <th>Date sortie</th>
                    <th>Id chambre</th>
                    <th>Id client</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?php echo (int) $r['id']; ?></td>
                        <td><?php echo htmlspecialchars(isset($r['dateIn']) ? $r['dateIn'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars(isset($r['dateOut']) ? $r['dateOut'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo (int) $r['idChambre']; ?></td>
                        <td><?php echo (int) $r['idClient']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
