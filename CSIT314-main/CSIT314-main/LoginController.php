<?php
// Controller class to process login requests
require_once 'UserAccount.php';
require_once 'UserProfile.php';

class LoginController {
    private $entity, $entityP;

    public function __construct() {
        // Initialize Entity object
        $this->entity = new UserAccount();
        $this->entityP = new UserProfile();
    }
	
	public function auth($username, $password, $profile_id){
		$profileState = $this->entityP->loginProfile($profile_id);
		$userA = $this->entity->loginAccount($username, $password, $profile_id);
		if (!$profileState['success']){
			return $profileState;
		}
		else if (!$userA['success']){
			return $userA;
		}
		else if ($profileState['success'] && $userA['success']){
			$_SESSION['userID'] = $username;
			$_SESSION['profile'] = $profileState['name'];
			$_SESSION['logged'] = true;
			return ["success" => true];
		}
		else{
			return ["success" => false, "error" => "Unknown Error"];
		}
	}
    
    public function getUserProfiles() {
        // Retrieve user profiles from the database
        $profiles = $this->entityP->getUserProfiles();

        return $profiles;
    }
}

?>