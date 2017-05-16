<?php
/**
 * Category page recommender block.
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
class Blueknow_Recommender_Block_Category_Recommender extends Blueknow_Recommender_Block_Category_Base {
	
	/**
	 * Render block.
	 */
	//[15-02-2012] MAGPLUGIN-39. Add product page recommendations.
	public function _toHtml() {
		//the block is rendered only if home recommendations enabled 
		if ($this->_configuration->isCategoryEnabled()) {
			return parent::_toHtml();
		}
		return '';
	}
}