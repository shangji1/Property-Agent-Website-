<?php
session_start(); // Start the session

// Check if the agent's user ID is set in the session
if(isset($_SESSION['userID'])) {
    $agentUserID = $_SESSION['userID'];
    // Now you can use $agentUserID wherever you need the agent's user ID in this page
}

require_once 'AgentCreatePropController.php';

//CREATE//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'createProperty') {
	$requestData = json_decode(file_get_contents('php://input'), true);
	$name = $requestData['name'];
	$type = $requestData['type'];
	$size = $requestData['size'];
	$rooms = $requestData['rooms'];
	$price = $requestData['price'];
	$location = $requestData['location'];
	$status = $requestData['status'];
	$image = $requestData['image'];
	$views = $requestData['views'];
	$seller_id = $requestData['seller_id'];
	
	$agentCreatePropController = new AgentCreatePropController();
	$response = $agentCreatePropController->createProperty($name, $type, $size, $rooms, $price, $location, $status, $image, $views, $seller_id, $agentUserID);

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
    <title>Agent Create Page</title>
    <link rel="stylesheet" href="style.css">

    <script>
        

    </script>

</head>
<body>
    <div class="body">
        <h2>Create Property Listing</h2>
        <form id="createPropForm" enctype="multipart/form-data">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br>

            <label for="type">Type:</label><br>
            <input type="text" id="type" name="type" required><br>

            <label for="sqfeet">Square Feet:</label><br>
            <input type="number" id="sqfeet" name="sqfeet" required><br>

            <label for="rooms">Rooms:</label><br>
            <input type="number" id="rooms" name="rooms" required><br>

            <label for="price">Price:</label><br>
            <input type="number" id="price" name="price" required><br>

            <label for="location">Location:</label><br>
            <input type="text" id="location" name="location" required><br>

            <label for="seller">Seller:</label><br>
            <input type="text" id="seller" name="seller" required><br>

            <label for="image">Upload Image:</label><br>
            <input type="file" id="image" name="image" accept="image/*"><br>

            <input type="submit" value="Create">
        </form>
    </div>
</body>
</html>