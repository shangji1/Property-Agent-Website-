<?php
require_once 'Konohadb.php';

class Review implements JsonSerializable{
    private $db, $conn;
    private $review, $customer_id, $agent_id;

    public function __construct($review = null, $customer_id = null, $agent_id = null) {
        if ($review !== null && $customer_id !== null && $agent_id !== null) {
            $this->review = $review;
            $this->customer_id = $customer_id;
            $this->agent_id = $agent_id;
        }
    }

    public function getAgentReviews($agent_id) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();

        $reviews = array(); 

        $sql = "SELECT * FROM reviews WHERE agent_id = '$agent_id'";

        $result = $this->conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $review = new Review(
                    $row['review'],
                    $row['customer_id'],
                    $row['agent_id']
                );
 
                $reviews[] = $review;
            }
			
			$this->db->closeConn();
            return ['success' => true, 'reviews' => $reviews];
        } else {
			$errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'errorMessage' => $errorMessage];
		}
    }

    // Create review
    public function createSaleReview($review, $customer_id, $agent_id) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();
        // Prepare SQL statement
        $stmt = $this->conn->prepare("INSERT INTO reviews (review, customer_id, agent_id) VALUES (?, ?, ?)");
    
        // Bind parameters
        $stmt->bind_param("sss", $review, $customer_id, $agent_id);
    
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
			'review' => $this->review,
            'customer_id' => $this->customer_id,
            'agent_id' => $this->agent_id
		);
	}
}
?>