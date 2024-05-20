<?php
require_once 'Konohadb.php';

class Rating implements JsonSerializable{
    private $db, $conn;
    private $rating, $customer_id, $agent_id;

    public function __construct($rating = null, $customer_id = null, $agent_id = null) {
        if ($rating !== null && $customer_id !== null && $agent_id !== null) {
            $this->rating = $rating;
            $this->customer_id = $customer_id;
            $this->agent_id = $agent_id;
        }
    }

    public function getAgentRatings($agent_id) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();

        $ratings = array(); 

        $sql = "SELECT * FROM ratings WHERE agent_id = '$agent_id'";

        $result = $this->conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rating = new Rating(
                    $row['rating'],
                    $row['customer_id'],
                    $row['agent_id']
                );
 
                $ratings[] = $rating;
            }
			
			$this->db->closeConn();
            return ['success' => true, 'ratings' => $ratings];
        } else {
			$errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'errorMessage' => $errorMessage];
		}
    }

    // Create rating
    public function createSaleRating($rating, $customer_id, $agent_id) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();

        // Prepare SQL statement
        $stmt = $this->conn->prepare("INSERT INTO ratings (rating, customer_id, agent_id) VALUES (?, ?, ?)");
        
        // Bind parameters
        $stmt->bind_param("iss", $rating, $customer_id, $agent_id);
        
        // Execute the statement
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
			'rating' => $this->rating,
            'customer_id' => $this->customer_id,
            'agent_id' => $this->agent_id
		);
	}
}
?>