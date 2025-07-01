<?php
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeviceDen</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>DeviceDen</h1>
        <div class="header-buttons">
            <button><a href="home.php">Home</a></button>
            <?php if (isset($_SESSION['username'])): ?>
                <button id="logoutButton"><a href="logout.php">Logout</a></button>
            <?php else: ?>
                <button id="logoutButton"><a href="signup.php">Signup</a></button>
                <button id="logoutButton"><a href="login.php">Login</a></button>
            <?php endif; ?>
        </div>
    </header>

    <div class="grid-container">
        <div class="grid-item">
            <h2>Welcome to DeviceDen</h2>
            <a href="login.php"><button class="get-started-button">Get Started</button></a>
        </div>
    </div>

    <footer>
        &copy; 2024 DeviceDen. All rights reserved.
    </footer>

    <script>
        document.getElementById("logoutButton").addEventListener("click", function() {
            sessionStorage.removeItem("isLoggedIn");
            sessionStorage.removeItem("username");
            window.location.href = "login.php"; // Redirect to login page
        });

        const headerButtons = document.querySelectorAll(".header-buttons button:not(#logoutButton)");
        headerButtons.forEach(button => {
            button.addEventListener("click", function(event) {
                if (!sessionStorage.getItem("isLoggedIn")) {
                    event.preventDefault();
                    alert("Please log in first.");
                }
            });
        });
    </script>
</body>
</html>
<style>
    /* Reset default margin and padding */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-image: url('https://www.eiosys.com/wp-content/uploads/2021/11/blog-15-Best-Email-Marketing-tools-in-2021.webp');
      background-size: cover;
      background-position: center;
      height: 100vh; /* Make the body cover the viewport height */
      display: flex;
      flex-direction: column;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #333;
      color: white;
      padding: 10px 20px;
      width: 100%;
      position: fixed;
      top: 0;
      z-index: 1000;
    }

    .header-buttons button {
      padding: 10px;
      margin-right: 10px;
      cursor: pointer;
      background-color: #007bff;
      border: none;
      color: white;
      border-radius: 5px;
      text-decoration: none;
    }

    .header-buttons button:hover {
      background-color: #0056b3;
    }

    .grid-container {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      justify-content: center;
      width: 80%;
      max-width: 1200px;
      margin: 0 auto;
      text-align: center; /* Center align text inside grid items */
      margin-top: 100px; /* Space between header and content */
    }

    .grid-item {
      background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      text-align: center; /* Center align text within grid item */
    }

    .grid-item h2 {
      margin-bottom: 20px; /* Add space below heading */
    }

    .grid-item p {
      font-size: 18px;
      line-height: 1.6;
    }

    .get-started-button {
      margin-top: 20px;
      padding: 10px 20px;
      cursor: pointer;
      background-color: #007bff;
      border: none;
      color: white;
      border-radius: 5px;
      text-decoration: none;
    }

    .get-started-button:hover {
      background-color: #0056b3;
    }

    footer {
      background-color: #333;
      color: white;
      padding: 20px 0;
      text-align: center;
      position: fixed;
      bottom: 0;
      width: 100%;
    }
  </style>