<?php
header('Content-Type: application/json');

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);
$message = $data['message'] ?? '';

if (empty($message)) {
    echo json_encode(['error' => 'Message is required']);
    exit;
}

$apiKey = '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';
$model = 'tngtech/tng-r1t-chimera:free';

$payload = [
    'model' => $model,
    'messages' => [
        [
            'role' => 'system',
            'content' => 'Você é um assistente especializado em inteligência competitiva para o agronegócio (UbyOn). Responda SEMPRE em Português do Brasil. Use formatação Markdown (negrito, listas, títulos, tabelas) para organizar suas respostas de forma clara, profissional e estruturada.'
        ],
        [
            'role' => 'user',
            'content' => $message
        ]
    ]
];

$ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
} else {
    if ($httpCode === 200) {
        $responseData = json_decode($response, true);
        $botMessage = $responseData['choices'][0]['message']['content'] ?? 'No response from AI.';
        echo json_encode(['reply' => $botMessage]);
    } else {
        echo json_encode(['error' => 'API Error: ' . $response]);
    }
}

curl_close($ch);
