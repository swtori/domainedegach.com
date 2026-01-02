# Guide de Configuration - Domaine de Gach

## 1. Configuration EmailJS

### Étape 1 : Créer un compte EmailJS
1. Allez sur [https://www.emailjs.com/](https://www.emailjs.com/)
2. Créez un compte gratuit
3. Vérifiez votre email

### Étape 2 : Créer un Service Email
1. Dans le dashboard, allez dans "Email Services"
2. Cliquez sur "Add New Service"
3. Choisissez votre fournisseur d'email (Gmail, Outlook, etc.)
4. Suivez les instructions pour connecter votre compte email
5. Notez le **Service ID** (ex: `service_xxxxx`)

### Étape 3 : Créer un Template Email
1. Allez dans "Email Templates"
2. Cliquez sur "Create New Template"
3. Utilisez ce template :

**Subject :** Nouveau message depuis le site Domaine de Gach

**Content :**
```
Bonjour,

Vous avez reçu un nouveau message depuis le formulaire de contact du site Domaine de Gach.

De : {{from_name}}
Email : {{from_email}}
Téléphone : {{phone}}
Sujet : {{subject}}

Message :
{{message}}

---
Vous pouvez répondre directement à cet email pour contacter {{from_name}}.
```

4. Dans "Settings", configurez :
   - **To Email** : contact@domainedegach.com
   - **From Name** : Domaine de Gach
   - **Reply To** : {{reply_to}}

5. Notez le **Template ID** (ex: `template_xxxxx`)

### Étape 4 : Obtenir la Public Key
1. Allez dans "Account" > "General"
2. Copiez votre **Public Key** (ex: `xxxxxxxxxxxxx`)

### Étape 5 : Configurer le site
1. Ouvrez le fichier `js/contact.js`
2. Remplacez les valeurs suivantes :
   ```javascript
   const EMAILJS_SERVICE_ID = 'VOTRE_SERVICE_ID';
   const EMAILJS_TEMPLATE_ID = 'VOTRE_TEMPLATE_ID';
   const EMAILJS_PUBLIC_KEY = 'VOTRE_PUBLIC_KEY';
   ```

## 2. Configuration Google Maps

### Étape 1 : Obtenir les coordonnées GPS
1. Allez sur [Google Maps](https://www.google.com/maps)
2. Recherchez l'adresse du Domaine de Gach
3. Cliquez droit sur l'emplacement > "Plus d'infos sur cet endroit"
4. Notez les coordonnées (latitude, longitude)

### Étape 2 : Générer l'iframe
1. Allez sur [Google Maps Embed API](https://www.google.com/maps)
2. Recherchez votre adresse
3. Cliquez sur "Partager" > "Intégrer une carte"
4. Copiez le code iframe

### Étape 3 : Mettre à jour le site
1. Ouvrez le fichier `localiser.html`
2. Remplacez l'iframe existante par celle que vous venez de copier

## 3. Ajout des images

Créez les dossiers suivants et ajoutez vos images :

```
images/
├── hero-domaine.jpg          (1920x1080px recommandé)
├── chambre-suite.jpg          (800x600px recommandé)
├── chambre2.jpg
├── chambre3.jpg
├── chambre4.jpg
├── piscine.jpg
├── autour-vignes.jpg
├── autour-patrimoine.jpg
├── autour-nature.jpg
├── autour-gastronomie.jpg
├── autour-activites.jpg
└── autour-festivals.jpg
```

**Conseils pour les images :**
- Format : JPG ou WebP
- Taille optimale : 1920px de largeur maximum
- Compression : Optimisez les images pour le web (utilisez TinyPNG ou ImageOptim)
- Alt text : Les images ont déjà des descriptions dans le HTML

## 4. Musique d'ambiance (optionnel)

1. Créez un dossier `audio/`
2. Ajoutez un fichier `ambient.mp3` avec une musique d'ambiance douce
3. Format recommandé : MP3, 128kbps, durée 2-3 minutes (en boucle)

**Note :** La musique est désactivée par défaut. Les visiteurs peuvent l'activer s'ils le souhaitent.

## 5. Configuration PHP (optionnel)

Si vous souhaitez utiliser le fichier `php/content.php` pour alimenter le site en contenu dynamique :

1. Modifiez le fichier `php/content.php` avec vos vraies données
2. Pour les réseaux sociaux, mettez à jour les URLs
3. Pour les actualités, ajoutez vos vraies actualités
4. Pour les avis, vous pouvez :
   - Intégrer l'API Google Maps Reviews
   - Intégrer l'API TrustPilot
   - Ou ajouter manuellement les avis

## 6. Personnalisation des textes

Tous les textes sont dans les fichiers HTML. Vous pouvez les modifier directement :
- Descriptions des chambres
- Textes de la page "Autour du domaine"
- Contenu de la page d'accueil

## 7. Test du site

Avant de mettre en ligne :

1. ✅ Testez le formulaire de contact (vérifiez qu'EmailJS fonctionne)
2. ✅ Vérifiez que toutes les images s'affichent
3. ✅ Testez la navigation sur mobile
4. ✅ Vérifiez que la carte Google Maps s'affiche
5. ✅ Testez la musique d'ambiance (si ajoutée)
6. ✅ Vérifiez les liens de navigation

## 8. Déploiement

### Option 1 : Hébergement classique
- Téléversez tous les fichiers sur votre serveur web
- Assurez-vous que PHP est activé si vous utilisez `php/content.php`

### Option 2 : GitHub Pages
- Le site peut être déployé sur GitHub Pages (sans PHP)
- Pour utiliser PHP, vous devrez utiliser un service comme Netlify Functions ou Vercel

### Option 3 : Netlify / Vercel
- Déployez facilement depuis GitHub
- Support PHP disponible sur certains plans

## Support

Pour toute question, contactez : contact@domainedegach.com
