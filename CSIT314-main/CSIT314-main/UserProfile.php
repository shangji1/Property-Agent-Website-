<?php
require_once 'Konohadb.php';

class UserProfile implements JsonSerializable{
    private $db, $conn;
	private $id, $name, $activeStatus, $description;
	
	public function __construct($id = null, $name = null, $activeStatus = null, $description = null) {
        if ($id !== null && $name !== null && $activeStatus !== null && $description !== null){
			$this->id = $id;
			$this->name = $name;
			$this->activeStatus = $activeStatus;
			$this->description = $description;
		}
    }

	public function loginProfile($id){
		$this->db = new DBconn();
		$this->conn = $this->db->getConn();
		
		$stmt = $this->conn->prepare("SELECT name, activeStatus FROM user_profiles WHERE id = ?");
		$stmt->bind_param("i", $id);
		
		if ($stmt->execute()) {
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			
			$this->db->closeConn();
			
			if (!$row['activeStatus'])
				return ["success" => false, "error" => "Your profile has been suspended. You cannot log in."];
			else 
				return ["success" => true, "name" => $row['name']];
        } else {
			$errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'error' => $errorMessage];
        }
	}

    public function createUserProfile ($name, $activeStatus, $description) {
		// Check if profile exists
		if ($this->profileExists($name)){
            return ['success' => false, 'message' => 'Profile already exists!'];
		}
		
		$this->db = new DBconn(); 
        $this->conn = $this->db->getConn();
		
		$sql = "INSERT INTO user_profiles (name, activeStatus, description) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sis", $name, $activeStatus, $description);

        if ($stmt->execute()) {
			$this->db->closeConn();
            return ['success' => true];
        } else {
			$errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'message' => 'Error', 'errorMessage' => $errorMessage];
        }
    }
	
	public function profileExists($name) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();
		
		$sql = "SELECT COUNT(*) FROM user_profiles WHERE name = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param("s", $name);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();

		$this->db->closeConn();
		
		return $row['COUNT(*)'] > 0;
    }

    public function getUserProfiles() {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();
		$profiles = array(); // Initialize an empty array to store profiles

        // Prepare SQL statement to select profiles
        $sql = "SELECT * FROM user_profiles";

        // Execute the query
        $result = $this->conn->query($sql);

        // Check if the query was successful
        if ($result) {
            // Fetch profiles and add them to the array
            while ($row = $result->fetch_assoc()) {
                $profile = new UserProfile(
                    $row['id'],
                    $row['name'],
                    $row['activeStatus'],
                    $row['description']
                );
                // Add the UserProfile object to the array
                $profiles[] = $profile;
                //$profiles[] = $row;
            }
			
			$this->db->closeConn();
            return ['success' => true, 'profiles' => $profiles];
        } else {
			$errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'errorMessage' => $errorMessage];
		}
    }

    public function updateUserProfile ($id, $name, $activeStatus, $description) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();
		
		// Prepare SQL statement to update the user profile
        $sql = "UPDATE user_profiles SET name = ?, activeStatus = ?, description = ? WHERE id = ?";
        
        // Prepare the SQL statement
        $stmt = $this->conn->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("sisi", $name, $activeStatus, $description, $id);
        
        // Execute the query
        if ($stmt->execute()) {
			$this->db->closeConn();
            return ['success' => true];
        } else {
			$errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'errorMessage' => $errorMessage];
        }
    }
	
    public function suspendUserProfile($id) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();
		
		$sql = "UPDATE user_profiles SET activeStatus = 0 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
			$this->db->closeConn();
            return ['success' => true];
        } else {
			$errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'errorMessage' => $errorMessage];
        }
    }
	
	public function searchUserProfile($name){
		$this->db = new DBconn(); 
        $this->conn = $this->db->getConn();
		
		$sql = "SELECT * FROM user_profiles WHERE name LIKE CONCAT('%', ?, '%')";
		$stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $name);
		
		$profiles = array(); 
    
        if ($stmt->execute()) {
			$result = $stmt->get_result();
			
			while ($row = $result->fetch_assoc()) {
                $profile = new UserProfile(
                    $row['id'],
                    $row['name'],
                    $row['activeStatus'],
                    $row['description']
                );
                // Add the UserProfile object to the array
                $profiles[] = $profile;
            }
			
			$this->db->closeConn();
            return ['success' => true, 'profiles' => $profiles];
        } else {
			$errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'errorMessage' => $errorMessage];
        }
	}
	
	public function jsonSerialize() {
		return array(
			'id' => $this->id,
			'name' => $this->name,
			'activeStatus' => $this->activeStatus,
			'description' => $this->description
		);
	}
}

?>