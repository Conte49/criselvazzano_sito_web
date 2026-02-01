<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$type = $_GET['type'] ?? 'posts';
$dataDir = __DIR__ . '/../data';

if ($type === 'posts') {
    $file = $dataDir . '/posts.json';
} elseif ($type === 'media') {
    $file = $dataDir . '/media.json';
} else {
    echo json_encode([]);
    exit;
}

if (file_exists($file)) {
    echo file_get_contents($file);
} else {
    echo json_encode([]);
}
