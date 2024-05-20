<?php
session_start();

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    if ($_SESSION['profile'] == "Buyer") {
        header("Location: BuyerLanding.php");
    } else if ($_SESSION['profile'] == "Seller") {
        header("Location: SellerLanding.php");
    } else if ($_SESSION['profile'] == "Agent") {
        header("Location: AgentLanding.php");
    } else if ($_SESSION['profile'] == "Admin") {
        header("Location: AdminLanding.php");
    } else {
        exit();
    }
}

require 'LoginController.php';
$controller = new LoginController();
// Handle POST request to authenticate user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'login') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $username = $requestData['username'];
    $password = $requestData['password'];
    $profile = $requestData['profile'];

    // Perform login authentication
    $response = $controller->auth($username, $password, $profile);
    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getProfiles') {
    $profiles = $controller->getUserProfiles();
    header('Content-Type: application/json');
    echo json_encode($profiles);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Estate System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f4f4f4;
        }
        .login-container {
            text-align: center;
        }
        .login-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        #loginForm {
            display: inline-block;
        }
        #loginForm select,
        #loginForm input[type="text"],
        #loginForm input[type="password"],
        #loginForm button {
            margin: 5px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        #loginForm button {
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="login-container">
        <div class="login-title">REAL ESTATE SYSTEM</div>
        <h2>Log In</h2>
        <form id="loginForm">
            <select id="profile" name="profile">
                <option value="buyer">Buyer</option>
                <option value="seller">Seller</option>
                <option value="agent">Agent</option>
                <option value="admin">Admin</option>
            </select>
            <br>
            <input type="text" id="username" placeholder="Username" required>
            <input type="password" id="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p id="loginMessage"></p>
    </div>
    <script src="LoginApi.js"></script>
</body>
</html>