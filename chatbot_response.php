<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['response' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['response' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$input = file_get_contents('php://input');
$data = json_decode($input, true);
$message = $data['message'];
$ai = $data['ai'] ?? 'openai';
$model = $data['model'] ?? '';

function callOpenAI($message) {
    $apiKey = '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';
    $url = 'https://api.openai.com/v1/completions';

    $postData = json_encode([
        'model' => 'text-davinci-003',
        'prompt' => $message,
        'max_tokens' => 150,
        'temperature' => 0.7
    ]);

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ];

    return makeApiRequest($url, $postData, $headers);
}

function callClaude($message) {
    $apiKey = 'sk-proj-UVtW5XdJiBmMow2zzdUOT3BlbkFJ2YsO4BwQmYh7aBRfIaDg';
    $url = 'https://api.anthropic.com/v1/complete';

    $postData = json_encode([
        'prompt' => $message,
        'max_tokens_to_sample' => 150,
        'stop_sequences' => ['\n']
    ]);

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ];

    return makeApiRequest($url, $postData, $headers);
}

function callHuggingFace($message, $model) {
    $apiKey = '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';
    $url = 'https://api-inference.huggingface.co/models/' . $model;

    $postData = json_encode([
        'inputs' => $message
    ]);

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ];

    return makeApiRequest($url, $postData, $headers);
}

function makeApiRequest($url, $postData, $headers) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(['response' => 'Request Error:' . curl_error($ch)]);
        exit;
    }

    curl_close($ch);
    return json_decode($response, true);
}

$response = '';

switch ($ai) {
    case 'openai':
        $result = callOpenAI($message);
        $response = $result['choices'][0]['text'] ?? 'No response from OpenAI';
        break;
    case 'claude':
        $result = callClaude($message);
        $response = $result['completion'] ?? 'No response from Claude';
        break;
    case 'huggingface':
        if (empty($model)) {
            echo json_encode(['response' => 'Model is required for Hugging Face']);
            exit;
        }
        $result = callHuggingFace($message, $model);
        $response = $result[0]['generated_text'] ?? 'No response from Hugging Face';
        break;
    default:
        $response = 'Invalid AI selected';
        break;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatbot_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO messages (user_id, message, ai_response, ai_type) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $user_id, $message, $response, $ai);

if ($stmt->execute() === FALSE) {
    echo json_encode(['response' => 'Error saving message: ' . $conn->error]);
    exit;
}

$stmt->close();
$conn->close();

echo json_encode(['response' => $response]);
?>
