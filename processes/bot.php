<?php
// bot.php
require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json');

$geminiApiKey = appEnvironment('GEMINI_API_KEY');
if ($geminiApiKey === null || trim($geminiApiKey) === '') {
    http_response_code(503);
    echo json_encode(['error' => 'AI service is not configured.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

$inputData = json_decode(file_get_contents('php://input'), true);
$userMessage = is_array($inputData) && isset($inputData['message']) && is_string($inputData['message'])
    ? trim($inputData['message'])
    : '';

if (empty($userMessage)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message cannot be empty.']);
    exit;
}

$websiteKnowledge = "You are an expert customer support agent for our website: 'Vulneratrack'. Use the following verified facts to answer user queries: - Users' vulnerability to floods are measured through statuses such as: pwd, elderly, child, adult, etc. - Vulnerability score is also measured through local risk factors such as incoming typhoons or clogged drainages. - Reports are submitted through the 'Report' page, where users can upload images and provide descriptions of the issue. - Location: Our services mainly covers Celine Homes, East Homes, and Purok Carvic as they are the most flood-prone areas. - Live Support: Users can speak to a human by calling the government information number present in home page. If the answer is not here, politely say you don't know.";

$websiteKnowledge .= "\n\nUser Question: " . $userMessage;
$payload = [
    "contents" => [
        [
            "role" => "user",
            "parts" => [["text" => $websiteKnowledge]]
        ]
    ]
];

$model = 'gemini-3.5-flash-lite';
$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'x-goog-api-key: ' . trim($geminiApiKey),
    ],
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($response === false || $httpCode < 200 || $httpCode >= 300) {
    error_log("Gemini API request failed with HTTP status {$httpCode}" . ($curlError !== '' ? ": {$curlError}" : '.'));
    http_response_code(502);
    echo json_encode(['error' => 'The AI service could not complete your request. Please try again later.']);
    exit;
}

$responseData = json_decode($response, true);
$botReply = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? "Sorry, I am having trouble understanding right now.";

echo json_encode(['reply' => $botReply]);
