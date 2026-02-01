<?php
require_once 'config.php';
requireLogin();

header('Content-Type: application/json');

// Carica variabili d'ambiente
if (file_exists(__DIR__ . '/.env')) {
    $env = parse_ini_file(__DIR__ . '/.env');
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

$text = $_POST['text'] ?? '';
$type = $_POST['type'] ?? 'content'; // title o content

if (!$text) {
    echo json_encode(['success' => false, 'message' => 'Testo mancante']);
    exit;
}

$apiKey = $_ENV['OPENAI_API_KEY'] ?? '';
if (!$apiKey) {
    echo json_encode(['success' => false, 'message' => 'Chiave API non configurata']);
    exit;
}

$prompt = $type === 'title' 
    ? "Migliora questo titolo per una news della Croce Rossa Italiana rendendolo più accattivante e professionale, mantieni lo stesso significato. Rispondi SOLO con il titolo migliorato, senza virgolette o spiegazioni:\n\n$text"
    : "Migliora questo testo per una news della Croce Rossa Italiana rendendolo più chiaro, professionale e coinvolgente. Mantieni il tono istituzionale ma umano. Correggi eventuali errori grammaticali. Rispondi SOLO con il testo migliorato:\n\n$text";

$data = [
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'system', 'content' => 'Sei un assistente che aiuta a migliorare i testi per la Croce Rossa Italiana. Scrivi in italiano in modo chiaro, professionale e coinvolgente.'],
        ['role' => 'user', 'content' => $prompt]
    ],
    'temperature' => 0.7,
    'max_tokens' => 500
];

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo json_encode(['success' => false, 'message' => 'Errore API OpenAI']);
    exit;
}

$result = json_decode($response, true);
$improvedText = $result['choices'][0]['message']['content'] ?? '';

echo json_encode(['success' => true, 'text' => trim($improvedText)]);
