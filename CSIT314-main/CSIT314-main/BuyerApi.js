class BuyerApi {
    constructor () {

    }
	
    getViewDashboard (propertyStatus = 'all', pageNum = 1) {
        fetch(`BuyerLanding.php?action=getViewDashboard&status=${propertyStatus}&page=${pageNum}`)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (!data.success) // Access 'success' directly instead of using brackets
				throw new Error(data.errorMessage);

            // Display search results
            this.setProperty(data.properties);
			this.setPageSelector(data.numOfProp, pageNum);
        })
		.catch(error => {
			console.error('Error fetching property:', error);
		});
    }

    getShortlistDashboard() {
        // Fetch shortlisted properties for the logged-in user
        fetch(`BuyerLanding.php?action=getShortlistDashboard&buyerId=${window.userID}`)
        .then(response => response.json())
        .then(data => {
            console.log(data);
			
			if (!data.success) // Access 'success' directly instead of using brackets
				throw new Error(data.errorMessage);
			
            const shortlistListing = document.querySelector('.shortlist-listing');
            shortlistListing.innerHTML = '';
            
            if (data.properties.length === 0) {
                var message = document.createElement('h1');
                message.innerHTML = 'No properties shortlisted';
                shortlistListing.appendChild(message);
            } else {
                data.properties.forEach(property => {
                    // Create div for property image, name, and status
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
        
                    // Append elements to propertyDiv
                    propertyDetailsDiv.appendChild(propertyName);
					
					// Create h3 element for property status
                    var propertyStatus = document.createElement('p');
                    propertyStatus.textContent = property.status;
        
                    // Append elements to propertyDiv
                    propertyDetailsDiv.appendChild(propertyStatus);
        
                    // View button
                    var viewButton = document.createElement('button');
                    viewButton.textContent = 'View';
                    viewButton.addEventListener('click', () => {
                        this.displayProperty(property.id);
                    });
        
                    propertyDetailsDiv.appendChild(viewButton);

                    //Delete ShortList button
                    var shortListButton = document.createElement('button');
                    shortListButton.textContent = 'Delete ShortList';
                    shortListButton.addEventListener('click', () => {
                        this.deleteShortListProperty(property.id);
                    });
                
                    propertyDetailsDiv.appendChild(shortListButton);

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
        
                    // Append propertyDetailsDiv to propertyDiv
                    propertyDiv.appendChild(propertyDetailsDiv);
        
                    // Append property container to shortlist container
                    shortlistListing.appendChild(propertyDiv);
                });
            }
        })		
		.catch(error => {
			console.error('Error fetching shortlist property:', error);
		});
    }

    displayProperty(id) {
        fetch(`BuyerLanding.php?action=viewProperty&propertyId=${id}`)
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
                            <p>Property ID: ${id}</p>
                            <p>Name: ${data.property.name}</p>
                            <p>Type: ${data.property.type}</p>
                            <p>Size: ${data.property.size}</p>
                            <p>Rooms: ${data.property.rooms}</p>
                            <p>Price: ${data.property.price}</p>
                            <p>Location: ${data.property.location}</p>
                            <p>Status: ${data.property.status}</p>
                            <p>Seller: ${data.property.seller_id}</p>
                            <p>Number of Views: ${data.property.views}</p>
                        </div>
                `;
                //create a property image div to append to modal content
                var propertyImage = document.createElement('div');
                propertyImage.classList.add('property-image');
                // Create img element for property image
                var img = document.createElement('img');
                img.src = data.property.image; // Assuming data.property.image is the image URL
                // Set style for property image
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
                // Append img element to propertyImage div
                propertyImage.appendChild(img);

                propertyImage.style.maxWidth = '50%';
                propertyImage.style.height = 'auto';
                document.getElementById('details').appendChild(propertyImage);

                // Add mortgage calculator
                var mortgageCalculator = document.createElement('div');
                mortgageCalculator.innerHTML = `
                    <h3>Mortgage Calculator</h3>
                    <label for="loanAmount">Loan Amount:</label>
                    <input type="number" id="loanAmount" value=""><br>
                    <label for="interestRate">Interest Rate (%):</label>
                    <input type="number" id="interestRate" value=""><br>
                    <label for="loanTerm">Loan Term (years):</label>
                    <input type="number" id="loanTerm" value=""><br>
                    <button onclick="calculateMortgage()">Calculate</button>
                    <p id="monthlyPayment"></p>
                `;
                document.getElementById('details').appendChild(mortgageCalculator);

                modalFeatures();
            })
            .catch(error => {
                console.error('Error fetching property details:', error);
            });
    }

    shortListProperty (propertyId) {
        fetch(`BuyerLanding.php?action=shortListProperty&propertyId=${propertyId}&buyerId=${window.userID}`)
        .then(response => response.json())
        .then(response => {
			if (!response.success){ // Access 'success' directly instead of using brackets
				if (response.errorMessage.includes('Property already shortlisted!')){
					alert(response.errorMessage);
					this.getViewDashboard();
				}
				throw new Error(response.errorMessage);
			}
            alert("Property added to shortlist!");
            this.getViewDashboard();
        })
		.catch(error => {
			console.error('Error deleting shortlist:', error);
		});
    }

    deleteShortListProperty (propertyId) {
        fetch(`BuyerLanding.php?action=deleteShortlistProperty&propertyId=${propertyId}&buyerId=${window.userID}`)
        .then(response => response.json())
        .then(response => {
			if (!response.success){ // Access 'success' directly instead of using brackets
				if (response.errorMessage.includes('Property already shortlisted!')){
					alert(response.errorMessage);
					this.getShortlistDashboard();
				}
				throw new Error(response.errorMessage);
			}
            alert("Property removed from shortlist!");
            this.getShortlistDashboard();
        })
		.catch(error => {
			console.error('Error deleting shortlist:', error);
		});
    }

    //Create Review 
    displayReview(propertyId) {
        // Fetch property details to get necessary information
        fetch(`BuyerLanding.php?action=viewProperty&propertyId=${propertyId}`)
            .then(response => response.json())
            .then(data => {
                if (!data.success) // Access 'success' directly instead of using brackets
					throw new Error(data.errorMessage);
				
				// Create modal content
                const modalContent = document.getElementById('modal-content');
                modalContent.innerHTML = `
                    <span class="close">&times;</span>
                    <div id="review-form">
                        <h2>Review the Agent</h2>
                        <form id="reviewForm">
                            <div>
                                <label for="propertyName">Property Name:</label>
                                <span id="propertyName">${data.property.name}</span>
                            </div>
                            <div>
                                <label for="agentID">Real Estate Agent:</label>
                                <span id="agentID">${data.property.agent_id}</span>
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
        
        fetch('BuyerLanding.php?action=createReview', {
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

    // Create Rating 
    displayRating(propertyId) {
        // Fetch property details to get necessary information
        fetch(`BuyerLanding.php?action=viewProperty&propertyId=${propertyId}`)
            .then(response => response.json())
            .then(data => {
                if (!data.success) // Access 'success' directly instead of using brackets
					throw new Error(data.errorMessage);
				
				// Create modal content
                const modalContent = document.getElementById('modal-content');
                modalContent.innerHTML = `
                    <span class="close">&times;</span>
                    <div id="rating-form">
                        <h2>Rate the Agent</h2>
                        <form id="ratingForm">
                            <div>
                                <label for="propertyName">Property Name:</label>
                                <span id="propertyName">${data.property.name}</span>
                            </div>
                            <div>
                                <label for="agentID">Real Estate Agent:</label>
                                <span id="agentID">${data.property.agent_id}</span>
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
    
        fetch('BuyerLanding.php?action=createRating', {
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
        .catch(error => console.error('Error rating agent:', error));
    }

    //SEARCH//
    searchBuyerProperty = (propertyName, propertyStatus, pageNum) => {        
        fetch(`BuyerLanding.php?action=searchBuyerProperty`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: propertyStatus, name: propertyName, pageNum: pageNum }) // Include pageNum in the JSON object
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            
            if (!data.success) // Access 'success' directly instead of using brackets
				throw new Error(data.errorMessage);

            // Display search results
            this.setProperty(data.properties);
			this.setPageSelector(data.numOfProp, pageNum);
        })
        .catch(error => console.error('Error searching properties:', error));
    }

    setProperty = (properties) => {
		fetch(`BuyerLanding.php?action=getShortlistDashboard&buyerId=${window.userID}`)
		.then(response => response.json())
		.then(data => {
			console.log(data);
			
			if (!data.success) // Access 'success' directly instead of using brackets
				throw new Error(data.errorMessage);
			
			const propertyList = document.querySelector('.property-listings');
			propertyList.innerHTML = '';

			if (properties && properties.length > 0) {
				properties.forEach(property => {
					// Create div for property image, name, and status
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
			
					// Append elements to propertyDiv
					propertyDetailsDiv.appendChild(propertyName);
					
					// Create p element for property status
                    var propertyStatus = document.createElement('p');
                    propertyStatus.textContent = property.status;
        
                    // Append elements to propertyDiv
                    propertyDetailsDiv.appendChild(propertyStatus);
			
					// View button
					var viewButton = document.createElement('button');
					viewButton.textContent = 'View';
					viewButton.addEventListener('click', () => {
						this.displayProperty(property.id);
					});
			
					propertyDetailsDiv.appendChild(viewButton);
			
					// ShortList button
					if (!data.properties.find(shortlist=> shortlist.id == property.id)) {
						var shortListButton = document.createElement('button');
						shortListButton.textContent = 'Add To ShortList';
						shortListButton.addEventListener('click', () => {
							this.shortListProperty(property.id);
						});
		
						propertyDetailsDiv.appendChild(shortListButton);
					}
			
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
			
					// Append propertyDetailsDiv to propertyDiv
					propertyDiv.appendChild(propertyDetailsDiv);
			
					// Append property container to listings
					propertyList.appendChild(propertyDiv);
				});

			} else {
				// Display message when there are no search results
				const noResultsMessage = document.createElement('div');
				noResultsMessage.textContent = 'No search results found.';
				propertyList.appendChild(noResultsMessage);
			}
		})
		.catch(error => {
			console.error('Error fetching shortlist property:', error);
		});
    }
	
	setPageSelector (numOfProp, pageNum) {
		let totalPages = (numOfProp <= 0) ? 1 : Math.ceil(numOfProp / 9);
		console.log(totalPages);
		const pageSelect = document.getElementById('pageSelect');
        pageSelect.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
			if (i == pageNum)
				option.selected = true;
            pageSelect.appendChild(option);
        }
	}
}

function runView (){
	// Get value entered in search input field and convert it to lowercase
	const propertyName = document.getElementById('searchInput').value.toLowerCase();
	const propertyStatus = document.getElementById('filterSelect').value;
	const pageNum = document.getElementById('pageSelect').value; // Get selected page number
	
	if (propertyName.trim() == '') {
		BuyerApiInstance.getViewDashboard(propertyStatus, pageNum);
    } else{
		BuyerApiInstance.searchBuyerProperty(propertyName, propertyStatus, pageNum);
	}
}

function calculateMortgage() {
    var loanAmount = parseFloat(document.getElementById('loanAmount').value);
    var interestRate = parseFloat(document.getElementById('interestRate').value) / 100;
    var loanTerm = parseFloat(document.getElementById('loanTerm').value);
    
    var monthlyInterestRate = interestRate / 12;
    var numberOfPayments = loanTerm * 12;
    var monthlyPayment = (loanAmount * monthlyInterestRate) / (1 - Math.pow(1 + monthlyInterestRate, -numberOfPayments));
    
    document.getElementById('monthlyPayment').textContent = 'Monthly Payment: $' + monthlyPayment.toFixed(2);
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

const BuyerApiInstance = new BuyerApi();

function initializeView () {
    BuyerApiInstance.getViewDashboard();

    // Event listener for page selection
    document.getElementById('pageSelect').addEventListener('change', runView);

    // Event listener for search input
    document.getElementById('searchInput').addEventListener('input', function() {
		const propertyName = this.value.toLowerCase();
		const propertyStatus = document.getElementById('filterSelect').value;
        BuyerApiInstance.searchBuyerProperty(propertyName, propertyStatus, 1);
    });

    // Event listener for dropdown filter
    document.getElementById('filterSelect').addEventListener('change', runView);
}

function initializeShortlist () {
    BuyerApiInstance.getShortlistDashboard();
}

window.onload = () => {
    loadContent('BuyerView.php');
}

function loadContent(page) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Attempt to set the innerHTML property of an element
            var element = document.querySelector("#body"); // Change to the appropriate selector
            if (element !== null) {
                element.innerHTML = this.responseText;
                
                if (page === 'BuyerView.php') {
                    initializeView();
                } else if (page === 'BuyerShortlist.php') {
                    initializeShortlist();
                }
            } else {
                console.error("Element not found.");
            }
        }
    };
    xhttp.open("GET", page, true);
    xhttp.send();
}