<?php
/**
* 
* Blueknow Recommender PlatformAPI contract.
*  
* NOTE: This module has been successfully tested on PrestaShop 1.3.x.
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
*
*/
//[20-02-2013] MAGPLUGIN-37. Externalize platform API.
interface BlueknowPlatformAPI {
	
	/**
	 * Default uri constant
	 */ 
	const API_URI = 'https://platformapi.blueknow.com';
	/**
	 * Discontinued path constant
	 */ 
	const DISCONTINUED_PATH = '/catalog/discontinue';
	
	/** 
	 * Get instance of platform API.
	 */
	public static function getInstance();

	/**
	 * Discontinue product using Id.
	 */
	public function discontinueProduct($productId);
}