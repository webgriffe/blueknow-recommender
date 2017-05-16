<?php
/**
 * Checkout object model.
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
class Blueknow_Recommender_Model_Checkout extends Varien_Object {
	
	/**
	 * Collection of orders.
	 * @var array of Blueknow_Recommender_Model_Checkout_Order
	 */
	private $_orders = array();
	
	/**
	 * Get last successfull orders from current session.
	 * @return array of Blueknow_Recommender_Model_Checkout_Order
	 */
	//[14-12-2012] Updated due to MAGPLUGIN-14, check single or multi shipping
	public function getOrders() {
		if (empty($this->_orders)) {
			//get last quote identifier
			$quoteId = Mage::getSingleton('checkout/session')->getLastQuoteId();
			if ($quoteId) {
				$store = Mage::app()->getStore();
				//orders registry
				$ordersId = array();
				//get quote
	            $quote = Mage::getModel('sales/quote')->load($quoteId);
	            //check if multishipping
	            $multi = $quote->getIsMultiShipping();
		        //Check if multishiping 
	           	if (!$multi) {
	           		//Single shippment case (reserved quoteId = orderId) used by Mage convert utilties.
	           		$order = Mage::getModel('sales/order')->loadByIncrementId($quote->getReservedOrderId());
	           		//Create custom BK order
	           		$this->_orders[] = $this->_createOrder($order, $store);
	            } else {
	            	//Multishipping case load orders
	            	$orders = Mage::getResourceModel('sales/order_collection')->addAttributeToFilter('quote_id', $quoteId)->load();
		            //orders iteration
		            foreach ($orders as $order) {
		            	$orderId = $order->getIncrementId();
		            	if (in_array($orderId, $ordersId)) {
		            		continue; //order already processed
		            	}
		            	//register order
		            	$ordersId[] = $orderId;
		            	//create our own order and add to our list.
		            	$this->_orders[] = $this->_createOrder($order, $store);
					}
	            	
	            }
			}
		}
		return $this->_orders;
	}
	
	/**
	 * Create Blueknow order object containing all products.
	 * @return $bOrder
	 * @since 1.2.0.GA.
	 */
	protected function _createOrder($order, $store) {
		//create our own order
		$bOrder = Mage::getModel('blueknow_recommender/Checkout_Order');
		//Assign ID and other properties
		$bOrder->setId($order->getIncrementId());
		$bOrder->setTotal($store->roundPrice($order->getBaseGrandTotal()));
		$bOrder->setTax($store->roundPrice($order->getBaseTaxAmount()));
		$bOrder->setShipping($store->roundPrice($order->getBaseShippingAmount()));
		//Iterate through order items
		foreach ($order->getAllItems() as $item) {
			//Found parent (configurable products, bundle also?)
			if ($item->getParentItemId()) {
				continue;
			}
			$bProduct = Mage::getModel('blueknow_recommender/Checkout_Product');
			$bProduct->setId($item->getProductId());
			//[20-02-2013] MACPLUGIN-38 Add taxes to price.
			$bProduct->setPrice($store->roundPrice($item->getPriceInclTax()));
			$bProduct->setQuantity(intval($item->getQtyOrdered()));
			//get saleable information
			$bProduct->setSaleable(Mage::getModel('catalog/product')->load($item->getProductId())->isSaleable());
			//add product
			$bOrder->addProduct($bProduct);
		}
		return $bOrder;
	}
}