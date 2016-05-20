<?php
/**
 * Blueknow Recommender observer.
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
class Blueknow_Recommender_Model_Observer {
	
	/**
	 * Activate new-login flag from Blueknow Recommender session scope.
	 * @param Varien_Event_Observer $observer
	 */
	public function setNewLoginWhenCustomerLogIn(Varien_Event_Observer $observer) {
		$this->_getSession()->setNewLogin();
	}
	
	/**
	 * Deactivate new-login flag from Blueknow Recommender session scope.
	 * @param Varien_Event_Observer $observer
	 */
	public function unsetNewLoginAfterTracking(Varien_Event_Observer $observer) {
		$this->_getSession()->unsetNewLogin();
	}
	
	/**
	 * Discontinue out of stock products
	 */
	//[04-01-2013] MAGPLUGIN-19. Discontinue products using platform API on some events.
	public function discontinueProduct(Varien_Event_Observer $observer) {
		//Get the order
		$order= $observer->getEvent()->getOrder();
		//Retrieve api
		$api = Mage::getSingleton('blueknow_recommender/platformApiService');
		foreach ($order->getItemsCollection() as $item) {
			//Get the remaining stock quantity
			$stockQuantity = (int)Mage::getModel('cataloginventory/stock_item')->loadByProduct($item->getProductId())->getQty();
			if ($stockQuantity < 1) {
				//Discontinue product
				$api->discontinueProduct($item->getProductId());
			}
		}		
	}
	
	/**
	 * Discontinue deleted product.
	 */
	//[04-01-2013] MAGPLUGIN-19. Discontinue products using platform API on some events.
	public function discontinueDeletedProduct(Varien_Event_Observer $observer) {
		//Retrieve products from event
		$productId = $observer->getEvent()->getProduct()->getId();
		//Discontinue
		$this->_discontinue($productId);
	}
	
	/**
	 * Discontinue disabled product.
	 */
	//[04-01-2013] MAGPLUGIN-19. Discontinue products using platform API on some events.
	public function discontinueDisabledProduct(Varien_Event_Observer $observer) {
		//Retrieve event
		$event = $observer->getEvent();
		//Retrieve the product status
		$status = $event->getStatus();
		//Check if it's disabled
		if ($status == Mage_Catalog_Model_Product_Status::STATUS_DISABLED) {
			$productId = $event->getProduct()->getId();
			//Discontinue
			$this->_discontinue($productId);
		}
	}
	
	/**
	 * Retrieve Blueknow Recommender session object.
	 * @return Mage_Core_Model_Session_Abstract
     */
	protected function _getSession() {
		return Mage::getSingleton('blueknow_recommender/session');
	}
	
	/**
	 * Retrieve API instance and discontinue product
	 * 
	 */
	protected function _discontinue($productId) {
		//Check that we correctly retrieved productId.
		if (isset($productId)) {
			//Retrieve api and discontinue
			$api = Mage::getSingleton('blueknow_recommender/platformApiService');
			$api->discontinueProduct($productId);
		}
	}
}