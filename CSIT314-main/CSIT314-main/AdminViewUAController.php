<?php
require_once 'UserAccount.php';

class AdminViewUAController {
    private $entity;

    public function __construct() {
        $this->entity = new UserAccount();
    }

    public function getUserAccounts($page) {
        // Retrieve user profiles from the database
        $result = $this->entity->getUserAccounts($page);

        return $result;
    }
}

?>