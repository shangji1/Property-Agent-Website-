class AgentApi {
    constructor() {

    }
    getAgentProperties() {
        fetch(`AgentView.php?action=getAgentProperties&agentId=${window.userID}`)
        .then(response => response.json())
        .then(data => {
            console.log(data);
			
			if (!data['success'])
				throw new Error(data['errorMessage']);
			
			this.displayProperty(data['properties']);
        })
        .catch(error => console.error('Error fetching properties:', error));
    }
	
	displayProperty (properties) {
		const propertiesList = document.getElementById('propertyList');
		propertiesList.innerHTML = '';
		properties.forEach(property => {
			//name
			const row = document.createElement('tr');
			row.id = `property-row-${property.id}`;
			const nameCell = document.createElement('td');
			nameCell.textContent = property.name;
			row.appendChild(nameCell);
			//seller
			const usernameCell = document.createElement('td');
			usernameCell.textContent = property.seller_id;
			row.appendChild(usernameCell);
			//location
			const locationCell = document.createElement('td');
			locationCell.textContent = property.location;
			row.appendChild(locationCell);
			//price
			const priceCell = document.createElement('td');
			priceCell.textContent = `$${property.price}`;
			row.appendChild(priceCell);
			//view button
			const buttonCell = document.createElement('td');
			const viewButton = document.createElement('button');
			viewButton.textContent = 'View';
			viewButton.addEventListener('click', () => {
				this.viewProperty(property.name, property.type, property.size, property.rooms, property.price, property.location, property.seller_id, property.status);
			});
			buttonCell.appendChild(viewButton);
			//update button
			const updateButton = document.createElement('button');
			updateButton.textContent = 'Update';
			updateButton.addEventListener('click', () => {
				displayUpdateProperty(property.name, property.type, property.size, property.rooms, property.price, property.location, property.status, property.image, property.views, property.seller_id, property.agent_id, property.id);
			});
			buttonCell.appendChild(updateButton);
			//delete button
			const deleteButton = document.createElement('button');
			deleteButton.textContent = 'Delete';
			deleteButton.addEventListener('click', () => {
				if (confirm(`Are you sure you want to delete Property ${property.name}?`)){
					this.deleteProperty(property.id, property.name);
				}
			});
			buttonCell.appendChild(deleteButton);
			row.appendChild(buttonCell);
			propertiesList.appendChild(row);
		});
	}

    getAgentRatings() {
        fetch(`AgentRating.php?action=getAgentRatings&agentId=${window.userID}`)
        .then(response => response.json())
        .then(data => {
            console.log(data);
			if (!data.success) // Access 'success' directly instead of using brackets
				throw new Error(data.errorMessage);
			
            const ratingList = document.getElementById('ratingList');
            ratingList.innerHTML = '';
            data.ratings.forEach(rating => {
                const ratingDiv = document.createElement('div');
                ratingDiv.classList.add('rating');
                const userP = document.createElement('p');
                userP.classList.add('user');
                userP.textContent = rating.customer_id;
                ratingDiv.appendChild(userP);
                const scoreP = document.createElement('p');
                scoreP.classList.add('score');
                scoreP.textContent = `Rating: ${rating.rating}`;
                ratingDiv.appendChild(scoreP);
                ratingList.appendChild(ratingDiv);
            });

            // Calculate average rating
            const totalRatings = data.ratings.length;
            const totalScore = data.ratings.reduce((sum, rating) => sum + parseInt(rating.rating), 0);
            const averageRating = totalScore / totalRatings;
            // Append average rating to element
            const avgRatingElement = document.getElementById('AvgRating');
            avgRatingElement.textContent = `${averageRating.toFixed(2)} out of 5 stars`;
        })
        .catch(error => console.error('Error fetching ratings:', error));
    }

    //Agent Review 
    getAgentReviews() {
        fetch(`AgentReview.php?action=getAgentReviews&agentId=${window.userID}`)
        .then(response => response.json())
        .then(data => {
			console.log(data);
			if (!data.success) // Access 'success' directly instead of using brackets
				throw new Error(data.errorMessage);
				
            const reviewList = document.getElementById('reviewList'); // Make sure 'reviewList' is the correct ID in your HTML
            reviewList.innerHTML = '';
            data.reviews.forEach(review => {
                const reviewDiv = document.createElement('div');
               // reviewDiv.classList.add('review'); // 
    
                const userP = document.createElement('p');
                userP.classList.add('user');
                userP.textContent = review.customer_id; // 
                reviewDiv.appendChild(userP);
    
                const reviewP = document.createElement('p');
                //reviewP.classList.add('review-text'); //
                reviewP.textContent = `Review: ${review.review}`; // 
                reviewDiv.appendChild(reviewP);
    
                reviewList.appendChild(reviewDiv);
            });
        })
        .catch(error => console.error('Error fetching reviews:', error));
    }
    
    viewProperty(name, type, size, rooms, price, location, seller_id, status) {
		const modalContent = document.getElementById('modal-content');
		modalContent.innerHTML = `
			<span class="close">&times;</span>
			<div class = "property-view">
			<h2>Name: ${name}</h2>
			<p>Type: ${type}</p>
			<p>Sqft: ${size}</p>
			<p>Rooms: ${rooms}</p>
			<p>Price: $${price}</p>
			<p>Location: ${location}</p>
			<p>Seller: ${seller_id}</p>
			<p>Status: ${status}</p>
		`;
		modalFeatures();
    }

    //Delete Property function
    deleteProperty(propertyId, name) {  
        fetch(`AgentView.php?action=deleteProperty`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ propertyId })
        })
        .then(response => response.json())
        .then(data => {
            if (data['success']) {
                alert(`Property ${name} was deleted successfully!`);
				loadContent('AgentView.php');
            } else {
                throw new Error(data['errorMessage']);
            }
        })
        .catch(error => console.error('Error deleting property:', error));
    }

    //UPDATE//
    updateAgentProperty = (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id, id) => {
        fetch('AgentView.php?action=updateProperty', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, type, size, rooms, price, location, status, image, views, seller_id, agent_id, id })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
			if (data['success']){
                alert(`Property ${name} was updated successfully!`);
				this.getAgentProperties();
			}
			else {
				throw new Error(data['errorMessage']);
			}
        })
        .catch(error => console.error('Error updating property:', error));
    }
    
    //SEARCH//
    searchEngineProperty = () => {		
		// Get value entered in search input field and convert it to lowercase
        const searchInput = document.getElementById('searchProperty').value.toLowerCase();
		const agent_id = window.userID;
		if (searchInput.trim() == ''){
			this.getAgentProperties();
			return;
		}
		
		fetch('AgentView.php?action=searchProperty', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ searchInput, agent_id })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
			
			if (!data['success'])
				throw new Error(data['errorMessage']);
			
			this.displayProperty(data['properties']);
        })
        .catch(error => console.error('Error fetching properties:', error));
    }
	
	createNewProperty = (event) => {
		event.preventDefault();
		const name = document.getElementById('name').value;
		const type = document.getElementById('type').value;
		const size = document.getElementById('sqfeet').value;
		const rooms = document.getElementById('rooms').value;
		const price = document.getElementById('price').value;
		const location = document.getElementById('location').value;
		const status = "available";
		const image = "https://i.insider.com/655582f84ca513d8242a5725?width=700";
		const views = 0;
		const seller_id = document.getElementById('seller').value;
		
		fetch('AgentCreate.php?action=createProperty', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({ name, type, size, rooms, price, location, status, image, views, seller_id})
		})
		.then(response => response.json())
		.then(data => {
			console.log(data);
			if (data['success']){
                alert(`Property ${name} was created successfully!`);
				loadContent('AgentView.php');
			}
			else if (data['errorMessage'].toLowerCase().includes("foreign key constraint")){
				alert (`Seller "${seller_id}" does not exists!`);
			}
			else 
				throw new Error(data['errorMessage']);
        })
        .catch(error => console.error('Error creating property :', error));
    }
    
}

function displayUpdateProperty(name, type, size, rooms, price, location, status, image, views, seller_id, agent_id, id) {
    const Form = document.getElementById('modal-content');
    
    Form.style.display = 'block';
    
    Form.innerHTML = `
    <span class="close">&times;</span>
    <form id="UpForm" style="width:50%">
    <input type="hidden" id="id" name="id" value="${id}" required>
	<br><strong>Name:</strong>
    <input type="text" id="name" name="name" value="${name}" placeholder="Name" required>
	<br><strong>Type:</strong>
    <input type="text" id="type" name="type" value="${type}" placeholder="Type" required required>
	<br><strong>Size:</strong>
    <input type="text" id="size" name="size" value="${size}" placeholder="Square Feet" required>
	<br><strong>Rooms:</strong>
    <input type="text" id="rooms" name="rooms" value="${rooms}" placeholder="Rooms" required>
	<br><strong>Price ($):</strong>
    <input type="text" id="price" name="price" value="${price}" placeholder="Price" required required>
	<br><strong>location:</strong>
    <input type="text" id="location" name="location" value="${location}" placeholder="Location" required>
	<br><strong>Status:</strong>
    <input type="text" id="status" name="status" value="${status}" placeholder="Status" required>
    <input type="hidden" id="image" name="image" value="${image}">
    <input type="hidden" id="views" name="views" value="${views}">
    <input type="hidden" id="seller_id" name="seller_id" value="${seller_id}">
    <input type="hidden" id="agent_id" name="agent_id" value="${agent_id}">
	<br><input id="SubmitUpdateProperty" type="submit" value="Submit"><br>
    </form>
    `;

    document.getElementById('UpForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const updatedId = document.getElementById('id').value;
        const updatedName = document.getElementById('name').value;
        const updatedType = document.getElementById('type').value;
        const updatedSize = document.getElementById('size').value;
        const updatedRooms = document.getElementById('rooms').value;
        const updatedPrice = document.getElementById('price').value;
        const updatedLocation = document.getElementById('location').value;
        const updatedStatus = document.getElementById('status').value;
        const updatedImage = document.getElementById('image').value;
        const updatedViews = document.getElementById('views').value;
        const updatedSeller = document.getElementById('seller_id').value;
        const updatedAgent = document.getElementById('agent_id').value;
        
		// If validation passes, proceed with confirmation popup
		const confirmation = confirm(`Are you sure you want to update Property ${name}'s details?`);
		if (confirmation) {
			// Call the update property API function
			Agent.updateAgentProperty(updatedName, updatedType, updatedSize, updatedRooms, updatedPrice, updatedLocation, updatedStatus, updatedImage, updatedViews, updatedSeller, updatedAgent, updatedId);
        }
			
		document.getElementById("myModal").style.display = "none";
    });

    modalFeatures();
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

const Agent = new AgentApi();

function initializeView() {
    Agent.getAgentProperties();

    document.getElementById('searchProperty').addEventListener('input', Agent.searchEngineProperty);
}

function initializeRating() {
    Agent.getAgentRatings();
}

window.onload = () => {
    loadContent('AgentView.php');
}

function initializeReview() {
    Agent.getAgentReviews();
}

function loadContent(page) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Attempt to set the innerHTML property of an element
            var element = document.querySelector(".body"); // Change to the appropriate selector
            if (element !== null) {
                element.innerHTML = this.responseText;
                
                if (page === 'AgentView.php') {
                    initializeView();
                } else if (page === "AgentRating.php") {
                    initializeRating();
                } else if (page === "AgentReview.php") {
                    initializeReview();
                }else if (page === "AgentCreate.php") {
                    document.getElementById('createPropForm').addEventListener('submit', function(event) {
						event.preventDefault();
						Agent.createNewProperty(event);
					});
				}
            } else {
                console.error("Element not found.");
            }
        }
    };
    xhttp.open("GET", page, true);
    xhttp.send();
}