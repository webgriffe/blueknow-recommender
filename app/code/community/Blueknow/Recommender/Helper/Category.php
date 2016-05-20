<?php
/**
 * Product category helper.
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
class Blueknow_Recommender_Helper_Category extends Mage_Core_Helper_Abstract {
	
	/**
	 * Return current category path or get it from current category and creating array of categories paths.
	 * @return array Items defined as ('id', 'name') pair.
	 * @deprecated 1.2.0.GA (see issue MAGPLUGIN-13); use #getPath() instead.
	 */
	public function getCategoryPath() {
		$path = array();
		if ($category = Mage::registry('current_category')) {
			$pathInStore = $category->getPathInStore();
			$pathIds = array_reverse(explode(',', $pathInStore));
			$categories = $category->getParentCategories();
			//add category path
			foreach ($pathIds as $categoryId) {
				//this condition can be improved!(Not used deprecated method)
				if (isset($categories[$categoryId]) && $categories[$categoryId]->getName()) {
					$path['category' . $categoryId] = array(
						'id' => $categoryId);
						//[2012-11-16] Issue MAGPLUGIN-11: Remove "cat_Lx_name" properties in event visited
						//'name' => addslashes($categories[$categoryId]->getName())
					//);
				}
			}
		}
		return $path;
	}
	
	/**
	 * Return current category path using catalog. It works independently of page used.
	 * 
	 * @return associative array with ids.
	 * @since 1.2.0.GA.
	 */
	//[2012-11-13] Issue MAGPLUGIN-13. Track correctly categories from Home and Product page.
	public function getPath($productId) {
		$path = array();
		//Get lowest level category
		if ($category = $this->getLastChild($productId)) {
			//Retrieve ids from path with correct category id order.
			$pathInStore = $category->getPathInStore();
			$pathIds = array_reverse(explode(',', $pathInStore));
			//Get parents
			$parents = $category->getParentCategories();
			//add ids to an associative array
			foreach ($pathIds as $catId) {
				if (isset($parents[$catId])) {
					$path['category' . $catId] = array('id' => $catId);
				}	
			}
		}
		return $path;
	}
	
	/**
	 * Return lowest level Category
	 * @param number $productId
	 * @return Category model object
	 * @since 1.2.0.GA.
	 */
	//[23-01-2013] MAGPLUGIN-13. Retrieve lowest level category following 2 different strategies.
	public function getLastChild($productId) {
		//Retrieve from registry (Product page case)
		$category = Mage::registry('current_category');
		//Go to model to find it.
		if(!isset($category)) {
			//Retrieve product from catalog with all properties (avoid Mage::registry in Home page or others)
			$prod = Mage::getModel('catalog/product')->load($productId);
			//Get the last category id from category Ids
			$categoryIds = $prod->getCategoryIds();
			$last = end($categoryIds);
			//Retrieve current category from catalog
			$category = Mage::getModel('catalog/category')->load($last);
		}
		return $category;
	}
}
