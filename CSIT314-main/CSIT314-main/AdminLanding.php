<?php
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
    header("Location: index.php");
} else if ($_SESSION['profile'] != "Admin") {
    if ($_SESSION['profile'] == "Buyer") {
        header("Location: BuyerLanding.php");
    } else if ($_SESSION['profile'] == "Seller") {
        header("Location: SellerLanding.php");
    } else if ($_SESSION['profile'] == "Agent") {
        header("Location: AgentLanding.php");
    } else {
        header("Location: index.php");
    }
}

require_once 'AdminCreateUPController.php';
require_once 'AdminViewUPController.php';
require_once 'AdminUpdateUPController.php';
require_once 'AdminSuspendUPController.php';
require_once 'AdminSearchUPController.php';

require_once 'AdminCreateUAController.php';
require_once 'AdminViewUAController.php';
require_once 'AdminUpdateUAController.php';
require_once 'AdminSuspendUAController.php';
require_once 'AdminSearchUAController.php';

//CREATE UP//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'createProfile') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $profileName = $requestData['profileName'];
    $activeStatus = $requestData['activeStatus'];
    $description = $requestData['description'];
	
    $controllerCreateUP = new AdminCreateUPController();
    $response = $controllerCreateUP->createProfile($profileName, $activeStatus, $description);

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}


//CREATE UA//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'createAccount') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $Username = $requestData['accountUsername'];
    $Email = $requestData['accountEmail'];
    $Password = $requestData['accountPassword'];
    $activeStatus = $requestData['activeStatus'];
    $Profile_id = $requestData['accountProfile_id'];
    
	$controllerCreateUA = new AdminCreateUAController();
    $response = $controllerCreateUA->createAccount($Username, $Email, $Password, $activeStatus, $Profile_id);

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} 

//VIEW ALL PROFILE//
// Handle POST request to authenticate user
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getProfiles') {
	$controllerViewUP = new AdminViewUPController();
	$response = $controllerViewUP->getUserProfiles();

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

//VIEW ALL ACCOUNTS//
// Handle POST request to authenticate user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'getAccounts') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $pageNum = $requestData['page'];
	
	$controllerViewUA = new AdminViewUAController();
	$response = $controllerViewUA->getUserAccounts($pageNum);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();

} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getProfileById') {
    $controllerViewUA = new AdminViewUAController();
	$response = $controllerViewUA->getProfileById($_GET['profile_id']);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

//UPDATE//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'updateProfile') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $profileId = $requestData['profileId'];
    $profileName = $requestData['profileName'];
    $activeStatus = $requestData['activeStatus'];
    $description = $requestData['description'];
    
	$controllerUpdate = new AdminUpdateUPController();
    $response = $controllerUpdate->updateProfile($profileId, $profileName, $activeStatus, $description);

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
	exit();
}

//UPDATE UA//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'updateAccount') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $username = $requestData['username'];
    $email = $requestData['email'];
    $password = $requestData['password'];
    $activeStatus = $requestData['activeStatus'];
    $profile_id = $requestData['profile_id'];
    
	$controllerUpdateUA = new AdminUpdateUAController();
    $response = $controllerUpdateUA->updateUserAccount($username, $email, $password, $activeStatus, $profile_id);

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

//SUSPEND// 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'suspendProfile') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    if (isset($requestData['profileId'])) {
        $profileId = $requestData['profileId'];
		
		$controllerSuspend = new AdminSuspendUPController();
        $response = $controllerSuspend->suspendProfile($profileId);

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errorMessage' => 'Profile ID is missing']);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'suspendAccount') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    if (isset($requestData['username'])) {
        $username = $requestData['username'];
		
		$controllerSuspendUA = new AdminSuspendUAController();
        $response = $controllerSuspendUA->suspendAccount($username);

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errorMessage' => 'Username is missing']);
        exit();
    }
}

//SEARCH UP//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'searchProfile') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $name = $requestData['name'];
	
    $controllerSearchUP = new AdminSearchUPController();
    $response = $controllerSearchUP->searchUserProfile($name);

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

//SEARCH UA//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'searchAccount') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $username = $requestData['username'];
	
    $controllerSearchUA = new AdminSearchUAController();
    $response = $controllerSearchUA->searchUserAccount($username);

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .button-container button {
            width: 50%; /* Set button width */
            padding: 10px;
            margin: 5px;
            font-size: 16px;
        }

        .button-container {
            border: 2px solid black;
            display: flex;
            justify-content: center;
        }

        #flex {
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 90%;
            width: 90%;
            padding: 5px;
        }
        
        #UPUA {
            border: 1px solid black;
            width: 90%;
            height: 80%;
        }
    </style>
</head>
<body>
    
    <div id="flex">
        <div>
            <h1>Welcome, <?php echo $_SESSION['userID'] ?></h1>
        </div>
        <div class="button-container">
            <button onclick="loadContent('AdminUP.php')">User Profile</button>
            <button onclick="loadContent('AdminUA.php')">User Account</button>
        </div>
        <br>
        <div id="UPUA">
            
        </div>
        <br>
        <div>
            <a href="logout.php">Logout</a>
        </div>
    </div>
	<script src="AdminApi.js"></script>
</body>

</html>
