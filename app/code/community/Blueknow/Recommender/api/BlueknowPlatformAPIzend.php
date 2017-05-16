<?php
/**
* Blueknow Platform Api Zend.
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
//[20-02-2013] MAGPLUGIN-37. Externalize platform API.
//[04-01-2013] MAGPLUGIN-19. Discontinue products using platform API.
include_once ('BlueknowPlatformAPI.php');
class BlueknowPlatformAPIzend extends Zend_Rest_Client implements BlueknowPlatformAPI {
	
	/** Singleton instance */
	public static $instance;
	/** Customer configured bkNumber */
	private $_bkNumber;
	
	/**
	 * Default singleton constructor
	 */
	function __construct() {
		//Create rest client using default URI.
		$this->setUri(self::API_URI);
		//Retrieve BK-Number
		if (empty($this->_bkNumber)) {
			$config = Mage::getModel('blueknow_recommender/Configuration');
			$this->_bkNumber = $config->getBKNumber();
		}
	}
	
	/**
	 * Get instance of platform API.
	 */
	public static function getInstance() {
		//Check instance constructed
		if (!self::$instance instanceof self) {
			self::$instance = new self();
		}
		//Return the instance
		return self::$instance;
	}
	
	/**
	 * Discontinue product using Platform Api.
	 * @param number $productId
	 */
	public function discontinueProduct($productId) {		
		//Create path: GET https://platformapi.blueknow.com/catalog/discontinue/{bknumber}/{productId}
		$path = self::DISCONTINUED_PATH . "/". $this->_bkNumber . "/" . $productId;
		//Try the request
		try {
			// Get request
			$response = $this->restGet($path);			
	    // Connection exception
		} catch (Zend_Http_Client_Adapter_Exception $e) {
			Mage::log("Client exception " . $e, null, "blueknowPlatformAPI.log");
	    // General exceptions
		} catch (Exception $e) {
	    	Mage::log("General exception " . $e, null, "blueknowPlatformAPI.log");	
		}
	}
}