<?php
// bot.php
session_start();
session_write_close(); // Safely close session to prevent file locks

header('Content-Type: application/json');

// Replace with your actual Gemini API Key
define('GEMINI_API_KEY', 'AQ.Ab8RN6J1byaPkGLtk-_yMdR5JlvPGAjKvrwP1QNajHRPUPdB-Q');
define('GEMINI_MODEL', 'gemini-1.5-flash');

$inputData = json_decode(file_get_contents('php://input'), true);
$userMessage = isset($inputData['message']) ? trim($inputData['message']) : '';

if (empty($userMessage)) {
    echo json_encode(["error" => "Message cannot be empty."]);
    exit;
}

$websiteKnowledge = "You are an expert customer support agent for our website: 'Vulneratrack'. Use the following verified facts to answer user queries: - Users' vulnerability to floods are measured through statuses such as: pwd, elderly, child, adult, etc. - Vulnerability score is also measured through local risk factors such as incoming typhoons or clogged drainages. - Reports are submitted through the 'Report' page, where users can upload images and provide descriptions of the issue. - Location: Our services mainly covers Celine Homes, East Homes, and Purok Carvic as they are the most flood-prone areas. - Live Support: Users can speak to a human by calling the government information number present in home page. If the answer is not here, politely say you don't know.";

$payload = [
    "contents" => [
        [
            "role" => "user",
            "parts" => [["text" => $websiteKnowledge . "\n\nUser Question: " . $userMessage]]
        ]
    ]
];

// FIXED: Clean, direct URL parameter query string which works flawlessly on live servers
$url = "https://generativelanguage.googleapis.com/v1beta/models/" . GEMINI_MODEL . ":generateContent?key=" . trim(GEMINI_API_KEY);

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_SSL_VERIFYPEER => true, // Keep true for security on production servers
    CURLOPT_SSL_VERIFYHOST => 2
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || $httpCode !== 200) {
    echo json_encode([
        "error" => "API Request Failed (Status $httpCode).",
        "details" => json_decode($response, true)
    ]);
    exit;
}

$responseData = json_decode($response, true);

// FIXED: Exact structural path parsing fix for Gemini's JSON return structure
$botReply = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? "Sorry, I am having trouble understanding right now.";

echo json_encode(["reply" => $botReply]);
