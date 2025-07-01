<?php
session_start();

require 'db.php'; // Include the database connection file

$error = ''; // Variable to store error messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate username and password
    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } else {
        // Check for admin credentials
        if ($username === 'admin' && $password === 'admin') {
            // Create a session for the admin
            $_SESSION['username'] = 'admin';
            $_SESSION['user_id'] = 0; // Optional: Set a special ID for admin

            // Redirect to ahome.php for admin
            header("Location: ahome.php");
            exit();
        } else {
            try {
                // Fetch user from the database
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    // Create a session for the user
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_id'] = $user['id']; // Store user ID in session if needed

                    // Redirect to home.php after successful login
                    header("Location: home.php");
                    exit();
                } else {
                    $error = 'Invalid username or password.';
                }
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
        <a href="signup.php" class="signup-button">Sign Up</a>
    </div>
</body>
</html>
<style>
    body {
        font-family: Arial, sans-serif;
        background-image: url('https://images.pexels.com/photos/1406282/pexels-photo-1406282.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1=');
        background-size: cover;
        background-position: center;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .login-container {
        background-color: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    h2 {
        margin-bottom: 20px;
    }
    .error {
        color: red;
        margin-bottom: 10px;
    }
    input[type="text"],
    input[type="password"],
    input[type="submit"],
    .signup-button {
        width: calc(100% - 22px);
        margin: 10px 0;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        text-decoration: none;
        display: inline-block;
    }
    input[type="text"],
    input[type="password"] {
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
    input[type="submit"],
    .signup-button {
        background-color: #007bff;
        color: #fff;
    }
    input[type="submit"]:hover,
    .signup-button:hover {
        background-color: #0056b3;
    }
</style>