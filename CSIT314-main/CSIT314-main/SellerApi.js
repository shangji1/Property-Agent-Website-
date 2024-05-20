class SellerApi {
    constructor() {

    }

    getDashboard() {
        fetch(`SellerLanding.php?action=getDashboard&sellerId=${window.userID}`)
            .then(response => response.json())
            .then(data => {
                console.log(data);
				if (!data.success) // Access 'success' directly instead of using brackets
					throw new Error(data.errorMessage);
					
                const propertyList = document.querySelector('.property-listings');
                propertyList.innerHTML = '';

                data.properties.forEach(property => {
                    // Create div for property image, name and status
                    var propertyDiv = document.createElement('div');
                    propertyDiv.classList.add('property');
                    // Create image element
                    var img = document.createElement('img');
                    img.src = property.image; // Assuming property.image is the image URL
                    img.alt = property.id;
                    // Append elements to container
                    propertyDiv.appendChild(img);

                    var propertyDetailsDiv = document.createElement('div');
                    propertyDetailsDiv.classList.add('property-details');
                    // Create h2 element for property name
                    var propertyName = document.createElement('h2');
                    propertyName.textContent = property.name;

                    // Create div element for property status
                    var propertyStatus = document.createElement('div');
                    propertyStatus.classList.add('status');
                    propertyStatus.textContent = property.status;

                    // Append elements to propertyDiv
                    propertyDetailsDiv.appendChild(propertyName);
                    propertyDetailsDiv.appendChild(propertyStatus);

                    var viewButton = document.createElement('button');
                    viewButton.textContent = 'View';
                    viewButton.addEventListener('click', () => {
                        this.displayProperty(property.id);
                    });

                    propertyDetailsDiv.appendChild(viewButton);

					// Create button for Give Rating
					var ratingButton = document.createElement('button');
					ratingButton.textContent = 'Give Rating';
					ratingButton.addEventListener('click', () => {
						this.displayRating(property.id);
					});

					// Create button for Give Review
					var reviewButton = document.createElement('button');
					reviewButton.textContent = 'Give Review';
					reviewButton.addEventListener('click', () => {
						this.displayReview(property.id);
					});

					// Append Give Rating and Give Review buttons to container
					propertyDetailsDiv.appendChild(ratingButton);
					propertyDetailsDiv.appendChild(reviewButton);

                    propertyDiv.appendChild(propertyDetailsDiv);
                    
                    // Append property container to listings
                    document.querySelector('.property-listings').appendChild(propertyDiv);
                });
            })
			.catch(error => {
                console.error('Error fetching properties:', error);
            });
    }

    displayProperty(id) {
        fetch(`SellerLanding.php?action=viewProperty&propertyId=${id}`)
            .then(response => response.json())
            .then(data => {
                console.log(data);
				if (!data.success) // Access 'success' directly instead of using brackets
					throw new Error(data.errorMessage);
                // Assuming you have a modal for property details
                document.getElementById('modal-content').innerHTML = `
                    <span class="close">&times;</span>
                    <div id="details">
                        <div>
                            <p>Property ID: ${data.property.id}</p>
                            <p>Name: ${data.property.name}</p>
                            <p>Type: ${data.property.type}</p>
                            <p>Size: ${data.property.size}</p>
                            <p>Rooms: ${data.property.rooms}</p>
                            <p>Price: ${data.property.price}</p>
                            <p>Location: ${data.property.location}</p>
                            <p>Status: ${data.property.status}</p>
                            <p>Property Agent: ${data.property.agent_id}</p>
                            <p>Number of Views: ${data.property.views}</p>
                            <p>Number of shortlisted: ${data.shortlist}</p>
                        </div>
                `;
                //create a property image div to append to modal content
                var propertyImage = document.createElement('div');
                propertyImage.classList.add('property-image');
                // Create img element for property image
                var img = document.createElement('img');
                img.src = data.property.image; // Assuming propertyDetails.image is the image URL
                // Set style for property image
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
                // Append img element to propertyImage div
                propertyImage.appendChild(img);

                propertyImage.style.maxWidth = '50%';
                propertyImage.style.height = 'auto';
                document.getElementById('details').appendChild(propertyImage);

                modalFeatures();
            })
            .catch(error => {
                console.error('Error fetching property details:', error);
            });
    }

    displayRating(propertyId) {
        // Fetch property details to get necessary information
        fetch(`SellerLanding.php?action=viewProperty&propertyId=${propertyId}`)
            .then(response => response.json())
            .then(propertyDetails => {
				console.log(propertyDetails);
				if (!propertyDetails.success) // Access 'success' directly instead of using brackets
					throw new Error(propertyDetails.errorMessage);
					
                // Create modal content
                const modalContent = document.getElementById('modal-content');
                modalContent.innerHTML = `
                    <span class="close">&times;</span>
                    <div id="rating-form">
                        <h2>Rate the Agent</h2>
                        <form id="ratingForm">
                            <div>
                                <label for="propertyID">Property ID:</label>
                                <span id="propertyID">${propertyDetails.property.id}</span>
                            </div>
                            <div>
                                <label for="propertyName">Property Name:</label>
                                <span id="propertyName">${propertyDetails.property.name}</span>
                            </div>
                            <div>
                                <label for="agentID">Real Estate Agent:</label>
                                <span id="agentID">${propertyDetails.property.agent_id}</span>
                            </div>
                            <div>
                                <label for="customerID">Customer:</label>
                                <span id="customerID">${userID}</span>
                            </div>
                            <div>
                                <label for="agentRating">Rating (1-5):</label>
                                <input type="number" id="agentRating" name="agentRating" min="1" max="5" required>
                            </div>
                            <button type="submit">Submit Rating</button>
                        </form>
                    </div>
                `;

                // Add event listener to form submission
                const ratingForm = document.getElementById('ratingForm');
                ratingForm.addEventListener('submit', this.createRating);
                
                // Display the modal
                modalFeatures();
            })
            .catch(error => {
                console.error('Error fetching property details:', error);
            });
    }

    createRating = (event) => {
        event.preventDefault();
        // Extract values from the form
        const agentRating = document.getElementById('agentRating').value;
        const customerID = userID; // Assuming userID is accessible here
        const agentID = document.getElementById('agentID').textContent;
    
        fetch('SellerLanding.php?action=createRating', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ agentRating, customerID, agentID })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error in the network!');
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
			if (!data.success){ // Access 'success' directly instead of using brackets
				document.getElementById("myModal").style.display = "none";
				throw new Error(data.errorMessage);
			}
			else{
				alert(`Rating for ${agentID} was created successfully!`);
                document.getElementById("myModal").style.display = "none";
			}
        })
        .catch(error => console.error('Error reviewing agent:', error));
    }

    // Create Review 
    displayReview(propertyId) {
        // Fetch property details to get necessary information
        fetch(`SellerLanding.php?action=viewProperty&propertyId=${propertyId}`)
            .then(response => response.json())
            .then(propertyDetails => {
				console.log(propertyDetails);
				if (!propertyDetails.success) // Access 'success' directly instead of using brackets
					throw new Error(propertyDetails.errorMessage);
				
                // Create modal content
                const modalContent = document.getElementById('modal-content');
                modalContent.innerHTML = `
                    <span class="close">&times;</span>
                    <div id="review-form">
                        <h2>Review the Agent</h2>
                        <form id="reviewForm">
                            <div>
                                <label for="propertyID">Property ID:</label>
                                <span id="propertyID">${propertyDetails.property.id}</span>
                            </div>
                            <div>
                                <label for="propertyName">Property Name:</label>
                                <span id="propertyName">${propertyDetails.property.name}</span>
                            </div>
                            <div>
                                <label for="agentID">Real Estate Agent:</label>
                                <span id="agentID">${propertyDetails.property.agent_id}</span>
                            </div>
                            <div>
                                <label for="customerID">Customer:</label>
                                <span id="customerID">${userID}</span>
                            </div>
                            <div>
                                <label for="agentReview">Review:</label>
                                <textarea id="agentReview" name="agentReview" rows="4" cols="50" required placeholder="Type your review here..."></textarea>
                            </div>
                            <button type="submit">Submit Review</button>
                        </form>
                    </div>
                `;
                // Add event listener to form submission
                const reviewForm = document.getElementById('reviewForm');
                reviewForm.addEventListener('submit', this.createReview);
                
                // Display the modal
                modalFeatures();
            })
            .catch(error => {
                console.error('Error fetching property details:', error);
            });
    }
    
    createReview = (event) => {
        event.preventDefault();
        // Extract values from the form
        const agentReview = document.getElementById('agentReview').value;
        const customerID = userID; // Assuming userID is accessible here
        const agentID = document.getElementById('agentID').textContent;
        
        fetch('SellerLanding.php?action=createReview', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ agentReview, customerID, agentID })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error in the network!');
            }
            return response.json();
        })
		.then(data => {
            console.log(data);
			if (!data.success){ // Access 'success' directly instead of using brackets
				document.getElementById("myModal").style.display = "none";
				throw new Error(data.errorMessage);
			}
			else{
				alert(`Review for ${agentID} was created successfully!`);
                document.getElementById("myModal").style.display = "none";
			}
        })
        .catch(error => console.error('Error reviewing agent:', error));
    }
    
}

function modalFeatures () {
    // Get the modal
    var modal = document.getElementById("myModal");

    modal.style.display = "block";

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
    modal.style.display = "none";
    }
    
}

const sellerApiInstance = new SellerApi();

window.onload = () => {
    
    sellerApiInstance.getDashboard();
}


function loadContent(page) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Attempt to set the innerHTML property of an element
            var element = document.querySelector(".body"); // Change to the appropriate selector
            if (element !== null) {
                element.innerHTML = this.responseText;
                
                
            } else {
                console.error("Element not found.");
            }
        }
    };
    xhttp.open("GET", page, true);
    xhttp.send();
}
