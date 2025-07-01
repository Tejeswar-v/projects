<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeviceDen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>DeviceDen</h1>
        <div class="header-buttons">
            <button><a href="home.php">Home</a></button>
            <button><a href="cart.php">Cart</a></button>
            <button><a href="acc.php">Account</a></button>
            <button id="logoutButton"><a href="logout.php">Logout</a></button>
        </div>
    </header>

    <div class="grid-container">
        <div class="grid-item">
            <a href="ha.php?cat=home">
                <img src="https://t3.ftcdn.net/jpg/01/67/14/58/360_F_167145898_LKW1gwGhCvPOLWw45z5xurzRh4TfGX0R.jpg" alt="Home Appliances">
                <h2>Home Appliances</h2>
            </a>
        </div>
        <div class="grid-item">
            <a href="ha.php?cat=laptop">
                <img src="https://cdn-dynmedia-1.microsoft.com/is/image/microsoftcorp/MSFT-Surfcae-laptops-models-hero-poster?scl=1" alt="Laptops">
                <h2>Laptops</h2>
            </a>
        </div>
        <div class="grid-item">
            <a href="ha.php?cat=mobile">
                <img src="https://cms-assets.bajajfinserv.in/is/image/bajajfinance/made-in-india-mobiles?scl=1" alt="Mobiles">
                <h2>Mobiles</h2>
            </a>
        </div>
        <div class="grid-item">
            <a href="ha.php?cat=accessory">
                <img src="https://5.imimg.com/data5/YT/RA/MY-36315460/mobile-accessories-all-types-mobile-accessories-available.jpg" alt="Accessories">
                <h2>Accessories</h2>
            </a>
        </div>
    </div>
</body>
</html>

<style>
    /* CSS styling for the home page */
    body {
        font-family: Arial, sans-serif;
        background-image: url('https://img.freepik.com/free-vector/blue-gradient-background-limbo-studio-setup_107791-32108.jpg');
        background-size: cover;
        background-position: center;
    }
    header {
        display: flex;
        justify-content: space-between;
        background-color: #333;
        color: white;
        padding: 10px 20px;
    }
    .header-buttons button {
        padding: 10px;
        background-color: #007bff;
        border: none;
        color: white;
        border-radius: 5px;
        margin-right: 10px;
        cursor: pointer;
    }
    .header-buttons button a {
        color: white;
        text-decoration: none;
    }
    .grid-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        width: 80%;
        max-width: 1200px;
        margin: 100px auto 0;
    }
    .grid-item {
        text-align: center;
        background-color: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .grid-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
    }
</style>
