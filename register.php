<?php
session_start();
require "DataBase.php";

// Variable to store error/success messages
$message = "";

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form fields are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Create an instance of the DataBase class
        $db = new DataBase();

        // Register the user
        if ($db->registerUser($_POST['username'], $_POST['password'])) {
            // Store the username in the session
            $_SESSION['username'] = $_POST['username'];

            // Redirect to the chat page upon successful signup
            header("Location: chat.php");
            exit();
        } else {
            $message = "Signup Failed";
        }
    } else {
        $message = "All fields are required";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="chatbot.png" type="image/png">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body> 
    <div class="wallpaper"></div>
    <div class="container">
        <div class="transparent-box">
            <img src="chatbot.png" class="icon" alt="Chatbot Icon">
            <h2><i>Chatbot</i></h2>
        <div class="form-container">
            <h2><i>Signup</i></h2>
            <form action="register.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="signup">Signup</button>
                <p>Already have an account? <a href="login.php">Login now</a></p>
            </form>
            <?php echo isset($message) ? $message : ""; ?>
        </div>
    </div>
</div>
</body>
</html>
