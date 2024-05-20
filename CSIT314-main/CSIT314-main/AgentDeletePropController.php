<?php
require_once 'PropertyListing.php';

class AgentDeletePropController {
    private $entity;

    public function __construct() {
        $this->entity = new PropertyListing();
    }

    public function deleteProperty($id){
        return $this->entity->deleteProperty($id);
    }
}

?>