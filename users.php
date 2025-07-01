<?php
session_start();
require 'db.php';

// Check if user is admin
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// First, alter the users table to add blocked column if it doesn't exist
try {
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS blocked BOOLEAN DEFAULT FALSE");
} catch (PDOException $e) {
    die("Error altering users table: " . $e->getMessage());
}

// Handle user blocking/unblocking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['block_user'])) {
        $userId = $_POST['user_id'];
        
        try {
            $stmt = $pdo->prepare("UPDATE users SET blocked = TRUE WHERE id = ?");
            $stmt->execute([$userId]);
            $_SESSION['message'] = "User blocked successfully!";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error blocking user: " . $e->getMessage();
        }
    } elseif (isset($_POST['unblock_user'])) {
        $userId = $_POST['user_id'];
        
        try {
            $stmt = $pdo->prepare("UPDATE users SET blocked = FALSE WHERE id = ?");
            $stmt->execute([$userId]);
            $_SESSION['message'] = "User unblocked successfully!";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error unblocking user: " . $e->getMessage();
        }
    }
    
    header("Location: users.php");
    exit();
}

try {
    // Fetch only unblocked users
    $stmt = $pdo->query("SELECT * FROM users WHERE blocked = FALSE ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        header {
            background-color: #2c3e50;
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header-buttons button {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #34495e;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .block-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .unblock-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .block-btn:hover {
            background-color: #c82333;
        }
        .unblock-btn:hover {
            background-color: #218838;
        }
        .tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #f1f1f1;
            margin-right: 5px;
        }
        .tab.active {
            background-color: #34495e;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <h1>DeviceDen - Admin</h1>
        </div>
        <div class="header-right">
            <button onclick="location.href='ahome.php'">Home</button>
            <button onclick="location.href='aord.php'">Orders</button>
            <button onclick="location.href='users.php'">Users</button>
            <button onclick="location.href='blocked_users.php'">Blocked Users</button>
            <button onclick="location.href='logout.php'">Logout</button>
        </div>
    </header>

    <div class="container">
        <h1>Manage Users</h1>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="tabs">
            <div class="tab active" onclick="location.href='users.php'">Active Users</div>
            <div class="tab" onclick="location.href='blocked_users.php'">Blocked Users</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                   
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['mobile']) ?></td>
                        
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" name="block_user" class="block-btn" 
                                    onclick="return confirm('Are you sure you want to block this user?')">
                                    Block
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

