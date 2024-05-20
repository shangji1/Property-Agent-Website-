<?php
require_once 'ShortList.php';
require_once 'PropertyListing.php';

class BuyerShortListViewController {
    private $entity , $entityP;

    public function __construct () {
        $this->entity = new ShortList();
        $this->entityP = new PropertyListing();
    }

    public function getBuyerShortlistProperties($buyer_id) {
		// Get an array of shortlist property id
		$shortlist = $this->entity->getBuyerShortlistProperties($buyer_id);
		if (!$shortlist['success'])
			return $shortlist;
		
		// Fetch properties using array
        $result = $this->entityP->getBuyerShortlistProperties($shortlist['property_id']);
        return $result;
    }
}

?>