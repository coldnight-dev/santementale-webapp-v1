<?php
// v1/share/upload.php - Upload des images de partage

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

if (!isset($_FILES['image'])) {
    echo json_encode(['success' => false, 'error' => 'No image provided']);
    exit;
}

$uploadDir = __DIR__ . '/../share/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$uid = bin2hex(random_bytes(8));
$extension = 'png';
$filename = $uid . '.' . $extension;
$filepath = $uploadDir . $filename;

if (move_uploaded_file($_FILES['image']['tmp_name'], $filepath)) {
    $metadata = [
        'created' => time(),
        'level' => $_POST['level'] ?? 0,
        'xp' => $_POST['xp'] ?? 0,
        'streak' => $_POST['streak'] ?? 0,
        'tasks' => $_POST['tasks'] ?? 0
    ];
    file_put_contents($uploadDir . $uid . '.json', json_encode($metadata));
    echo json_encode(['success' => true, 'uid' => 'v1' . $uid]);
} else {
    echo json_encode(['success' => false, 'error' => 'Upload failed']);
}
