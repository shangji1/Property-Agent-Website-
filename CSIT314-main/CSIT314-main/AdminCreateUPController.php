<?php
require 'UserProfile.php';

class AdminCreateUPController {
    private $entity;

    public function __construct () {
        $this->entity = new UserProfile();
    }

    public function createProfile ($name, $activeStatus, $description) {
        $result = $this->entity->createUserProfile($name, $activeStatus, $description);
        return $result;
    }
}

?>