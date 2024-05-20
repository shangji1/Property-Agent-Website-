"use strict";

function getDashboard() {
  fetch('SellerLanding.php?action=getDashboard').then(function (response) {
    return response.json();
  }).then(function (propertyObjects) {
    propertyObjects.forEach(function (property) {
      // Create image element
      var img = document.createElement('img'); // Set image properties
      // Create div for property name and status

      var propertyDiv = document.createElement('div'); // Set property name and status
      // Create button for View

      var viewButton = document.createElement('button'); // Set View button properties

      viewButton.textContent = 'View';
      viewButton.addEventListener('click', function () {
        displayProperty(property.id);
      }); // Append elements to container
      // Check if property status is 'sold'

      if (property.status === 'sold') {
        // Create button for Give Rating
        var ratingButton = document.createElement('button'); // Set Give Rating button properties

        ratingButton.textContent = 'Give Rating';
        ratingButton.addEventListener('click', function () {
          displayRating(property.id, property.name, property.agent_id);
        }); // Create button for Give Review

        var reviewButton = document.createElement('button'); // Set Give Review button properties

        reviewButton.textContent = 'Give Review';
        reviewButton.addEventListener('click', function () {
          displayReview(property.id, property.name, property.agent_id);
        }); // Append Give Rating and Give Review buttons to container
      }
    });
  });
}

function displayProperty(id) {
  fetch("SellerLanding.php?action=viewProperty&propertyId=".concat(id)).then(function (response) {
    return response.json();
  }).then(function (response) {// Display property details (response.property.name or response.shortlist)
  });
}