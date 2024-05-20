<?php
require_once 'ShortList.php';
require_once 'PropertyListing.php';

class SellerViewONEPropertyController {
    private $entity;
    private $shortlistEntity;

    public function __construct() {
        $this->entity = new PropertyListing();
        $this->shortlistEntity = new ShortList();
    }

    public function getPropertyByID($id) {
        $property = $this->entity->getPropertyById($id);
		if (!$property['success'])
			return $property;
        $shortlist = $this->shortlistEntity->getCountByProperty($id);
        return ['success'=> true, 'property' => $property['property'], 'shortlist' => $shortlist];
    }
}
?>
