<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'DataBaseConfig.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$config = new DataBaseConfig();
$conn = new mysqli($config->servername, $config->username, $config->password, $config->databasename);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT message, ai_response, created_at FROM messages WHERE user_id = ? ORDER BY created_at ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="chatbot.png" type="image/png">
    <title>Chatbot</title>
    <link rel="stylesheet" href="styles1.css">
</head>
<body>
    <div class="chatbot-container">
        <div id="header">
            <img src="chatbot.png" class="icon" alt="Chatbot Icon">
            <h1><i>Chatbot</i></h1>
            <div class="user-info">
                <span>Welcome in <b>ASMAA AI</b>, <?php echo htmlspecialchars($username); ?></span>
                <a href="logout.php" class="a1">Logout</a>
            </div>
        </div>
        <div id="chatbot">
            <div id="conversation">
                <?php foreach ($messages as $msg): ?>
                    <div class="chatbot-message user-message">
                        <p class="chatbot-text"><?php echo htmlspecialchars($msg['message']); ?></p>
                    </div>
                    <div class="chatbot-message chatbot">
                        <p class="chatbot-text"><?php echo htmlspecialchars($msg['ai_response']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <form id="input-form">
            <div class="message-container">
                <select id="model-select">
                    <option value="" disabled>select model</option>
                    <option value="mistralai/Mixtral-8x7B-Instruct-v0.1">Mixtral-8x7B-Instruct-v0.1</option>
                    <option value="mistralai/Mistral-7B-Instruct-v0.20">Mistral-7B-Instruct-v0.20</option>
                    <option value="01-ai/Yi-1.5-34B-Chat">Yi-1.5-34B-Chat</option>
                    <option value="microsoft/Phi-3-mini-4k-instruct">Phi-3-mini-4k-instruct</option>
                </select>
                <div id="chatbox-container">
                    <input id="input-field" type="text" placeholder="Type your message here">
                    <button id="submit-button" type="submit">
                        <img class="send-icon" src="send-message.png" alt="">
                    </button>
                </div>
            </div>
        </form>
        
    </div>

    <script>
        const aiSelect = 'huggingface';
        const modelSelect = document.getElementById('model-select');

        const chatbot = document.getElementById('chatbot');
        const conversation = document.getElementById('conversation');
        const inputForm = document.getElementById('input-form');
        const inputField = document.getElementById('input-field');

        inputForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const input = inputField.value.trim();
            if (input === "") return;
            inputField.value = '';
            const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            let userMessage = document.createElement('div');
            userMessage.classList.add('chatbot-message', 'user-message');
            userMessage.innerHTML = `<p class="chatbot-text" sentTime="${currentTime}">${input}</p>`;
            conversation.appendChild(userMessage);

            userMessage.scrollIntoView({ behavior: "smooth" });

            fetch('chatbot_response.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: input, ai: aiSelect, model: modelSelect.value })
            })
            .then(response => response.json())
            .then(data => {
                let botMessage = document.createElement('div');
                botMessage.classList.add('chatbot-message', 'chatbot');
                botMessage.innerHTML = `<p class="chatbot-text" sentTime="${currentTime}">${data.response}</p>`;
                conversation.appendChild(botMessage);

                botMessage.scrollIntoView({ behavior: "smooth" });
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMessage = document.createElement('div');
                errorMessage.classList.add('chatbot-message', 'chatbot');
                errorMessage.innerHTML = `<p class="chatbot-text" sentTime="${currentTime}">Sorry, there was an error processing your message.</p>`;
                conversation.appendChild(errorMessage);

                errorMessage.scrollIntoView({ behavior: "smooth" });
            });
        });
    </script>
</body>
</html>
