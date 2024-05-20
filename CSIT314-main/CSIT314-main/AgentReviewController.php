<?php
require_once 'Review.php';


class AgentReviewController {
    private $entity;

    public function __construct() {
        $this->entity = new Review();
    }

    public function getAgentReviews($agent_id) {
        // Retrieve user profiles from the database
        $result = $this->entity->getAgentReviews($agent_id);

        return $result;
    }
}

?>
