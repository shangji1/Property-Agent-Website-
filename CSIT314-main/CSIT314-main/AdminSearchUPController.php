<?php
require_once 'UserProfile.php';

class AdminSearchUPController {
    private $entity;

    public function __construct() {
        $this->entity = new UserProfile();
    }

    public function searchUserProfile($name) {
        // Retrieve user profiles from the database
        $result = $this->entity->searchUserProfile($name);

        return $result;
    }
}

?>