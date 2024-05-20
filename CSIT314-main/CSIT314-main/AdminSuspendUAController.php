<?php
// AdminSuspendUAController.php
require_once 'UserAccount.php';

class AdminSuspendUAController {
    private $entity;

    public function __construct() {
        $this->entity = new UserAccount();
    }

    public function suspendAccount($username) {
        return $this->entity->suspendUserAccount($username);
    }
}

?>