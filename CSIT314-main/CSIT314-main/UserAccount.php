<?php
require_once 'Konohadb.php';

class UserAccount implements JsonSerializable{
    private $db, $conn;
	private $username, $password, $email, $profile_id, $activeStatus;
	
	public function __construct($username = null, $password = null, $email = null, $activeStatus = null, $profile_id = null) {		
		if($username !== null && $password !== null && $email !== null && $activeStatus !== null && $profile_id !== null){
			$this->username = $username;
			$this->password = $password;
			$this->email = $email;
			$this->activeStatus = $activeStatus;
			$this->profile_id = $profile_id;
		}
    }
	
	public function loginAccount($username, $password, $profile_id){
		$this->db = new DBconn();
		$this->conn = $this->db->getConn();
		
		$stmt = $this->conn->prepare("SELECT activeStatus, COUNT(*) FROM user_accounts WHERE username = ? AND password = ? AND profile_id = ?");
		$stmt->bind_param("ssi", $username, $password, $profile_id);
		
		if ($stmt->execute()) {
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			
			$this->db->closeConn();
			
			if ($row['COUNT(*)'] == 0)
				return ["success" => false, "error" => "Invalid username or password"];
			else if (!$row['activeStatus'])
				return ["success" => false, "error" => "Your account has been suspended. You cannot log in."];
			else 
				return ["success" => true];
        } else {
			$errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'error' => $errorMessage];
        }
	}

    public function getUserAccounts($pageNum) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();
		
		$accounts = array(); 
		
        // Prepare SQL statement to select profiles
        $sql = "SELECT * FROM user_accounts ORDER BY username ASC";
		$stmt = $this->conn->prepare($sql);
		
        // Execute the query    
        if ($stmt->execute()) {
			$result = $stmt->get_result();
			
			while ($row = $result->fetch_assoc()) {
                $account = new UserAccount(
                    $row['username'],
                    $row['password'],
                    $row['email'],
                    $row['activeStatus'],
                    $row['profile_id']
                );
 
                $accounts[] = $account;
            }
			
			// Calculate total number of accounts
            $totalAccounts = count($accounts);
			
            // Calculate start and end indexes for pagination
            $startIndex = ($pageNum - 1) * 25;
            $endIndex = min($startIndex + 25, $totalAccounts);
    
            // Slice the accounts array to get accounts for the current page
            $pagedAccounts = array_slice($accounts, $startIndex, 25);
			
			$this->db->closeConn();
            return ['success' => true, 'accounts' => $pagedAccounts, 'numOfAcc' => $totalAccounts];
        } else {
			$errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'errorMessage' => $errorMessage];
        }
    }	

    public function createUserAccount($username, $email, $password, $activeStatus, $profile_id) {
        // Check if profile exists
		if ($this->accountExists($username)){
            return ['success' => false, 'message' => 'Account already exists!'];
		}
		
		$this->db = new DBconn(); 
        $this->conn = $this->db->getConn();
		
		// Prepare SQL statement
        $stmt = $this->conn->prepare("INSERT INTO user_accounts (username, email, password, activeStatus, profile_id) VALUES (?, ?, ?, ?, ?)");
        
        // Bind parameters
        $stmt->bind_param("sssii", $username, $email, $password, $activeStatus, $profile_id);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Account creation successful
			$this->db->closeConn();
            return ['success' => true];
        } else {
            // Account creation failed
			$errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'message' => 'Error', 'errorMessage' => $errorMessage];
        }
    }
	
	public function accountExists($username) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();
		
		$sql = "SELECT COUNT(*) FROM user_accounts WHERE username = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();

		$this->db->closeConn();
		
		return $row['COUNT(*)'] > 0;
    }

    // Update user account
    public function updateUserAccount($username, $email, $password, $activeStatus, $profile_id) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();
		
		// Prepare SQL statement
        $stmt = $this->conn->prepare("UPDATE user_accounts SET email = ?, password = ?, activeStatus = ?, profile_id = ? WHERE username = ?");
        
        // Bind parameters
        $stmt->bind_param("ssiis", $email, $password, $activeStatus, $profile_id, $username);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Account update successful
			$this->db->closeConn();
            return ['success' => true];
        } else {
            // Account update failed
			$errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'errorMessage' => $errorMessage];
        }
    }
	
	public function searchUserAccount($username){
		$this->db = new DBconn(); 
        $this->conn = $this->db->getConn();
		
		$sql = "SELECT * FROM user_accounts WHERE username LIKE CONCAT('%', ?, '%')";
		$stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
		
		$accounts = array(); 
    
        if ($stmt->execute()) {
			$result = $stmt->get_result();
			
			while ($row = $result->fetch_assoc()) {
                $account = new UserAccount(
                    $row['username'],
                    $row['password'],
                    $row['email'],
                    $row['activeStatus'],
                    $row['profile_id']
                );
 
                $accounts[] = $account;
            }
			
			$this->db->closeConn();
            return ['success' => true, 'accounts' => $accounts];
        } else {
			$errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'errorMessage' => $errorMessage];
        }
	}

    //Suspend user for userAccount
    public function suspendUserAccount($username) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();
		
		$sql = "UPDATE user_accounts SET activeStatus = 0 WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
    
        if ($stmt->execute()) {
			$this->db->closeConn();
            return ['success' => true];
        } else {
			$errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'errorMessage' => $errorMessage];
        }
    }
	
	public function jsonSerialize() {
		return array(
			'username' => $this->username,
			'password' => $this->password,
			'email' => $this->email,
			'activeStatus' => $this->activeStatus,
			'profile_id' => $this->profile_id
		);
	}
}
?>
