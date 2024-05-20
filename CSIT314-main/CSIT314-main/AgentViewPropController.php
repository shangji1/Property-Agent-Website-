<?php
require_once 'PropertyListing.php';

class AgentViewPropController {
    private $entity;

    public function __construct() {
        $this->entity = new PropertyListing();
    }

    public function getPropertiesByAgent($agent_id) {
        $properties = $this->entity->getPropertiesByAgent($agent_id);

        return $properties;
    }
}

?>