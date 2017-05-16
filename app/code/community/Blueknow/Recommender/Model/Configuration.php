<?php
/**
 * Blueknow Recommender configuration object model.
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
class Blueknow_Recommender_Model_Configuration extends Varien_Object {
	
	/*Configuration XML paths*/
	const XML_PATH_BLUEKNOW_ENABLED = 'blueknow/recommender/enabled';
	const XML_PATH_BLUEKNOW_BK_NUMBER = 'blueknow/recommender/bk_number';
	const XML_PATH_BLUEKNOW_CROSS_SELL_ENABLED = 'blueknow/item_to_item/enabled';
	const XML_PATH_BLUEKNOW_CROSS_SELL_NOR = 'blueknow/item_to_item/nor';
	const XML_PATH_BLUEKNOW_UP_SELL_ENABLED = 'blueknow/item_to_basket/enabled';
	const XML_PATH_BLUEKNOW_UP_SELL_NOR = 'blueknow/item_to_basket/nor';
	const XML_PATH_BLUEKNOW_HOME_ENABLED = 'blueknow/item_to_user/enabled';
	const XML_PATH_BLUEKNOW_HOME_NOR = 'blueknow/item_to_user/nor';
	const XML_PATH_BLUEKNOW_CATEGORY_ENABLED = 'blueknow/item_to_category/enabled';
	const XML_PATH_BLUEKNOW_CATEGORY_NOR = 'blueknow/item_to_category/nor';

	/**
	 * Blueknow Recommender extension descriptor/identifier.
	 * @var string
	 */
	private $_descriptor;
	
	/**
	 * Service-is-enabled flag.
	 * @var bool
	 */
	private $_enabled;
	
	/**
	 * BK number assigned to eCommerce.
	 * @var string
	 */
	private $_bkNumber;
	
	/**
	 * Cross-sell-is-enabled flag.
	 * @var bool
	 */
	private $_crossSellEnabled;
	
	/**
	 * Number of cross-sell recommendations to request.
	 * @var number
	 */
	private $_crossSellNOR;
	
	/**
	 * Up-sell-is-enabled flag.
	 * @var bool
	 */
	private $_upSellEnabled;
	
	/**
	 * Number of up-sell recommendations to request.
	 * @var number
	 */
	private $_upSellNOR;
	
	/**
	 * Home-recommendations-is-enabled flag.
	 * @since 1.2.0
	 * @var bool
	 */
	private $_homeEnabled;
	
	/**
	 * Number of home recommendations to request.
	 * @since 1.2.0
	 * @var number
	 */
	private $_homeNOR;
	
	/**
	 * Category-recommendations-is-enabled flag.
	 * @since 1.3.0
	 * @var bool
	 */
	private $_categoryEnabled;
	
	/**
	 * Number of category recommendations to request.
	 * @since 1.3.0
	 * @var number
	 */
	private $_categoryNOR;
	
	/**
	 * Base URL where resource are loaded from (JavaScript APIs, logos, and so on).
	 * @var string
	 */
	private $_baseUrl;
	
	/**
	 * Current language ISO name (http://en.wikipedia.org/wiki/ISO_639).
	 * @var string
	 */
	private $_language;
	
	/**
	 * Current currency.
	 * @var Blueknow_Recommender_Model_Currency.
	 */
	private $_currentCurrency;
	
	/**
	 * Default/base currency.
	 * @var Blueknow_Recommender_Model_Currency.
	 */
	private $_defaultCurrency;
	
	/**
	 * Get service-is-enabled flag from configuration.
	 * @return bool
	 */
	public function isEnabled() {
		if (empty($this->_enabled)) {
			$bkNumber = $this->getBKNumber();
			$this->_enabled = Mage::getStoreConfigFlag(self::XML_PATH_BLUEKNOW_ENABLED) && !empty($bkNumber);
		}
		return $this->_enabled;
	}
	
	/**
	 * Get BK number from configuration.
	 * @return string
	 */
	public function getBKNumber() {
		if (empty($this->_bkNumber)) {
			$this->_bkNumber =  Mage::getStoreConfig(self::XML_PATH_BLUEKNOW_BK_NUMBER);
		}
		return $this->_bkNumber;
	}
	
	/**
	 * Get cross-sell-is-enabled flag from configuration.
	 * @return bool
	 */
	public function isCrossSellEnabled() {
		if (empty($this->_crossSellEnabled)) {
			$this->_crossSellEnabled = Mage::getStoreConfigFlag(self::XML_PATH_BLUEKNOW_CROSS_SELL_ENABLED);
		}
		return $this->_crossSellEnabled;
	}
	
	/**
	 * Get number of cross-sell recommendations from configuration.
	 * @return number
	 */
	public function getCrossSellNOR() {
		if (empty($this->_crossSellNOR)) {
			$this->_crossSellNOR = Mage::getStoreConfig(self::XML_PATH_BLUEKNOW_CROSS_SELL_NOR);
		}
		return $this->_crossSellNOR;
	}
	
	/**
	 * Get up-sell-is-enabled flag from configuration.
	 * @return bool
	 */
	public function isUpSellEnabled() {
		if (empty($this->_upSellEnabled)) {
			$this->_upSellEnabled = Mage::getStoreConfigFlag(self::XML_PATH_BLUEKNOW_UP_SELL_ENABLED);
		}
		return $this->_upSellEnabled;
	}
	
	/**
	 * Get number of up-sell recommendations from configuration.
	 * @return number
	 */
	public function getUpSellNOR() {
		if (empty($this->_upSellNOR)) {
			$this->_upSellNOR = Mage::getStoreConfig(self::XML_PATH_BLUEKNOW_UP_SELL_NOR);
		}
		return $this->_upSellNOR;
	}
	
	/**
	 * Get home-is-enabled flag from configuration.
	 * @return bool
	 */
	public function isHomeEnabled() {
		if (empty($this->_homeEnabled)) {
			$this->_homeEnabled = Mage::getStoreConfigFlag(self::XML_PATH_BLUEKNOW_HOME_ENABLED);
		}
		return $this->_homeEnabled;
	}
	
	/**
	 * Get number of home recommendations from configuration.
	 * @return number
	 */
	public function getHomeNOR() {
		if (empty($this->_homeNOR)) {
			$this->_homeNOR = Mage::getStoreConfig(self::XML_PATH_BLUEKNOW_HOME_NOR);
		}
		return $this->_homeNOR;
	}
	
	/**
	 * Get category-is-enabled flag from configuration.
	 * @since 1.3.0
	 * @return bool
	 */
	public function isCategoryEnabled() {
		if (empty($this->_categoryEnabled)) {
			$this->_categoryEnabled = Mage::getStoreConfigFlag(self::XML_PATH_BLUEKNOW_CATEGORY_ENABLED);
		}
		return $this->_categoryEnabled;
	}
	
	/**
	 * Get number of category recommendations from configuration.
	 * @since 1.3.0
	 * @return number
	 */
	public function getCategoryNOR() {
		if (empty($this->_categoryNOR)) {
			$this->_categoryNOR = Mage::getStoreConfig(self::XML_PATH_BLUEKNOW_CATEGORY_NOR);
		}
		return $this->_categoryNOR;
	}
	
	/**
	 * Get base URL where resources are loaded from.
	 * @return string
	 */
	public function getBaseURL() {
		if (empty($this->_baseUrl)) {
			$protocol = Mage::app()->getStore()->isCurrentlySecure() ? 'https' : 'http';
			$this->_baseUrl = $protocol . '://static.blueknow.com';
		}
		return $this->_baseUrl;
	}
	
	/**
	 * Get currency language ISO name.
	 * @return string
	 */
	public function getLanguage() {
		if (empty($this->_language)) {
			$isoCode = Mage::app()->getLocale()->getLocale();
			$parts = explode('_', $isoCode);
			$language = $parts[0];
			$this->_language = strtoupper($language);
		}
		return $this->_language;
	}
	
	/**
	 * Get current currency.
	 * @return Blueknow_Recommender_Model_Currency.
	 */
	public function getCurrentCurrency() {
		if (!$this->_currentCurrency) {
			$this->_currentCurrency = Mage::helper('blueknow_recommender/Currency')->getCurrentCurrency();
		}
		return $this->_currentCurrency;
	}
	
	/**
	 * Get default currency.
	 * @return Blueknow_Recommender_Model_Currency.
	 */
	public function getDefaultCurrency() {
		if (!$this->_defaultCurrency) {
			$this->_defaultCurrency = Mage::helper('blueknow_recommender/Currency')->getDefaultCurrency();
		}
		return $this->_defaultCurrency;
	}
	
	/**
	 * Get module descriptor.
	 * @return string
	 * @deprecated
	 */
	//[22-01-2013] MAGPLUGIN-22. Deprecated due not use, using jut location on page.
	public function getDescriptor() {
		if (empty($this->_descriptor)) {
			$descriptor = Mage::getConfig()->getModuleConfig('Blueknow_Recommender')->version;
			$descriptor = str_replace('.', '_', $descriptor);
			$this->_descriptor = 'mag_' . $descriptor;
		}
		return $this->_descriptor;
	}
}