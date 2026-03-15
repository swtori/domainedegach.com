<?php
/** @var array $chambres */
/** @var array $clients */
/** @var array $reservations */
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
