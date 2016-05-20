<?php
/**
 * Product not found tracker block.
 * 
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Blueknow Recommender
 * extension to newer versions in the future. If you wish to customize it for
 * your needs please save your changes before upgrading.
 * 
 * @category	Blueknow
 * @package		Blueknow_Recommender
 * @copyright	Copyright (c) 2010 Blueknow, S.L. (http://www.blueknow.com)
 * @license		GNU General Public License
 * @author		<a href="mailto:santi.ameller@blueknow.com">Santiago Ameller</a>
 * @author		<a href="mailto:josep.ventura@blueknow.com">Josep M Ventura</a>
 * @since		1.2.0
 */
//[18-12-2012] Product not found page tracker.
class Blueknow_Recommender_Block_Product_NotFound_Tracker extends Blueknow_Recommender_Block_Base {
	
	/**
	 * Product identifier.
	 */
	private $_id;
	
	/**
	 * Render block.
	 * @see Blueknow_Recommender_Block_Base::_toHtml()
	 */
	public function _toHtml() {
		//Retrieve id from helper
		$this->_id = Mage::helper('blueknow_recommender/product')->getCurrentProductId();
		if (isset($this->_id)) {
			return parent::_toHtml();
		}
		return '';
	}
	
	/**
	 * Get current product id.
	 */
	public function getCurrentProductId() {
		return $this->_id;
	}
}