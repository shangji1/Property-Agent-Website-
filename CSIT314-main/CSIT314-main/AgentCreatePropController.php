<?php
require_once 'PropertyListing.php';

class AgentCreatePropController {
    private $entity;

    public function __construct() {
        $this->entity = new PropertyListing();
    }

    public function createProperty($name, $type, $size, $rooms, $price, $location, $status, $image, $views, $seller_id, $agent_id) {
        $result = $this->entity->createProperty($name, $type, $size, $rooms, $price, $location, $status, $image, $views, $seller_id, $agent_id);
        return $result;
    }

}

?>