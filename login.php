<?php
session_start();
require "DataBase.php";

if (isset($_SESSION['user_id'])) {
    header("Location: chat.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $db = new DataBase();
        $userId = $db->loginUser($_POST['username'], $_POST['password']);
        if ($userId) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $_POST['username'];
            header("Location: chat.php");
            exit();
        } else {
            $message = "Login failed! Invalid username or password.";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="chatbot.png" type="image/png">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wallpaper"></div>
    <div class="container">
        <div class="transparent-A">
            <img src="chatbot.png" class="icon" alt="Chatbot Icon">
            <h2><i>Chatbot</i></h2>
        <div class="form-container">
            <h2><i>Login</i></h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <p><?php echo $message; ?></p>
            <p>Don't have an account? <a href="register.php">Sign up now</a></p>
        </div>
    </div>
</div>

</body>
</html>
