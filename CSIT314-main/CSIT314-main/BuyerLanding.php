<?php
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
    header("Location: index.php");
} else if ($_SESSION['profile'] != "Buyer") {
    if ($_SESSION['profile'] == "Seller") {
        header("Location: SellerLanding.php");
    } else if ($_SESSION['profile'] == "Admin") {
        header("Location: AdminLanding.php");
    } else if ($_SESSION['profile'] == "Agent") {
        header("Location: AgentLanding.php");
    } else {
        header("Location: index.php");
    }
}

require_once 'BuyerViewALLPropertyController.php';
require_once 'BuyerViewONEPropertyController.php';
require_once 'BuyerShortlistAddController.php';
require_once 'BuyerShortlistViewController.php';
require_once 'BuyerShortlistDeleteController.php';
require_once 'BuyerSearchPropertyController.php';
require_once 'CreateReviewController.php';
require_once 'CreateRatingController.php';

//VIEW ALL
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getViewDashboard') {
    $BuyerViewALLPropertyController = new BuyerViewALLPropertyController();
	$response = $BuyerViewALLPropertyController->getBuyerProperties($_GET['status'], $_GET['page']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();

} 

//VIEW ONE
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'viewProperty') {
    if(isset($_GET['propertyId'])) {
        $BuyerViewONEPropertyController = new BuyerViewONEPropertyController();
		$response = $BuyerViewONEPropertyController->getPropertyByID($_GET['propertyId']);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}

//ADD SHORTLIST
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'shortListProperty') {
    $BuyerShortlistAddController = new BuyerShortlistAddController();
	$result = $BuyerShortlistAddController->addShortListProperty($_GET['propertyId'], $_GET['buyerId']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit();
}

//SHORTLIST VIEW
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getShortlistDashboard') {
    $BuyerShortlistViewController = new BuyerShortlistViewController();
	$response = $BuyerShortlistViewController->getBuyerShortlistProperties($_GET['buyerId']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();

}

//DELETE SHORTLIST
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'deleteShortlistProperty') {
    $BuyerShortlistDeleteController = new BuyerShortlistDeleteController();
	$result = $BuyerShortlistDeleteController->deleteShortlistProperty($_GET['propertyId'], $_GET['buyerId']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit();
}

//CREATE REVIEW//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'createReview') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $review = $requestData['agentReview'];
    $customer_id = $requestData['customerID'];
    $agent_id = $requestData['agentID'];
	$CreateReviewController = new CreateReviewController();
    $response = $CreateReviewController->createReview($review, $customer_id, $agent_id);

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

//CREATE RATING//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'createRating') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $rating = $requestData['agentRating'];
    $customer_id = $requestData['customerID'];
    $agent_id = $requestData['agentID'];
	$CreateRatingController = new CreateRatingController();
    $response = $CreateRatingController->createRating($rating, $customer_id, $agent_id);

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

//SEARCH PROPERTY//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'searchBuyerProperty') {
    $requestData = json_decode(file_get_contents('php://input'), true);

    if (isset($requestData['status']) && isset($requestData['name']) && isset($requestData['pageNum'])) {
        $status = $requestData['status'];
        $name = $requestData['name']; 
        $pageNum = $requestData['pageNum']; // Get pageNum from request
		
		$BuyerSearchPropertyController = new BuyerSearchPropertyController();
        $result = $BuyerSearchPropertyController->searchBuyerProperty($status, $name, $pageNum); // Pass pageNum to the controller method
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errorMessage' => 'Search input is missing']);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
/* The Modal (background) */
.propertyModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 60%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

#details {
    display: flex;
    align-items: center;
    justify-content: space-around;
    margin-bottom: 10px;
}
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <a href="AgentLanding.php" class="logo">Buyer Hub</a>
        <nav>
            <ul>
                <li><a href="#" onclick="loadContent('BuyerView.php')">View </button></li>
                <li><a href="#" onclick="loadContent('BuyerShortlist.php')">Shortlist </button></li>
                <li><a href="logout.php"> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div id="myModal" class="propertyModal">
        <!-- Modal content -->
        <div class="modal-content" id="modal-content">
            
        </div>
    </div>
    <div id="body">
        <!-- Content of the body goes here -->
        <h1 class="welcome-message">Welcome to the Buyer Page!</h1>
    </div>
</body>
<script>
    if (<?php echo isset($_SESSION['userID']) ? 'true' : 'false'; ?>) {
        window.userID = "<?php echo $_SESSION['userID']; ?>";
    }
</script>
<script src="BuyerApi.js"></script>
</html>