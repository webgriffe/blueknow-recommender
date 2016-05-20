<?php
/**
 * Customer object model.
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
 * @since		1.0.0
 * 
 */
class Blueknow_Recommender_Model_Customer extends Varien_Object {
	
	/**
	 * Customer identifier.
	 * @var mixed|null
	 */
	private $_customerId;
	
	/**
	 * Get the identifier of the current user logged, if any.
	 * @return mixed|null
	 * @deprecated 1.1.0.GA (see issue MAGPLUGIN-7); use #getEmail() instead.
	 */
	public function getIdentifier() {
		if (empty($this->_customerId)) {
			$session = Mage::getSingleton('customer/session');
			if ($session->isLoggedIn()) {
				$this->_customerId = $session->getCustomer()->getId();
			} else {
				$this->_customerId = null; //not logged
			}
		}
		return $this->_customerId;
	}
	
	/**
	 * Get the email of the current user logged, if any.
	 * @return mixed|null
	 * @since 1.1.0.GA (see issue MAGPLUGIN-7).
	 */
	//[2012-11-16] Issue MAGPLUGIN-7: Get user e-mail instead of user identifier in user logged event.
	public function getEmail() {
		if (empty($this->_customerId)) {
			$session = Mage::getSingleton('customer/session');
			if ($session->isLoggedIn()) {
				$this->_customerId = Mage::helper('customer')->getCustomer()->getEmail();
			} else {
				$this->_customerId = null; //not logged
			}
		}
		return $this->_customerId;
	}
}