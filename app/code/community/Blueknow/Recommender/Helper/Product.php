<?php
/**
 * Product helper.
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
 * @since		1.2.0
 *
 */
//[2012-12-20] This helper has been created due to issue MAGPLUGIN-17.
class Blueknow_Recommender_Helper_Product extends Mage_Core_Helper_Abstract {

	/**
	 * Get current product identifier following 3 different strategies: from cookie, from model and finally from URL.
	 * 
	 * @return string Current product identifier, if any; null otherwise.
	 */
	public function getCurrentProductId() {
		//first strategy: from cookie
		$productId = $this->getCurrentProductIdFromCookie();
		if (!isset($productId)) {
			//second strategy: from model
			$productId = $this->getCurrentProductIdFromModel();
		}
		if (!isset($productId)) {
			//third strategy: from URL
			$productId = $this->getCurrentProductIdFromUrl();
		}
		return isset($productId) ? $productId : null;
	}
	
	/**
	 * Get current product identifier from model.
	 * 
	 * @return string Current product identifier, if any; null otherwise.
	 */
	public function getCurrentProductIdFromModel() {
		//Retrieve from current product
		$product = Mage::helper('catalog')->getProduct();
		return isset($product) ? $product->getId() : null;
	}
	
	/**
	 * Get current product identifier from Blueknow's clicked cookie.
	 * Cookie format: {product_id}@{source}@{location} (example: 1234@item2item@product).
	 * 
	 * @return string Current product identifier, if cookie exists; null otherwise.
	 */
	public function getCurrentProductIdFromCookie() {
		$productId = null;
		//Check if cookie exists
		if (isset($_COOKIE["_bkclk"])) {
			//Parse cookie
			$cookieValue = explode("@", $_COOKIE["_bkclk"]);
			//Return identifier
			$productId = $cookieValue[0]; 
		}
		//Check product Id not empty (example: @item2item@product)
		return empty($productId) ? null : $productId;
	}
	
	/**
	 * Get current product identifier from URL.
	 * 
	 * @return string Current product identifier, if any; null otherwise.
	 */
	public function getCurrentProductIdFromUrl() {
		//Retrieve product using product name from url
		$currentUrl = Mage::helper('core/url')->getCurrentUrl();
		$productName = basename($currentUrl);
		//Try to load product and retrieve Id
		$product = Mage::getModel('core/url_rewrite')
			->setStoreId(Mage::app()->getStore()->getId())
			->loadByRequestPath($productName);
		return isset($product) ? $product->getProductId() : null;
	}
}
