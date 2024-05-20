<?php

require_once 'Konohadb.php';

class ShortList {
    private $db, $conn;
    public $id, $property_id, $buyer_id;

    public function __construct($id = null, $property_id = null, $buyer_id = null) {
        if ($id !== null && $property_id !== null && $buyer_id !== null) {
            $this->id = $id;
            $this->property_id = $property_id;
            $this->buyer_id = $buyer_id;
        }
    }

    public function getCountByProperty($property_id) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();

        $sql = "SELECT COUNT(*) AS count FROM shortlist WHERE property_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $property_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $stmt->close();

        $this->db->closeConn();

        return $count;
    }

    public function getBuyerShortlistProperties($buyer_id) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();
		
		$property_id = array(); 

        $sql = "SELECT * FROM shortlist WHERE buyer_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $buyer_id);
		
		if ($stmt->execute()) {
			$result = $stmt->get_result();
			
			while ($row = $result->fetch_assoc()) { 
                $property_id[] = $row['property_id'];
            }
			
            $stmt->close();
            $this->db->closeConn();
            return ['success' => true, 'property_id' => $property_id];
        } else {
            $errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'errorMessage' => $errorMessage];
		}
    }

    public function addShortListProperty($property_id, $buyer_id) {
        if ($this->shortListExists($property_id, $buyer_id)) {
            return ['success' => false, 'errorMessage' => 'Property already shortlisted!'];
		}

        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();

        $sql = "INSERT INTO shortlist (property_id, buyer_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $property_id, $buyer_id);

        if ($stmt->execute()) {
            $stmt->close();
            $this->db->closeConn();
            return ['success' => true];
        } else {
            $errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'errorMessage' => $errorMessage];
		}
    }

    public function deleteShortlistProperty ($property_id, $buyer_id) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();

        $sql = "DELETE FROM shortlist WHERE property_id = ? AND buyer_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $property_id, $buyer_id);

        if ($stmt->execute()) {
            $stmt->close();
            $this->db->closeConn();
            return ['success' => true];
        } else {
            $errorMessage = $this->conn->error;
			$this->db->closeConn();
            return ['success' => false, 'errorMessage' => $errorMessage];
		}
    }
	
	public function shortListExists($property_id, $buyer_id) {
        $this->db = new DBconn(); 
        $this->conn = $this->db->getConn();

        $sql = "SELECT * FROM shortlist WHERE property_id = ? AND buyer_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $property_id, $buyer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $this->db->closeConn();

        return $result->num_rows > 0;
    }
}
?>
