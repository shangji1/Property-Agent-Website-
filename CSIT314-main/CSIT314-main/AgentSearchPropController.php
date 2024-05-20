<?php
require_once 'PropertyListing.php';

class AgentSearchPropController {
    private $entity;

    public function __construct() {
        $this->entity = new PropertyListing();
    }

    public function searchProperty($name, $agent_id){
        return $this->entity->searchProperty($name, $agent_id);
    }
}

?>