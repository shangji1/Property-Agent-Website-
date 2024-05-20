<?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
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
  width: 80%;
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
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin UA Page</title>
</head>
<body>
    <br>
    <button id="createAccount">Create User Account</button>
    <br>
    <input type="text" id="searchAccount" placeholder="Search accounts">
	<div id="page-selection">
		<label for="pageSelect">Select Page:</label>
		<select id="pageSelect">
			<!-- Options will be added here by JavaScript -->
		</select>
	</div>
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content" id="modal-content">
            
        </div>
    </div>
    <br>
    <div id="accountList" style="width: 100%; height: 85%; overflow: auto;">
      
    </div> 
    <br>
</body>
</html>