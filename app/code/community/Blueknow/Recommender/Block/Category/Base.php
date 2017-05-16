<?php
/**
 * Base category block.
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
class Blueknow_Recommender_Block_Category_Base extends Blueknow_Recommender_Block_Base {
	
	/**
	 * Category domain object.
	 * @var Blueknow_Recommender_Model_Category
	 */
	protected $_category;
	
	/**
	 * Prepare category before render it.
	 */
	public function _beforeToHtml() {
		parent::_beforeToHtml();
		$this->_category = Mage::getModel('blueknow_recommender/Category');
	}
	
	/**
	 * Get current category level.
	 * @return string
	 */
	public function getCategoryLevel() {
		return "cat_L" . $this->_category->getLevel() . "_id";
	}
	
	/**
	 * Get current category identifier.
	 * @return string
	 */
	public function getCategoryId() {
		return $this->_category->getIdentifier();
	}
}