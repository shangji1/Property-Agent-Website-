<?php
require_once 'ShortList.php';

class BuyerShortlistDeleteController {
    private $entity;

    public function __construct () {
        $this->entity = new ShortList();
    }

    public function deleteShortListProperty ($property_id, $buyer_id) {
        $result = $this->entity->deleteShortListProperty($property_id, $buyer_id);
        return $result;
    }
}

?>