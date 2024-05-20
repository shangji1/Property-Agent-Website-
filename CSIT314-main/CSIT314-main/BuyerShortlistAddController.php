<?php
require_once 'ShortList.php';

class BuyerShortlistAddController {
    private $entity;

    public function __construct () {
        $this->entity = new ShortList();
    }

    public function addShortListProperty ($property_id, $buyer_id) {
        $result = $this->entity->addShortListProperty($property_id, $buyer_id);
        return $result;
    }
}

?>