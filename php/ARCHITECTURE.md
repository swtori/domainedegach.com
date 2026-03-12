# Architecture du Projet - Domaine de Gach

```
domainedegach.com/
│
├── index.html
├── chambres.html
├── chambre-suite.html
├── chambre-denis.html
├── chambre-creole.html
├── chambre-geo.html
├── autour.html
├── localiser.html
├── contact.html
├── mentions-legales.html
│
├── css/
│   └── style.css
│
├── js/
│   ├── main.js
│   ├── carousel.js
│   └── contact.js
│
├── php/
│   ├── content.php
│   └── gachDb.sql
│
└── img/
    ├── img_autour/
    │   ├── canal.jpg
    │   ├── cassoulet.jpg
    │   ├── citecarca.jpg
    │   ├── ecluse.jpg
    │   ├── lastours.jpg
    │   ├── lauragais1.jpg
    │   └── vignes1.jpg
    │
    ├── img_background/
    │   ├── arbreGach.jpg
    │   ├── behindGach.jpg
    │   ├── IMG-20240711-WA0000.jpg
    │   ├── IMG-20240711-WA0001.jpg
    │   ├── IMG-20240711-WA0002.jpg
    │   ├── interior.jpg
    │   ├── interior2.jpg
    │   └── interior3.jpg
    │
    ├── img_gach/
    │   ├── backgroundGach.jpg
    │   ├── lastGach.png
    │   ├── logo.webp
    │   ├── logo2.png
    │   ├── logoAncien.webp
    │   ├── piscine.jpg
    │   └── x.png
    │
    ├── img_localisation/
    │   └── tel.png
    │
    ├── img_piscine/
    │   └── piscine.png
    │
    ├── img_room/
    │   ├── _miniatures/
    │   │   ├── creole.webp
    │   │   ├── denis.webp
    │   │   ├── geo.webp
    │   │   └── suite.webp
    │   │
    │   ├── creole/
    │   │   ├── creole_1.jpg
    │   │   ├── creole_2.jpg
    │   │   ├── creole_3.jpg
    │   │   ├── creole_4.jpg
    │   │   └── creole_mobile.jpg
    │   │
    │   ├── denis/
    │   │   ├── denis_1.jpg
    │   │   ├── denis_2.jpg
    │   │   ├── denis_3.jpg
    │   │   └── denis_4.jpg
    │   │
    │   ├── geo/
    │   │   ├── geo_1.jpg
    │   │   ├── geo_2.jpg
    │   │   ├── geo_3.jpg
    │   │   └── geo_4.jpg
    │   │
    │   ├── suite/
    │   │   ├── suite_1.jpeg
    │   │   ├── suite_2.jpeg
    │   │   ├── suite_3.jpeg
    │   │   ├── suite_4.jpeg
    │   │   ├── suite_5.jpeg
    │   │   └── suite_6.jpeg
    │   │
    │   └── àtrier/
    │       └── iloveimg-compressed/
    │           └── geo_1.jpg
    │
    └── img_seeMore/
        ├── buttonLeft.png
        ├── buttonRight.png
        └── croix.png
```

## Description des dossiers

### Pages HTML
- **index.html** : Page d'accueil
- **chambres.html** : Liste des chambres
- **chambre-*.html** : Pages détaillées de chaque chambre (suite, denis, creole, geo)
- **autour.html** : Page "Autour du domaine"
- **localiser.html** : Page avec carte Google Maps
- **contact.html** : Formulaire de contact
- **mentions-legales.html** : Mentions légales

### Assets
- **css/** : Feuille de style principale
- **js/** : Scripts JavaScript (navigation, carousel, contact)
- **php/** : Scripts PHP et base de données SQL

### Images
- **img_autour/** : Images pour la page "Autour du domaine"
- **img_background/** : Images de fond
- **img_gach/** : Logo et images générales du domaine
- **img_localisation/** : Images pour la localisation
- **img_piscine/** : Images de la piscine
- **img_room/** : Images des chambres
  - **_miniatures/** : Miniatures des chambres
  - **creole/**, **denis/**, **geo/**, **suite/** : Images détaillées par chambre
- **img_seeMore/** : Icônes et boutons

