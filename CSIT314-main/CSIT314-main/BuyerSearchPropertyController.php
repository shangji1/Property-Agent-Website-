<?php
require_once 'PropertyListing.php';

class BuyerSearchPropertyController {
    private $entity;

    public function __construct() {
        $this->entity = new PropertyListing();
    }

    public function searchBuyerProperty($status, $name, $pageNum) {
        return $this->entity->searchBuyerProperty($status, $name, $pageNum);
    }
}

?>