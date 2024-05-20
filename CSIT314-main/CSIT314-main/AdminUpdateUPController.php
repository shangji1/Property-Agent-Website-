<?php
require_once 'UserProfile.php';

class AdminUpdateUPController {
    private $entity;

    public function __construct () {
        $this->entity = new UserProfile();
    }

    public function updateProfile($id, $name, $activeStatus, $description) {
        $result = $this->entity->updateUserProfile($id, $name, $activeStatus, $description);
        return $result;
    }
}
?>
