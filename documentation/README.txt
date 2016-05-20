/**
 * Blueknow Recommender for Magento.
 * 
 * @copyright	Copyright (c) 2009-2011 Blueknow, S.L. (http://www.blueknow.com)
 * @license		GNU General Public License
 * @author		<a href="mailto:santi.ameller@blueknow.com">Santiago Ameller</a>
 * 
 */

About the module
================

	* Name:          Blueknow Recommender for Magento eCommerce platform (v1.3.x or higher).
	* Description:   Personalized product recommendations for your eCommerce: cross-sell and up-sell.
	* Version:       1.2.0.

	NOTE: this module has been successfully tested on Magento 1.3.x or higher. If your eCommerce runs under an older version, please contact with us at support@blueknow.com.
	
Important notes
===============

	* Once add-on is installed, your clients won't get personalized recommendations until at least 24 hours after.
	* If some language you need is not supported, create it! Your new language file should be under %MAGENTO_HOME%/app/locale/<your_locale>/Blueknow_Recommender.csv.
	  Take other language file as example to define your new one.
	* If you modified an older version of this add-on, be careful not to loose your changes (widget templates, and so on).
	* If you are using cache in your eCommerce, some functionalities added by this add-on may not work. Refresh it from Magento's Admin Panel.
	* Errors? Problems? Bugs? Contributions? Let us know at support@blueknow.com.

How to install
==============

	To install Blueknow Recommender go to Magento Connect Manager (System > Magento Connect > Magento Connect Manager)
	and put the extension key according to your current Magento version
	
		> Magento 1.4.x (or lower): magento-community/BlueknowRecommender (Magento Connect 1.0).
		> Magento 1.5.x (or higher): http://connect20.magentocommerce.com/community/BlueknowRecommender (Magento Connect 2.0).
		
	After installing the extension, you will need to configure it from your Magento's Admin Panel: System > Configuration > Services > Blueknow Recommender.

	Optionally, you can review the default recommendations widget template provided for changing it according your needs:
		
		1. Template for product detail cross-sell recommendations widget .... %MAGENTO_HOME%/app/design/frontend/default/default/template/blueknow/product/recommender_widget.phtml.
		2. Template for shopping cart up-sell recommendations widget ........ %MAGENTO_HOME%/app/design/frontend/default/default/template/blueknow/cart/recommender_widget.phtml.
		3. Template for home page personalized recommendations widget ....... %MAGENTO_HOME%/app/design/frontend/default/default/template/blueknow/home/recommender_widget.phtml.
		4. Blueknow widgets styles sheet .................................... %MAGENTO_HOME%/skin/frontend/base/default/css/blueknow_widget.css.
	
	Once configuration is complete, Blueknow Tracker will start tracking behavioral events (visits, purchases...) and 24 hours later your clients 
	will be receiving personalized recommendations.
	
	If you need more information about this module, how to obtain a BK number for your eCommerce for free, or about the service in general, please visit our 
	public site at http://www.blueknow.com or contact with our Customer support department (support@blueknow.com).

Change log
==========

Version 1.2.0
-------------

	Stable version including new functionalities and some bugs fixed.
	
	+ [MAGPLUGIN-12] - Add item-to-user recommendations in the homepage
	+ [MAGPLUGIN-13] - Retrieve the correct category path when a product is accessed from the homepage.
	+ [MAGPLUGIN-14] - Track event purchased according to the shipping method selected, single or multi.
	+ [MAGPLUGIN-15] - Set cross-sell template without manually add into Magento Core cart template.
	+ [MAGPLUGIN-16] - Externalize JavaScript/CSS code as static extension resources.
	+ [MAGPLUGIN-17] - Track event discontinued from product-not-found page.
	+ [MAGPLUGIN-18] - Add a configurable property for product thumbnail size in extension configuration form (won't fix).
	+ [MAGPLUGIN-19] - Discontinue products using the Blueknow Platform API.
	+ [MAGPLUGIN-20] - Format product price in widget display.
	+ [MAGPLUGIN-21] - Item-to-basket recommendations and configurable products: parent and child products are included.
	+ [MAGPLUGIN-22] - Apply new clicked event strategy.

Version 1.1.1
-------------

	Stable version packaged due to Magento Connect limitation. It includes the same functionalities and fixed than previous one.

Version 1.1.0
-------------

	Beta version. User version 1.1.1 instead.

	+ [MAGPLUGIN-5]  - Specify the real dimensions of the Blueknow's logo on the widget template.
	+ [MAGPLUGIN-6]  - Work with product thumbnails instead of large images.
	+ [MAGPLUGIN-7]  - Get user e-mail instead of user identifier in user logged event.
	+ [MAGPLUGIN-8]  - Get and track an empty product description instead of its large version.
	+ [MAGPLUGIN-9]  - Use "script" HTML tag in widget template holders instead of "div".
	+ [MAGPLUGIN-11] - Remove "cat_Lx_name" properties in event visited.

Version 1.0.1
-------------

	Bug fixing version.

	+ [MAGPLUGIN-1] - Track cached images to improve loading time of the widget.
	+ [MAGPLUGIN-2] - Grouped, configurable and bundled products are not rendered when price is retrieved.
	+ [MAGPLUGIN-3] - Unexpected page loading whenever widget is rendered.
	+ [MAGPLUGIN-4] - Quotes in product name causes a JavaScript error.

Version 1.0.0
-------------

	Initial version.

	+ Tracking of users' products views.
	+ Tracking of discontinued products.
	+ Tracking of users' logins.
	+ Tracking of users' purchases.
	+ Tracking of discontinued products after a purchase.
	+ Cross-sell recommendations in product detail page.
	+ Up-sell recommendations in shopping cart page.