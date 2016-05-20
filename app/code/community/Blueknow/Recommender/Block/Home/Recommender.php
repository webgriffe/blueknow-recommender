<?php
/**
 * Home recommender block.
 * 
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Blueknow Recommender
 * extension to newer versions in the future. If you wish to customize it for
 * your needs please save your changes before upgrading.
 * 
 * @category	Blueknow
 * @package		Blueknow_Recommender
 * @copyright	Copyright (c) 2012 Blueknow, S.L. (http://www.blueknow.com)
 * @license		GNU General Public License
 * @author		<a href="mailto:santi.ameller@blueknow.com">Santiago Ameller</a>
 * @author		<a href="mailto:josep.ventura@blueknow.com">Josep M Ventura</a>
 * @since		1.2.0
 * 
 */
class Blueknow_Recommender_Block_Home_Recommender extends Blueknow_Recommender_Block_Base {
	
	/**
	 * Render block.
	 */
	//[21-12-2012] MAGPLUGIN-12. Add home page recommendations.
	public function _toHtml() {
		//the block is rendered only if home recommendations enabled 
		if ($this->_configuration->isHomeEnabled()) {
			return parent::_toHtml();
		}
		return '';
	}
}