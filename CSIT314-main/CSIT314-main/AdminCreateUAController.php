<?php
require_once 'UserAccount.php';

class AdminCreateUAController {
    private $entity;

    public function __construct () {
        $this->entity = new UserAccount();
    }

    public function createAccount ($username, $email, $password, $activeStatus, $profile_id) {
        $result = $this->entity->createUserAccount($username, $email, $password, $activeStatus, $profile_id);
        return $result;
    }
}

?>