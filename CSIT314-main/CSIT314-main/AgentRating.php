<?php
    session_start();
    require_once 'AgentRatingController.php';

    //VIEW//
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getAgentRatings') {
		$agentRatingController = new AgentRatingController();
        $response = $agentRatingController->getAgentRatings($_GET['agentId']);
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
    <title>Agent Rating</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your CSS file -->
</head>
<body>

    <div class="body">
        <h1>Agent Rating</h1>

        <div class="overall-rating">
            <h2>Overall Rating</h2>
            <p id="AvgRating"></p> 
        </div>

        <h2>Ratings</h2>
        <div id="ratingList">
            
        </div>
    </div>
</body>
</html>
