<?php
require_once 'Rating.php';

class CreateRatingController {
    private $entity;

    public function __construct() {
        $this->entity = new Rating();
    }

    public function createRating($rating, $customer_id, $agent_id) {
        $agentRatings = $this->entity->createSaleRating($rating, $customer_id, $agent_id);
        return $agentRatings;
    }

}

?>