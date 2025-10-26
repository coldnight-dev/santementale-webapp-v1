<?php
/**
 * API de logging des erreurs JavaScript
 * Capture les erreurs frontend et les enregistre côté serveur
 */

// Headers CORS et JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Log de debug pour vérifier que le script est appelé
error_log('[LOG-ERROR.PHP] Script appelé - Method: ' . $_SERVER['REQUEST_METHOD']);

// Gérer les requêtes OPTIONS (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Gérer GET pour vérifier que le script fonctionne
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'success' => true,
        'message' => 'API de logging fonctionnelle',
        'time' => date('Y-m-d H:i:s'),
        'logFile' => __DIR__ . '/js-errors.log',
        'writable' => is_writable(__DIR__)
    ]);
    exit;
}

// Accepter seulement POST pour le logging
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Lire les données JSON
$input = file_get_contents('php://input');
error_log('[LOG-ERROR.PHP] Input reçu: ' . substr($input, 0, 200));

$data = json_decode($input, true);

if (!$data) {
    error_log('[LOG-ERROR.PHP] JSON invalide');
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

// Extraire les informations
$errorType = $data['type'] ?? 'unknown';
$message = $data['message'] ?? 'No message';
$url = $data['url'] ?? 'Unknown URL';
$line = $data['line'] ?? 'N/A';
$column = $data['column'] ?? 'N/A';
$stack = $data['stack'] ?? 'No stack trace';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
$timestamp = date('Y-m-d H:i:s');
$clientTime = $data['timestamp'] ?? $timestamp;

// Contexte supplémentaire si disponible
$context = '';
if (isset($data['consoleLog']) && is_array($data['consoleLog'])) {
    $context .= "  Console Log (dernières entrées):\n";
    foreach (array_slice($data['consoleLog'], -10) as $log) {
        $logMsg = is_array($log) ? ($log['msg'] ?? 'N/A') : $log;
        $context .= "    - " . substr($logMsg, 0, 200) . "\n";
    }
}

if (isset($data['state'])) {
    $context .= "  State: " . json_encode($data['state']) . "\n";
}

if (isset($data['viewName'])) {
    $context .= "  View: " . $data['viewName'] . "\n";
}

// Créer le répertoire de logs si nécessaire
$logDir = __DIR__;
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

$logFile = $logDir . '/js-errors.log';

// Vérifier les permissions
if (file_exists($logFile) && !is_writable($logFile)) {
    error_log('[LOG-ERROR.PHP] Fichier non writable: ' . $logFile);
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Log file not writable']);
    exit;
}

// Formater le message de log
$logEntry = sprintf(
    "[%s] [%s] %s\n" .
    "  URL: %s (Line: %s, Column: %s)\n" .
    "  Message: %s\n" .
    "  Stack: %s\n" .
    "  User-Agent: %s\n" .
    "  Client Time: %s\n" .
    "%s" .
    "  ---\n\n",
    $timestamp,
    strtoupper($errorType),
    $message,
    $url,
    $line,
    $column,
    $message,
    substr($stack, 0, 500),
    $userAgent,
    $clientTime,
    $context
);

// Écrire dans le fichier de log
$success = @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);

error_log('[LOG-ERROR.PHP] Écriture dans ' . $logFile . ' - Success: ' . ($success ? 'OUI' : 'NON'));

if ($success !== false) {
    // Limiter la taille du fichier
    limitLogSize($logFile, 1000);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Error logged successfully',
        'file' => $logFile,
        'size' => filesize($logFile)
    ]);
} else {
    error_log('[LOG-ERROR.PHP] ERREUR écriture fichier');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to write log',
        'file' => $logFile,
        'dir_writable' => is_writable($logDir)
    ]);
}

function limitLogSize($file, $maxLines) {
    if (!file_exists($file)) return;
    
    $lines = file($file);
    if (count($lines) > $maxLines) {
        $lines = array_slice($lines, -$maxLines);
        @file_put_contents($file, implode('', $lines), LOCK_EX);
    }
}
