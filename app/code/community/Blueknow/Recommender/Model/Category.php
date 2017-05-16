<?php
/**
 * Category object model.
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
 * @since		1.3.0
 * 
 */
class Blueknow_Recommender_Model_Category extends Varien_Object {
	
	const DATA_CATEGORY = 'current_category';
	
	/**
	 * Determine if current category is available.
	 * @var bool
	 */
	private $_hasCategory;
	
	/**
	 * Category identifier.
	 * @var string
	 */
	private $_id;
	
	/**
	 * Category level.
	 * @var number
	 */
	private $_level;
	
	public function __construct() {
		parent::__construct();
		$this->_getCategory(); //loading of current category
		$this->_hasCategory = $this->hasData(self::DATA_CATEGORY) && $this->getData(self::DATA_CATEGORY) instanceof Mage_Catalog_Model_Category;
	}
	
	/**
	 * Get current category identifier.
	 * @return string
	 */
	public function getIdentifier() {
		if (empty($this->_id) && $this->_hasCategory) {
			$this->_id = $this->_getCategory()->getId();
		}
		return $this->_id;
	}
	
	/**
	 * Get current category level.
	 * @return string
	 */
	public function getLevel() {
		if (empty($this->_level) && $this->_hasCategory) {
			$this->_level = $this->_getCategory()->getLevel();
		}
		//Remove 1 level, Home is considered L1.
		return $this->_level -1;
	}
	
	/**
	 * Get current Category.
	 * @return Mage_Catalog_Model_Category
	 */
	protected function _getCategory() {
		if (!$this->hasData(self::DATA_CATEGORY) || !$this->getData(self::DATA_CATEGORY) instanceof Mage_Catalog_Model_Category) {
			$this->setData(self::DATA_CATEGORY, Mage::helper('catalog')->getCategory());
		}
		return $this->getData(self::DATA_CATEGORY);
	}
}