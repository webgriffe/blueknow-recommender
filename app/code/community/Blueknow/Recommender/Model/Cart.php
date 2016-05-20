<?php
/**
 * Shopping cart object model.
 * 
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Blueknow Recommender
 * extension to newer versions in the future. If you wish to customize it for
 * your needs please save your changes before upgrading.
 * 
 * @category	Blueknow
 * @package		Blueknow_Recommender
 * @copyright	Copyright (c) 2013 Blueknow, S.L. (http://www.blueknow.com)
 * @license		GNU General Public License
 * @author		<a href="mailto:santi.ameller@blueknow.com">Santiago Ameller</a>
 * @author		<a href="mailto:josep.ventura@blueknow.com">Josep M Ventura</a>
 * @since		1.0.0
 * 
 */
class Blueknow_Recommender_Model_Cart extends Varien_Object {
	
	/**
	 * Products (identifiers) inside shopping cart.
	 * @var array
	 */
	private $_products;
	
	/**
	 * Get products form current shopping cart.
	 * @return array
	 */
	public function getProducts() {
		if (!$this->_products) { //empty cart is directly returned
			$cart = Mage::helper('checkout/cart')->getCart();
			$ids = $cart->getProductIds();
			$this->filterProductIds($ids);
		}
		return $this->_products;
	}
	
	/**
	 * Retrieve product ids after success checkout in order to track sold products.
	 * 
	 * @since 1.2.0.GA.
	 */
	//[04-01-2013] MAGPLUGIN-21. Track product IDs on checkout for every kind of product.
	protected function filterProductIds($items) {
		//Check if we have a bundle
		$flagBundle = false;
		//Initialize tracker ids
		$trackerIds = array();
		//Loopt through all ids
		foreach ($items as $productId) { 
			//Retrieve original product in order to get TypeId
			$product = Mage::getModel('catalog/product')->load($productId);
			if (isset($product)) {
				//Check product types		
				switch ($product->getTypeId()) {
					//	Bundle product
					case Mage_Catalog_Model_Product_Type::TYPE_BUNDLE:
						//Activate the bundle flag
						$flagBundle = true;
						//Add id
						array_push($trackerIds, $productId);
						break;
					//	Configurable product
					case Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE:
						//Add id
						array_push($trackerIds, $productId);
						break;
					//	Default case
					default:
						//Check for configurable parent
						$parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($productId);
						//Check for bundle parent
						if((!$parentIds) && ($flagBundle)) {
							$parentIds = Mage::getModel('bundle/product_type')->getParentIdsByChild($productId);
						}
						//Retrieve parent and store
						if($parentIds) {
							//Check appropiate parent in our product id array
							$index = $this->getParentIndex($parentIds, $trackerIds);
							//Add the correct parent for id, otherwise skip
							if(isset($index)){
								array_push($trackerIds, $parentIds[$index]);
							}
						} else {
							//Just default product
							array_push($trackerIds, $productId);
						}
						break;
				}
			}
		}
		//Remove duplicates and store
		$this->_products = array_unique($trackerIds);
	}
	
	/**
	 * Get the appropiate parent index
	 * 
	 * @param array $parentIds
	 * @param array $trackerIds
	 * @return number
	 * @since 1.2.0.GA.
	 */
	private function getParentIndex($parentIds, $trackerIds) {
		//Checking all parent ids
		for ($i=0; $i < count($parentIds); $i++) {
			//Find the key
			$parentFound = array_search($parentIds[$i], $trackerIds, $strict = TRUE);
			//If it's integer we get the correct one
			if (is_int($parentFound)) {
				return $i;	
			}
		}
	}
}