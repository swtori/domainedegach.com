# Structure du Projet - Domaine de Gach

## Arborescence des fichiers

```
domaineDeGach/
│
├── index.html              # Page d'accueil
├── chambres.html           # Liste des 4 chambres
├── chambre-suite.html      # Page détaillée - Suite
├── chambre-2.html          # Page détaillée - Chambre 2
├── chambre-3.html          # Page détaillée - Chambre 3
├── chambre-4.html          # Page détaillée - Chambre 4
├── autour.html             # Page "Autour du domaine" (quinconce)
├── localiser.html          # Page avec Google Maps
├── contact.html            # Formulaire de contact avec EmailJS
│
├── css/
│   └── style.css          # Styles principaux (charte graphique)
│
├── js/
│   ├── main.js            # Navigation, musique, animations
│   └── contact.js         # Gestion du formulaire EmailJS
│
├── php/
│   └── content.php        # API pour contenu dynamique (réseaux, actualités, avis)
│
├── images/                # À créer - Images du site
│   ├── hero-domaine.jpg
│   ├── chambre-suite.jpg
│   ├── chambre2.jpg
│   ├── chambre3.jpg
│   ├── chambre4.jpg
│   ├── piscine.jpg
│   ├── autour-vignes.jpg
│   ├── autour-patrimoine.jpg
│   ├── autour-nature.jpg
│   ├── autour-gastronomie.jpg
│   ├── autour-activites.jpg
│   └── autour-festivals.jpg
│
├── audio/                 # À créer - Musique d'ambiance (optionnel)
│   └── ambient.mp3
│
├── README.md              # Documentation principale
├── CONFIGURATION.md       # Guide de configuration détaillé
├── STRUCTURE.md           # Ce fichier
└── .gitignore            # Fichiers à ignorer par Git
```

## Pages principales

### 1. Accueil (index.html)
- Hero section avec image du domaine
- Introduction
- Points forts (chambres, domaine viticole, piscine, nature)
- Aperçu des 4 chambres
- Call-to-action

### 2. Nos Chambres (chambres.html)
- Liste des 4 chambres avec photos
- Liens vers les pages détaillées
- Mention de la piscine

### 3. Pages détaillées des chambres
Chaque chambre a sa propre page avec :
- Grande photo
- Description détaillée
- Équipements
- Tarifs (basse/haute saison)
- Bouton de réservation

### 4. Autour du domaine (autour.html)
- Agencement en quinconce responsive
- 6 sections avec images et textes
- Découverte de la région

### 5. Nous localiser (localiser.html)
- Carte Google Maps intégrée
- Informations pratiques
- Plan d'accès

### 6. Contact (contact.html)
- Formulaire de contact
- Intégration EmailJS
- Informations de contact

## Fonctionnalités JavaScript

### main.js
- Navigation mobile (menu hamburger)
- Musique d'ambiance (toggle)
- Smooth scroll
- Animations au scroll (Intersection Observer)

### contact.js
- Gestion du formulaire de contact
- Envoi via EmailJS
- Messages de succès/erreur

## Styles CSS

### Charte graphique
- **Couleurs** : Blanc, vert olive, vert feuille, beige, brun
- **Typographie** : Playfair Display (titres), Inter (texte)
- **Style** : Sobre, élégant, aéré

### Responsive
- Mobile-first
- Breakpoints : 768px, 1024px
- Menu mobile
- Grilles adaptatives

## Configuration requise

1. **EmailJS** : Service ID, Template ID, Public Key
2. **Google Maps** : Coordonnées GPS et iframe
3. **Images** : Toutes les images dans le dossier `images/`
4. **Audio** (optionnel) : Fichier MP3 dans `audio/`

## Prochaines étapes

1. ✅ Structure HTML créée
2. ✅ CSS avec charte graphique
3. ✅ JavaScript fonctionnel
4. ⏳ Ajouter les images réelles
5. ⏳ Configurer EmailJS
6. ⏳ Configurer Google Maps
7. ⏳ Ajouter musique d'ambiance (optionnel)
8. ⏳ Personnaliser les textes
9. ⏳ Tester sur différents navigateurs
10. ⏳ Déployer en ligne
