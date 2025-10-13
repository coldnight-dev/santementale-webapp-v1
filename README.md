## 🧠 SanteMentale.org - Application Web v1

Application web de santé mentale avec outils interactifs pour le bien-être personnel.

### 🌟 Fonctionnalités

#### Outils disponibles
- 📝 **Routine quotidienne** - Suivi et gestion des habitudes quotidiennes
- 😊 **Journal des émotions** - Tracking émotionnel avec statistiques
- 🙏 **Journal de gratitude** - Cultiver la reconnaissance quotidienne
- 🔺 **Pyramide des besoins** - Évaluation basée sur Maslow
- ⚖️ **Balance décisionnelle** - Aide à la prise de décision

#### Caractéristiques techniques
- ✅ Système de validation de version centralisé
- 🔒 Stockage 100% local (localStorage)
- 🚫 Aucune collecte de données personnelles
- 📊 Système de statistiques avancées (en développement)
- 🏆 Système de rewards/achievements (en développement)

### 🛠️ Technologies

- **Backend** : PHP 7.x
- **Frontend** : JavaScript Vanilla, HTML5, CSS3
- **Serveur** : Linux (CentOS 7)
- **Stockage** : localStorage (navigateur)

### 📁 Structure du projet
/v1/ ├── config.php                    # Configuration centralisée ├── version_check.php             # Validation des versions (PHP) ├── index.php                     # Page d'accueil ├── parametres.php                # Paramètres utilisateur ├── js/ │   └── version-helper.js         # Validation JS + helpers ├── outils/ │   ├── index.php                 # Liste des outils │   ├── routine-quotidienne.php │   ├── journal-des-emotions.php │   ├── journal-de-gratitude.php │   ├── pyramide-des-besoins.php │   └── balance-decisionnelle.php └── errors/ └── 403.php                   # Gestion des erreurs
## 🚀 Installation

#### Prérequis
- Serveur Linux avec Apache/Nginx
- PHP 7.x ou supérieur
- Navigateur moderne avec support localStorage

#### Déploiement
```bash
# Cloner le dépôt
git clone https://github.com/coldnight-dev/santementale-webapp-v1.git

# Déplacer vers le répertoire web
sudo mv santementale-webapp-v1 /var/www/html/santementale.org/_app/v1/

# Configurer les permissions
sudo chown -R apache:apache /var/www/html/santementale.org/_app/v1/
sudo chmod -R 755 /var/www/html/santementale.org/_app/v1/
```

#### 🔧 Configuration
Les versions autorisées sont définies dans config.php :
define('ALLOWED_VERSIONS', ['1.0', '1.1', '1.web']);

#### 🔒 Sécurité et confidentialité
Anonymat total : Aucun compte requis
Données locales : Tout est stocké dans le navigateur
Pas de tracking : Aucune donnée envoyée à un serveur externe
Validation stricte : Système de contrôle de version à double validation

#### 📊 Roadmap
[ ] Système de statistiques avancées
[ ] Système de rewards/achievements
[ ] Export des données (JSON, CSV)
[ ] Mode sombre
[ ] PWA (Progressive Web App)
#### 🤝 Contribution
Ce projet est en développement actif. Les contributions sont les bienvenues !
