<?php
require_once 'PropertyListing.php';

class BuyerViewALLPropertyController {
    private $entity;

    public function __construct() {
        $this->entity = new PropertyListing();
    }

    public function getBuyerProperties($status, $page) {
        $results = $this->entity->getBuyerProperties($status, $page);

        return $results;
    }
}
?>