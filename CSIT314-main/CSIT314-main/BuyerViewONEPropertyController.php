<?php
require_once 'PropertyListing.php';

class BuyerViewONEPropertyController {
    private $entity;

    public function __construct() {
        $this->entity = new PropertyListing();
    }

    public function getPropertyByID($id) {
        $result = $this->entity->getPropertyById($id);
        return $result;
    }
    
}
?>