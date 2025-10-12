/**
 * Bibliothèque de gestion des versions
 * À inclure dans toutes les pages après l'injection de window.APP_CONFIG
 * 
 * Usage:
 * <script src="/v1/js/version-helper.js"></script>
 * <script>
 *   const version = VersionHelper.init({ requireVersion: true });
 * </script>
 */

const VersionHelper = (function() {
    'use strict';
    
    const STORAGE_KEY = 'client_version';
    
    /**
     * Vérifie si window.APP_CONFIG est disponible
     */
    function checkConfig() {
        if (!window.APP_CONFIG || !window.APP_CONFIG.allowedVersions) {
            console.error('VersionHelper: window.APP_CONFIG non disponible');
            return false;
        }
        return true;
    }
    
    /**
     * Vérifie si une version est autorisée
     */
    function isVersionAllowed(version) {
        if (!checkConfig()) return false;
        return window.APP_CONFIG.allowedVersions.includes(version);
    }
    
    /**
     * Récupère la version depuis localStorage
     */
    function getStoredVersion() {
        return localStorage.getItem(STORAGE_KEY);
    }
    
    /**
     * Stocke la version dans localStorage
     */
    function setStoredVersion(version) {
        if (version) {
            localStorage.setItem(STORAGE_KEY, version);
        } else {
            localStorage.removeItem(STORAGE_KEY);
        }
    }
    
    /**
     * Nettoie une version invalide du localStorage
     */
    function cleanInvalidStoredVersion() {
        const stored = getStoredVersion();
        if (stored && !isVersionAllowed(stored)) {
            console.warn(`Version stockée invalide: ${stored}. Nettoyage...`);
            setStoredVersion(null);
            return true; // Version nettoyée
        }
        return false; // Aucun nettoyage nécessaire
    }
    
    /**
     * Récupère la version depuis l'attribut data-version du script
     * (injecté par PHP)
     */
    function getPhpVersion() {
        const script = document.querySelector('script[data-version]');
        return script ? script.getAttribute('data-version') : null;
    }
    
    /**
     * Initialise et valide la version
     * @param {Object} options
     * @param {boolean} options.requireVersion - Si true, redirige vers 403 si pas de version
     * @param {boolean} options.redirectWithVersion - Si true, redirige vers l'URL avec ?v= si version manquante
     * @param {string} options.phpVersion - Version fournie par PHP (alternative à data-version)
     * @returns {string|null} La version validée ou null si redirection
     */
    function init(options = {}) {
        const {
            requireVersion = true,
            redirectWithVersion = false,
            phpVersion = null
        } = options;
        
        if (!checkConfig()) {
            window.location.href = '/v1/errors/403.php?error=config-missing';
            return null;
        }
        
        // Nettoyer les versions invalides en localStorage
        cleanInvalidStoredVersion();
        
        // 1. Priorité à la version PHP (URL)
        const urlVersion = phpVersion || getPhpVersion();
        if (urlVersion) {
            setStoredVersion(urlVersion);
            return urlVersion;
        }
        
        // 2. Vérifier localStorage
        const storedVersion = getStoredVersion();
        if (storedVersion) {
            // Rediriger avec la version dans l'URL pour cohérence
            if (redirectWithVersion) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('v', storedVersion);
                window.location.href = currentUrl.toString();
                return null;
            }
            return storedVersion;
        }
        
        // 3. Aucune version trouvée
        if (requireVersion) {
            window.location.href = '/v1/errors/403.php?error=no-version';
            return null;
        }
        
        return null;
    }
    
    /**
     * Crée une URL avec le paramètre de version
     * @param {string} path - Chemin relatif ou absolu
     * @param {string} version - Version à ajouter (optionnel, utilise la version courante si absent)
     * @returns {string} URL complète avec paramètre v
     */
    function createVersionedUrl(path, version = null) {
        const v = version || getStoredVersion();
        if (!v) {
            console.warn('VersionHelper: Aucune version disponible pour créer l\'URL');
            return path;
        }
        
        const url = new URL(path, window.location.origin);
        url.searchParams.set('v', v);
        return url.toString();
    }
    
    /**
     * Met à jour tous les liens avec le paramètre de version
     * @param {string} selector - Sélecteur CSS (par défaut: 'a[href]')
     */
    function addVersionToLinks(selector = 'a[href]') {
        const version = getStoredVersion();
        if (!version) {
            console.warn('VersionHelper: Aucune version disponible pour mettre à jour les liens');
            return;
        }
        
        document.querySelectorAll(selector).forEach(link => {
            const href = link.getAttribute('href');
            
            // Ignorer les liens externes, ancres, et ceux avec déjà un paramètre v
            if (!href || 
                href.startsWith('http') || 
                href.startsWith('#') || 
                href.includes('?v=') ||
                href.startsWith('mailto:') ||
                href.startsWith('tel:')) {
                return;
            }
            
            // Ajouter le paramètre v
            const separator = href.includes('?') ? '&' : '?';
            link.href = `${href}${separator}v=${encodeURIComponent(version)}`;
        });
    }
    
    /**
     * Retourne les informations de version
     */
    function getVersionInfo() {
        if (!checkConfig()) return null;
        
        return {
            current: getStoredVersion(),
            allowed: window.APP_CONFIG.allowedVersions,
            api: window.APP_CONFIG.apiVersion,
            module: window.APP_CONFIG.moduleVersion
        };
    }
    
    /**
     * Affiche les informations de version dans la console
     */
    function logVersionInfo() {
        const info = getVersionInfo();
        if (!info) return;
        
        console.group('🔖 Version Info');
        console.log('Version courante:', info.current);
        console.log('Versions autorisées:', info.allowed);
        console.log('API:', info.api);
        console.log('Module:', info.module);
        console.groupEnd();
    }
    
    // API publique
    return {
        init,
        isVersionAllowed,
        getStoredVersion,
        setStoredVersion,
        createVersionedUrl,
        addVersionToLinks,
        getVersionInfo,
        logVersionInfo,
        cleanInvalidStoredVersion
    };
})();

// Export pour utilisation dans d'autres modules si nécessaire
if (typeof module !== 'undefined' && module.exports) {
    module.exports = VersionHelper;
}
