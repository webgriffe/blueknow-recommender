<?php
/**
* Blueknow Platform Api Service.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Blueknow Recommender
* extension to newer versions in the future. If you wish to customize it for
* your needs please save your changes before upgrading.
*
* @category		Blueknow
* @package		Blueknow_Recommender
* @copyright	Copyright (c) 2013 Blueknow, S.L. (http://www.blueknow.com)
* @license		GNU General Public License
* @author		<a href="mailto:josep.ventura@blueknow.com">Josep Maria Ventura</a>
* @since		1.2.0
*/
//[04-01-2013] MAGPLUGIN-19. Discontinue products using platform API.
class Blueknow_Recommender_Model_PlatformApiService extends Zend_Rest_Client {
	
	/**
	 * Define constants and private properties.
	 */
	// Basic uri
	const API_URI = 'https://platformapi.blueknow.com';
	// Discontinued paths
	const DISCONTINUED_PATH = '/catalog/discontinue';
	//Customer stored BK
	private $_bkNumber;
	
	/**
	 * Default singleton constructor
	 */
	function __construct() {
		//Create rest client if not done
		$this->setUri(self::API_URI);
		//Retrieve BK-Number
		if (empty($this->_bkNumber)) {
			$config = Mage::getModel('blueknow_recommender/Configuration');
			$this->_bkNumber = $config->getBKNumber();
		}
	}
	
	/**
	 * Discontinue product using Platform Api.
	 * @param number $productId
	 */
	public function discontinueProduct($productId) {
		//Create path: GET https://platformapi.blueknow.com/catalog/discontinue/{bknumber}/{productId}
		$path =  self::DISCONTINUED_PATH . "/". $this->_bkNumber . "/" . $productId;
		$response = $this->restGet($path);
	}
}