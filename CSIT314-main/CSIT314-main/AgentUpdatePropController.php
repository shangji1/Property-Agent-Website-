<?php
require_once 'PropertyListing.php';

class AgentUpdatePropController {
    private $entity;

    public function __construct() {
        $this->entity = new PropertyListing();
    }

    public function updateProperty ($name, $type, $size, $rooms, $price, $location, $status, $image, $views, $seller_id, $agent_id, $id) {
        $properties = $this->entity->updateProperty($name, $type, $size, $rooms, $price, $location, $status, $image, $views, $seller_id, $agent_id, $id);
        return $properties;
    }
}

?>