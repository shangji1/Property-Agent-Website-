<?php
require_once 'UserAccount.php';

class AdminUpdateUAController {
    private $entity;

    public function __construct () {
        $this->entity = new UserAccount();
    }
	
    public function updateUserAccount($username, $email, $password, $activeStatus, $profile_id) {
        // Call the updateUserAccount method from UserAccEntity
        $result = $this->entity->updateUserAccount($username, $email, $password, $activeStatus, $profile_id);
        
        return $result;
    }
}
?>
