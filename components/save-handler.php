<?php
/**
 * SanteMentale.org - Save Handler Centralisé
 * JSON PUR avec ob_clean() + skip UNKNOWN
 */

require_once(__DIR__ . '/../config.php');

// Récupère UUID
$uuid = $_POST['device_uuid'] ?? 'unknown';

// ✅ SKIP INVISIBLE POUR UNKNOWN
if ($uuid === 'unknown') {
    return; // ✅ CONTINU PAGE NORMALE
}

// Nettoie UUID
$uuid = preg_replace('/[^a-f0-9-]/', '', strtolower($uuid));

// Validation UUID
if (strlen($uuid) !== 36 || !preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$/', $uuid)) {
    return; // ✅ CONTINU
}

// Logs dir
$logsDir = __DIR__ . '/../logs/';
if (!is_dir($logsDir)) mkdir($logsDir, 0755, true);

$file = $logsDir . $uuid . '.dat';

// Données
$data = $_POST;
$data['timestamp'] = date('Y-m-d H:i:s');
$data['uuid'] = $uuid;

// Sauvegarde
$jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
$tempFile = $file . '.tmp';

$success = (file_put_contents($tempFile, $jsonData, LOCK_EX | FILE_APPEND) !== false) && rename($tempFile, $file);

// ✅ JSON PUR + NETTOYAGE BUFFER
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// CRUCIAL : Nettoie TOUT buffer avant JSON
if (ob_get_level()) ob_clean();

echo json_encode([
    'status' => $success ? 'success' : 'error',
    'message' => $success ? 'Data saved' : 'Write failed'
]);

// ✅ STOP TOTAL - PAS DE CONTINU !
exit(0);
?>
