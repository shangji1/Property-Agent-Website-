<?php
require_once 'Review.php';

class CreateReviewController {
    private $entity;

    public function __construct() {
        $this->entity = new Review();
    }

    public function createReview($review, $customer_id, $agent_id) {
        $agentReviews = $this->entity->createSaleReview($review, $customer_id, $agent_id);
        return $agentReviews;
    }

}

?>