<?php
/** @var Chambre[] $chambres */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Chambres - Fake DB (test)</title>
</head>
<body>
    <h1>Liste des chambres (fake DB)</h1>

    <?php if (empty($chambres)): ?>
        <p>Aucune chambre trouvée.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($chambres as $chambre): ?>
                <li>
                    <?= htmlspecialchars($chambre->getDesignation(), ENT_QUOTES, 'UTF-8') ?>
                    — <?= number_format($chambre->getPrix(), 2, ',', ' ') ?> €
                    — max <?= (int) $chambre->getCapaciteMax() ?> personnes
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>


