<?php
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
    header("Location: index.php");
} else if ($_SESSION['profile'] != "Seller") {
    if ($_SESSION['profile'] == "Buyer") {
        header("Location: BuyerLanding.php");
    } else if ($_SESSION['profile'] == "Agent") {
        header("Location: AgentLanding.php");
    } else if ($_SESSION['profile'] == "Admin") {
        header("Location: AdminLanding.php");
    } else {
        header("Location: index.php");
    }
}

require_once 'SellerViewALLPropertyController.php';
require_once 'SellerViewONEPropertyController.php';
require_once 'CreateRatingController.php';
require_once 'CreateReviewController.php';

//VIEW//
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getDashboard') {
    if(isset($_GET['sellerId'])) {
        $SellerViewALLPropertyController = new SellerViewALLPropertyController();
        $response = $SellerViewALLPropertyController->getSellerProperties($_GET['sellerId']);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
} 

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'viewProperty') {
    if(isset($_GET['propertyId'])) {
		$SellerViewONEPropertyController = new SellerViewONEPropertyController();
        $response = $SellerViewONEPropertyController->getPropertyByID($_GET['propertyId']);
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
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
    <title>Seller Hub</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your CSS file -->
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <div class="logo">Seller Hub</div>
        <div class="logo"><a href="logout.php" style="text-decoration: none; color: inherit;"> Logout</a></div>
    </div>

    <!-- Body Section -->
    <div class="body">
        <h1>Welcome to Seller Hub, <?php echo $_SESSION['userID'] ?></h1>

        <!-- Property Listings -->
        <div class="property-listings">
            <!-- Add more property listings as needed -->
        </div>
    </div>
    <div id="myModal" class="propertyModal">
        <!-- Modal content -->
        <div class="modal-content" id="modal-content">
            
        </div>
    </div>
</body>
<script>
    if (<?php echo isset($_SESSION['userID']) ? 'true' : 'false'; ?>) {
        window.userID = "<?php echo $_SESSION['userID']; ?>";
    }
</script>
<script src="SellerApi.js"></script>
</html>
