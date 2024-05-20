<?php
    session_start();
    require_once 'AgentReviewController.php';

    //VIEW//
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getAgentReviews') {
		$agentReviewController = new AgentReviewController();
        $response = $agentReviewController->getAgentReviews($_GET['agentId']);
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
    <title>Agent Reviews</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your CSS file -->
</head>
<body>
    
    <div class="body">
        <h1>Customer Testimonials and Reviews</h1>
        <!-- Add more testimonials as needed -->
        <div id ="reviewList"></div>
        
    </div>

        
</div>
</body>
</html>
