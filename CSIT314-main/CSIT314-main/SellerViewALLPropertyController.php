<?php
require_once 'PropertyListing.php';

class SellerViewALLPropertyController {
    private $entity;

    public function __construct() {
        $this->entity = new PropertyListing();
    }

    public function getSellerProperties($seller_id) {
        // Retrieve user profiles from the database
        $result = $this->entity->getSellerProperties($seller_id);

        return $result;
    }
}
?>
