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
    <title>Buyer View</title>
</head>
<body>
    <div class="body">
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search properties...">

            <select id="filterSelect">
                <option value="all">All</option>
                <option value="available">Available</option>
                <option value="sold">Sold</option>
            </select>
        </div>
        <div class="page-selection">
            <label for="pageSelect">Select Page:</label>
            <select id="pageSelect">
                <!-- Options will be added here by JavaScript -->
            </select>
        </div>
        <!-- Property Listings -->
        <div class="property-listings">

            <!-- Add more property listings as needed -->
        </div>
    </div>
</body>
</html>