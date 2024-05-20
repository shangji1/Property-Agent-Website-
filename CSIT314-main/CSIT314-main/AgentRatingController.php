<?php
require_once 'Rating.php';


class AgentRatingController {
    private $entity;

    public function __construct() {
        $this->entity = new Rating();
    }

    public function getAgentRatings($agent_id) {
        // Retrieve user profiles from the database
        $result = $this->entity->getAgentRatings($agent_id);

        return $result;
    }
}

?>