<?php
/**
 * SanteMentale.org - Save Handler Centralisé
 * Gère TOUS les logs avec skip INVISIBLE UNKNOWN
 */

require_once(__DIR__ . '/../config.php');

// Récupère UUID ou UNKNOWN
$uuid = $_GET['uuid'] ?? $_POST['uuid'] ?? 'unknown';

// ✅ SKIP INVISIBLE POUR UNKNOWN - PAGE CONTINUE !
if ($uuid === 'unknown') {
    return; // ✅ SILENCIEUX + CONTINU
}

// Nettoie UUID
$uuid = preg_replace('/[^a-f0-9-]/', '', strtolower($uuid));

// Validation UUID
if (strlen($uuid) !== 36 || !preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$/', $uuid)) {
    return; // ✅ SILENCIEUX + CONTINU
}

// Fichier cible
$file = __DIR__ . '/../logs/' . $uuid . '.dat';

// Récupère données POST/GET
$data = array_merge($_GET, $_POST);
$data['timestamp'] = date('Y-m-d H:i:s');
$data['uuid'] = $uuid;

// ✅ SAUVEGARDE ATOMIQUE
$jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
$tempFile = $file . '.tmp';

if (file_put_contents($tempFile, $jsonData, LOCK_EX | FILE_APPEND) !== false) {
    rename($tempFile, $file); // Atomic move
}

// ✅ FIN SILENCIEUSE - PAGE CONTINUE !
return;
?>
