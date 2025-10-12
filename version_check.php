<?php
/**
 * Validation centralisée de la version client
 * À inclure avec require_once() dans toutes les pages nécessitant une vérification
 * 
 * Utilise config.php comme source unique des versions autorisées
 */

// Charger la configuration
require_once(__DIR__ . '/config.php');

// Récupération de la version depuis GET
$clientVersion = $_GET['v'] ?? null;

// Si une version est fournie, la valider
if ($clientVersion !== null) {
    if (!isVersionAllowed($clientVersion)) {
        // Version invalide : redirection avec code d'erreur
        $errorData = urlencode($clientVersion);
        header("Location: /v1/errors/403.php?error=bad-version&data=$errorData");
        exit;
    }
} else {
    // Aucune version fournie : redirection avec code d'erreur
    header('Location: /v1/errors/403.php?error=no-version');
    exit;
}

// Si on arrive ici, la version est valide
// La variable $clientVersion est disponible pour la page qui inclut ce fichier
?>
