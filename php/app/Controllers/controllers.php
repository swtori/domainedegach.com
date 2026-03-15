<?php

// Contrôleur très simple pour tester l'affichage des chambres avec la fake DB

require_once __DIR__ . '/../Models/models.php';
require_once __DIR__ . '/../Models/fakeDb.php';

/**
 * Retourne une liste d'objets Chambre construits à partir de la fake DB.
 *
 * @return Chambre[]
 */
function getAllChambresFromFakeDb(): array
{
    global $CHAMBRES;

    $result = [];
    foreach ($CHAMBRES as $row) {
        $result[] = new Chambre(
            $row['id'],
            $row['designation'],
            (float) $row['prix'],
            (int) $row['capaciteMax']
        );
    }

    return $result;
}

// Pour l’instant, ce fichier sert aussi de point d’entrée de test.
$chambres = getAllChambresFromFakeDb();

require __DIR__ . '/../Views/chambres.view.php';

