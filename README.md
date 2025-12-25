# Domaine de Gach - Site Web

Site web pour le Domaine de Gach, chambres d'hôtes dans un domaine viticole.

## Structure du site

- **index.html** : Page d'accueil
- **chambres.html** : Liste des 4 chambres
- **chambre-suite.html, chambre-2.html, chambre-3.html, chambre-4.html** : Pages détaillées de chaque chambre
- **autour.html** : Page "Autour du domaine" avec agencement en quinconce
- **localiser.html** : Page avec carte Google Maps
- **contact.html** : Formulaire de contact avec EmailJS

## Configuration

### EmailJS

Pour activer le formulaire de contact, vous devez configurer EmailJS :

1. Créez un compte sur [EmailJS](https://www.emailjs.com/)
2. Créez un service email (Gmail, Outlook, etc.)
3. Créez un template d'email
4. Modifiez le fichier `js/contact.js` et remplacez :
   - `YOUR_SERVICE_ID` par votre Service ID
   - `YOUR_TEMPLATE_ID` par votre Template ID
   - `YOUR_PUBLIC_KEY` par votre Public Key

Le template EmailJS doit contenir les variables suivantes :
- `{{from_name}}`
- `{{from_email}}`
- `{{phone}}`
- `{{subject}}`
- `{{message}}`
- `{{reply_to}}`

### Google Maps

Dans `localiser.html`, remplacez l'URL de l'iframe Google Maps par les coordonnées réelles du domaine.

### Images

Créez un dossier `images/` et ajoutez les images suivantes :
- `hero-domaine.jpg` : Image principale pour la page d'accueil
- `chambre-suite.jpg`, `chambre2.jpg`, `chambre3.jpg`, `chambre4.jpg` : Photos des chambres
- `autour-vignes.jpg`, `autour-patrimoine.jpg`, `autour-nature.jpg`, `autour-gastronomie.jpg`, `autour-activites.jpg`, `autour-festivals.jpg` : Images pour la page "Autour du domaine"
- `piscine.jpg` : Photo de la piscine

### Musique d'ambiance

Créez un dossier `audio/` et ajoutez un fichier `ambient.mp3` pour la musique d'ambiance (optionnel).

## PHP - Contenu dynamique

Le fichier `php/content.php` permet d'alimenter le site en contenu dynamique :
- Réseaux sociaux
- Actualités
- Avis clients (Google Maps, TrustPilot)

Pour utiliser ce fichier, vous devez avoir un serveur PHP. Vous pouvez appeler l'API via JavaScript :

```javascript
fetch('php/content.php?action=reviews')
    .then(response => response.json())
    .then(data => {
        // Traiter les données
    });
```

Actions disponibles :
- `?action=social` : Réseaux sociaux
- `?action=news` : Actualités
- `?action=reviews` : Avis clients
- `?action=all` : Toutes les données

## Charte graphique

- **Couleurs principales** : Blanc, vert olive (#6B7A47), vert feuille (#8B9A6B), beige (#E8E3D5), brun doux (#A68B5B)
- **Typographie** : 
  - Titres : Playfair Display (serif)
  - Texte : Inter (sans-serif)
- **Style** : Sobre, élégant, aéré, avec beaucoup d'espace blanc

## Fonctionnalités

- ✅ Navigation responsive avec menu mobile
- ✅ Musique d'ambiance (désactivée par défaut)
- ✅ Formulaire de contact avec EmailJS
- ✅ Carte Google Maps
- ✅ Agencement en quinconce responsive pour "Autour du domaine"
- ✅ Pages détaillées pour chaque chambre avec tarifs
- ✅ Mentions de la piscine
- ✅ Optimisation SEO
- ✅ Design responsive

## Tarifs

- **Basse saison** (Mai, Juin, Septembre) : 60€/nuit
- **Haute saison** (Juillet, Août) : 70€/nuit

## Contact

Email : e.sarrail@orange.fr

## Déploiement

Le site peut être déployé sur n'importe quel hébergeur web supportant HTML/CSS/JS. Pour utiliser le fichier PHP, un hébergeur avec support PHP est nécessaire.
