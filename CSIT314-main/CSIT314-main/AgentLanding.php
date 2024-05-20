<?php
 session_start();

if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
    header("Location: index.php");
} else if ($_SESSION['profile'] != "Agent") {
    if ($_SESSION['profile'] == "Buyer") {
        header("Location: BuyerLanding.php");
    } else if ($_SESSION['profile'] == "Seller") {
        header("Location: SellerLanding.php");
    } else if ($_SESSION['profile'] == "Admin") {
        header("Location: AdminLanding.php");
    } else {
        header("Location: index.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Agent Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <a href="AgentLanding.php" class="logo">Agent Hub</a>
        <nav>
            <ul>
                <li><a href="#" onclick="loadContent('AgentCreate.php')">Create </button></li>
                <li><a href="#" onclick="loadContent('AgentView.php')"> View</a></li>
                <li><a href="#" onclick="loadContent('AgentRating.php')"> Rating </a></li>
                <li><a href="#" onclick="loadContent('AgentReview.php')"> Review</a></li>
                <li><a href="logout.php"> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="body">
        <!-- Content of the body goes here -->
        <h1 class="welcome-message">Welcome to the Property Agent Page!</h1>
    </div>
</body>
<script>
    if (<?php echo isset($_SESSION['userID']) ? 'true' : 'false'; ?>) {
        window.userID = "<?php echo $_SESSION['userID']; ?>";
    }
</script>
<script src="AgentApi.js"></script>
</html>
