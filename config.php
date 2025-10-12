<?php
/**
 * Configuration centralisée de l'application
 * Ce fichier est la SEULE source de vérité pour les versions autorisées
 * et autres paramètres partagés entre PHP et JavaScript
 */

// ⚠️ LISTE UNIQUE DES VERSIONS AUTORISÉES ⚠️
// Toute modification doit être faite ICI UNIQUEMENT
define('ALLOWED_VERSIONS', ['1.0', '1.1', '1.web']);

// Version de l'API
define('API_VERSION', '0.dev');

// Version du module (pages)
define('MODULE_VERSION', '0.12');

/**
 * Génère le code JavaScript pour injecter la configuration
 * À utiliser dans les pages HTML : <?php echo getJsConfig(); ?>
 */
function getJsConfig() {
    $config = [
        'allowedVersions' => ALLOWED_VERSIONS,
        'apiVersion' => API_VERSION,
        'moduleVersion' => MODULE_VERSION
    ];
    
    return '<script>window.APP_CONFIG = ' . json_encode($config, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';</script>';
}

/**
 * Vérifie si une version est autorisée
 */
function isVersionAllowed($version) {
    return in_array($version, ALLOWED_VERSIONS);
}
?>
