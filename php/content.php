<?php
/**
 * Fichier PHP pour alimenter le site en contenu dynamique
 * Réseaux sociaux, actualités, preuves (avis, TrustPilot, etc.)
 */

header('Content-Type: application/json');

// Configuration
$config = [
    'from_email' => 'e.sarrail@orange.fr',
    'site_name' => 'Domaine de Gach'
];

// Fonction pour récupérer les réseaux sociaux
function getSocialNetworks() {
    return [
        [
            'name' => 'Facebook',
            'url' => 'https://www.facebook.com/domainedegach',
            'icon' => 'facebook'
        ],
        [
            'name' => 'Instagram',
            'url' => 'https://www.instagram.com/domainedegach',
            'icon' => 'instagram'
        ]
    ];
}

// Fonction pour récupérer les actualités
function getNews() {
    return [
        [
            'id' => 1,
            'title' => 'Nouvelle saison 2024',
            'date' => '2024-01-15',
            'content' => 'Le Domaine de Gach vous accueille pour une nouvelle saison riche en découvertes.',
            'image' => 'images/news1.jpg'
        ],
        [
            'id' => 2,
            'title' => 'Réouverture de la piscine',
            'date' => '2024-05-01',
            'content' => 'Notre piscine est maintenant ouverte pour la saison estivale.',
            'image' => 'images/piscine.jpg'
        ]
    ];
}

// Fonction pour récupérer les avis (peut être connecté à Google Maps API ou TrustPilot)
function getReviews() {
    return [
        [
            'id' => 1,
            'author' => 'Marie D.',
            'rating' => 5,
            'date' => '2024-03-10',
            'comment' => 'Un séjour magnifique dans un cadre authentique. La piscine est un vrai plus !',
            'source' => 'Google Maps'
        ],
        [
            'id' => 2,
            'author' => 'Jean P.',
            'rating' => 5,
            'date' => '2024-02-20',
            'comment' => 'Chambres confortables et accueil chaleureux. Nous recommandons vivement.',
            'source' => 'TrustPilot'
        ],
        [
            'id' => 3,
            'author' => 'Sophie L.',
            'rating' => 5,
            'date' => '2024-01-15',
            'comment' => 'Parfait pour se ressourcer. Le domaine viticole est magnifique.',
            'source' => 'Google Maps'
        ]
    ];
}

// Router simple
$action = $_GET['action'] ?? 'all';

switch ($action) {
    case 'social':
        echo json_encode([
            'success' => true,
            'data' => getSocialNetworks()
        ]);
        break;
    
    case 'news':
        echo json_encode([
            'success' => true,
            'data' => getNews()
        ]);
        break;
    
    case 'reviews':
        echo json_encode([
            'success' => true,
            'data' => getReviews()
        ]);
        break;
    
    case 'all':
    default:
        echo json_encode([
            'success' => true,
            'data' => [
                'social' => getSocialNetworks(),
                'news' => getNews(),
                'reviews' => getReviews()
            ]
        ]);
        break;
}
?>
