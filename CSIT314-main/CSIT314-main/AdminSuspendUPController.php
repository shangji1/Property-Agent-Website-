<?php
//Controller class to process log in requests 
require_once 'UserProfile.php';

class AdminSuspendUPController {
    private $entity;

    public function __construct() {
        $this->entity = new UserProfile();
    }

    public function suspendProfile($id) {
        $result = $this->entity->suspendUserProfile($id);
        return $result;
    }
}
?>
