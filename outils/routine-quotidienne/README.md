# Routine Quotidienne v0.12-alpha

**Application web modulaire de gestion de routine quotidienne**  
Développée par SanteMentale.org

---

## 📋 Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Architecture](#architecture)
3. [Installation](#installation)
4. [Structure des fichiers](#structure-des-fichiers)
5. [Fonctionnalités](#fonctionnalités)
6. [Guide du développeur](#guide-du-développeur)
7. [API JavaScript](#api-javascript)
8. [Sécurité](#sécurité)
9. [Performance](#performance)
10. [Accessibilité](#accessibilité)

---

## 🎯 Vue d'ensemble

Routine Quotidienne est une application web progressive permettant aux utilisateurs de créer, gérer et suivre leurs routines quotidiennes. L'application stocke toutes les données localement pour garantir la confidentialité.

### Caractéristiques principales

- ✅ Gestion de tâches quotidiennes
- 📊 Statistiques et suivi de progression
- 🔒 100% local, aucune donnée envoyée à des serveurs
- 📱 Responsive et accessible
- ⚡ Architecture modulaire pour des performances optimales

---

## 🏗️ Architecture

### Architecture Modulaire

L'application utilise une architecture modulaire avec chargement dynamique des vues :

```
index.php (Layout principal)
    ↓
Navigation dynamique
    ↓
Chargement AJAX des modules
    ↓
Injection dans le DOM
```

### Principes architecturaux

1. **Séparation des responsabilités** : Layout global vs logique métier
2. **Lazy loading** : Chargement à la demande des modules
3. **Encapsulation** : Chaque module gère son propre état
4. **Cache** : Mise en cache des vues chargées
5. **Progressive enhancement** : Fonctionnalités de base garanties

---

## 🚀 Installation

### Prérequis

- Serveur web (Apache, Nginx, etc.)
- PHP 7.4+
- Navigateur moderne avec support ES6+

### Installation simple

1. Cloner ou télécharger le repository
2. Placer les fichiers dans le répertoire web
3. Accéder à `index.php` via le navigateur

Aucune configuration de base de données nécessaire !

### Configuration

Modifier les constantes dans `index.php` si nécessaire :

```php
define('BASE_PATH', __DIR__);
define('VERSION', '0.12-alpha');
```

---

## 📁 Structure des fichiers

```
/outils/routine-quotidienne/
│
├── index.php                     # Point d'entrée principal
├── v0-12-alpha.changelog         # Notes de version
├── README.md                     # Ce fichier
│
├── assets/                       # Ressources globales
│   ├── css/
│   │   ├── global.css           # Styles globaux
│   │   ├── navigation.css       # Navigation (optionnel)
│   │   └── loader.css           # Loader (optionnel)
│   └── js/
│       └── app.js               # Utilitaires JavaScript globaux
│
└── view/                        # Modules de vues
    ├── today/                   # Vue "Aujourd'hui"
    │   └── index.php
    ├── tasks/                   # Vue "Tâches"
    │   └── index.php
    ├── stats/                   # Vue "Statistiques"
    │   └── index.php
    └── settings/                # Vue "Paramètres"
        └── index.php
```

---

## ⚙️ Fonctionnalités

### Vue "Aujourd'hui"
- Affichage des tâches du jour
- Marquage des tâches complétées
- Barre de progression
- Sauvegarde automatique dans localStorage

### Vue "Tâches"
- Création de tâches avec catégories
- Modification et suppression
- Horaires et notes optionnels
- Interface modale intuitive

### Vue "Statistiques"
- Série actuelle et meilleure série
- Taux de réussite global
- Calendrier visuel (30 jours)
- Graphique de progression hebdomadaire

### Vue "Paramètres"
- Toggle de notifications
- Export/Import JSON
- Informations de stockage
- Suppression des données

---

## 👨‍💻 Guide du développeur

### Créer un nouveau module

1. Créer un répertoire dans `/view/nom_module/`
2. Créer `index.php` avec la structure :

```php
<?php
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
    $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header('Location: ../../index.php');
    exit;
}
?>
<div class="view-content view-nom-module">
    <!-- HTML du module -->
</div>

<style>
    /* CSS spécifique au module */
</style>

<script>
(function() {
    'use strict';
    
    const NomModuleView = {
        init() {
            // Initialisation
        }
    };
    
    NomModuleView.init();
})();
</script>
```

3. Ajouter le module dans la navigation de `index.php`

### Conventions de code

#### PHP
- Utiliser les tags courts `<?php ?>`
- Validation systématique des requêtes AJAX
- Échappement des sorties avec `htmlspecialchars()`

#### JavaScript
- IIFE pour encapsulation
- Use strict mode
- Noms de variables descriptifs
- Commentaires pour les fonctions complexes

#### CSS
- Préfixer les classes par le nom du module
- Utiliser les variables CSS pour cohérence
- Mobile-first approach

### Utilisation de l'API JavaScript globale

```javascript
// Storage
RoutineApp.storage.set('key', value);
const data = RoutineApp.storage.get('key');

// Dates
const today = RoutineApp.date.today();
const formatted = RoutineApp.date.format(new Date());

// DOM
const escaped = RoutineApp.dom.escapeHtml(userInput);
RoutineApp.dom.toast('Message de succès', 'success');

// Validation
const isValid = RoutineApp.validate.notEmpty(value);

// Events
RoutineApp.events.on('custom:event', (data) => {
    console.log('Event received', data);
});
RoutineApp.events.emit('custom:event', { foo: 'bar' });

// Utils
const id = RoutineApp.utils.generateId('task');
const debounced = RoutineApp.utils.debounce(func, 300);
```

---

## 🔒 Sécurité

### Mesures implémentées

1. **Protection AJAX** : Vérification des en-têtes HTTP
2. **Échappement HTML** : Prévention XSS systématique
3. **Validation côté serveur** : Toutes les entrées validées
4. **Stockage local uniquement** : Pas d'exposition réseau
5. **Content Security Policy** : À implémenter selon besoins

### Bonnes pratiques

- Ne jamais faire confiance aux données utilisateur
- Toujours échapper les sorties HTML
- Valider les types de données
- Utiliser `RoutineApp.dom.escapeHtml()` pour le contenu dynamique

---

## ⚡ Performance

### Optimisations implémentées

1. **Lazy loading** : Modules chargés à la demande
2. **Cache** : Vues mises en cache après premier chargement
3. **Minification** : À faire en production
4. **Debouncing** : Sur les événements fréquents
5. **Event delegation** : Moins d'event listeners

### Métriques cibles

- Time to Interactive : < 2s
- First Contentful Paint : < 1s
- Lighthouse Score : > 90

### Surveillance

Utiliser `RoutineApp.performance` pour mesurer :

```javascript
RoutineApp.performance.start('operation');
// ... code ...
RoutineApp.performance.end('operation');
```

---

## ♿ Accessibilité

### Conformité WCAG 2.1 AA

- ✅ Contraste des couleurs suffisant
- ✅ Navigation au clavier complète
- ✅ ARIA labels sur éléments interactifs
- ✅ Focus visible
- ✅ Textes alternatifs
- ✅ Structure sémantique HTML5

### Support des technologies d'assistance

- Lecteurs d'écran (NVDA, JAWS, VoiceOver)
- Navigation clavier uniquement
- Zoom jusqu'à 200%
- Mode high contrast

### Test d'accessibilité

```bash
# Avec axe DevTools dans le navigateur
# Ou Lighthouse audit
```

---

## 🧪 Tests

### Tests manuels

1. Tester chaque vue individuellement
2. Vérifier le chargement AJAX
3. Tester les fonctionnalités localStorage
4. Vérifier la responsivité
5. Tester l'accessibilité clavier

### Tests automatisés (à venir)

- Tests unitaires JavaScript
- Tests d'intégration
- Tests E2E avec Playwright/Cypress

---

## 📦 Déploiement

### Checklist pre-production

- [ ] Minifier CSS et JavaScript
- [ ] Activer la compression GZIP
- [ ] Configurer le cache navigateur
- [ ] Désactiver le mode debug
- [ ] Tester sur navigateurs cibles
- [ ] Audit de sécurité
- [ ] Audit de performance
- [ ] Audit d'accessibilité

### Configuration serveur recommandée

```apache
# .htaccess
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css application/javascript
</IfModule>

<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

---

## 🐛 Débogage

### Mode debug

Activer dans `assets/js/app.js` :

```javascript
window.RoutineApp.config.debug = true;
```

Accéder aux utilitaires via console :

```javascript
RA.storage.get('tasks');
RA.debug.table(data);
```

---

## 📄 License

Propriétaire - SanteMentale.org © 2025

---

## 🤝 Contribution

Pour contribuer au projet :

1. Respecter l'architecture modulaire
2. Suivre les conventions de code
3. Documenter les nouvelles fonctionnalités
4. Tester exhaustivement
5. Soumettre via le repository GitHub

---

## 📞 Support

- **Site web** : https://santementale.org
- **Application** : https://app.santementale.org
- **Groupe Facebook** : SanteMentale.org

---

**Développé avec ❤️ par SanteMentale.org**
