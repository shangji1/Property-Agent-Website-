<?php 
require_once 'config.php';

class DBconn {
    private $db_server, $db_user, $db_pass, $db_name, $conn; 

    public function __construct () {
        $this->db_server = DB_SERVER;
        $this->db_user = DB_USER;
        $this->db_pass = DB_PASSWORD;
        $this->db_name = DB_NAME;
        $this->conn = mysqli_connect($this->db_server, $this->db_user, $this->db_pass, $this->db_name);
    }

    public function getConn () {
        return $this->conn;
    }

    public function closeConn() {
        mysqli_close($this->conn);
    }
}
?>