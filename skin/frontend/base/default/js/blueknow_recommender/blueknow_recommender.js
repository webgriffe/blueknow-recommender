/*!
 * Blueknow Recommender renderer
 * 
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Blueknow Recommender
 * extension to newer versions in the future. If you wish to customize it for
 * your needs please save your changes before upgrading.
 * 
 *	@category	skin
 *	@copyright	Copyright (c) 2013 Blueknow, S.L. (http://www.blueknow.com)
 *	@license	GNU General Public License
 *	@author		<a href="mailto:santi.ameller@blueknow.com">Santiago Ameller</a>
 *	@author		<a href="mailto:josep.ventura@blueknow.com">Josep M Ventura</a>
 *	@since		1.2.0
 */
/**
 * Base namespace.
 */
var Blueknow = Blueknow || {};
/**
 * Blueknow configuration map
 */
Blueknow.Configuration = {
		//Default currencies DO NOT MODIFY
		"EUR" : { symbol : "R", decimal : ",", thousand : ".", precision : 2 },
		"USD" : { symbol : "L", decimal : ".", thousand : ",", precision : 2 },
		"GBP" : { symbol : "L", decimal : ".", thousand : ",", precision : 2 }, 
		/*
		 *	If you wish to add more currencies please follow example format:
		 *
		 *	"isoCode" : {
		 *		symbol		: position of the symbol "L" for left or "R" for right.
		 *		decimal		: decimal symbol ("." or ",").  
		 *		thousand	: thousand separator ("e" empty-no separator, "." or ",").
		 *		precision	: number of decimals to display
		 *	}
		 *	Your currencies will be validated before display and JS exceptions thrown.
		 */
		//"JPY" : { symbol : "L", decimal : ".", thousand : "e", precision : 0 }, 
};
/**
 * Blueknow renderer tools auto executable anonymous function.
 */
//[16-09-2013] MAGPLUGIN-43 Change of closures to avoid window pollution.
(function() {
	/**
	 * Default currency options (EUR format)
	 */
	/* Default options:
	 * -symbol, displays currency sign (&eur;, $ or &pound;).
	 *  Always retrieved from Magento and populated in formatCurrency().
	 * -decimal sign, default ",".
	 * -thousand separator, "," default ".". If not property not set doesn't use it.
	 * -digit precision, default 2.
	 * -price format %v is the value (price) and %s is the sign. EUR example : 2&euro; (%v%s). 
	 *  You could add spaces between price and sign just %v %s.
	 */
	var defaultOptions = {
			decimal : ",",
			thousand: ".",
			precision : 2,
			format: "%v %s"
	};
	/**
	 * Regexp constants to assert users custom currencies
	 */
	var SYMBOL_REGEXP = /^L|R$/;
	var DECIMAL_REGEXP = /^\,|\.$/;
	var THOUSAND_REGEXP = /^\,|\.|e$/;
	
	/**
	 * Current options, initalized to null to avoid map empty check
	 */
	var currentOptions = null;
	
	/**
	 * Builds and renders a Blueknow widget taking a template from DOM and a collection of items.
	 * Note: It is the Blueknow Recommender success callback function.
	 * 
	 * @param items {Array} Items to be rendered.
	 */
	// Scope window
	window["renderItems"] = function(items) {
		if (items.length > 0) {
			var widget = "";
			var header = document.getElementById("blueknow-header");
			widget += header ? header.innerHTML : "";
			for ( var i = 0; i < items.length; i++) {
				var item = items[i];
				var templateHolder = document.getElementById("blueknow-template");
				if (!templateHolder) {
					break;
				}
				var template = templateHolder.innerHTML;
				template = template.replace(/\$id/g, item.id);
				template = template.replace(/\$name/g, item.name);
				template = template.replace(/\$url/g, item.url);
				template = template.replace(/source="\$image"/g, "src='"+ item.image + "'");
				//[11-03-2013] MAGPLUGIN-36 New format currency, using currency Iso from window global.
				template = template.replace(/\$price/g, _formatCurrency(item.price, window.bkCurrency));
				widget += template;
			}
			var footer = document.getElementById("blueknow-footer");
			widget += footer ? footer.innerHTML : "";
			var container = document.getElementById("blueknow-widget");
			if (container) {
				container.innerHTML = widget;
			}
			if (typeof(relocateWidget) == "function") {
				relocateWidget();
			}
		}
	};
	
	/**
	 * Blueknow Recommender error callback function. 
	 * 
	 * @param message {String} Error message from the recommender engine.
	 */
	//Scope window
	window["processError"] = function(message) {
		//emtpy
	};
	
	/**
	 * Price formatting function.Default custom formats for  EUR, USD and GBP but customers can add new ones.
	 * 
	 * @param price {Number|String} Product price.
	 * @param bkCurrency Map object containing iso : iso code and sign : currency sign.
	 * @since 1.3.0
	 */
	var _formatCurrency = function (price, bkCurrency) {
		//Check if we are using same currency
		if (currentOptions && (currentOptions.symbol == bkCurrency.symbol)) {
			return accounting.formatMoney(price, currentOptions);
		}
		//Not stored or diferent currency, try to found format
		if (Blueknow.Configuration.hasOwnProperty(bkCurrency.isoCode)) {
			//Store the result into current options
			currentOptions = _parseConfiguration(Blueknow.Configuration[bkCurrency.isoCode], bkCurrency.symbol);
			// Call accounting formatter
			return accounting.formatMoney(price, currentOptions);
		}
		//Copy of default options adding magento currency symbol
		var optionsCopy = defaultOptions;
		optionsCopy.symbol = bkCurrency.symbol;
		// Call accounting formatter
		return accounting.formatMoney(price, defaultOptions);
	};
	
	/**
	 * Load currency from our configuration object.
	 */
	var _parseConfiguration = function (config, currencySign) {
		//assert configuration, needs valid arguments
		if (!SYMBOL_REGEXP.test(config.symbol)) { throw "Wrong value in currency symbol-> " + config.symbol; }
		if (!DECIMAL_REGEXP.test(config.decimal)) { throw "Wrong value in currency decimal-> " + config.decimal; }
		if (!THOUSAND_REGEXP.test(config.thousand)) { throw "Wrong value in currency thousand-> " + config.thousand; }
		if (!Number === typeof config.precision) { throw "precision MUST be a number"; }
		
		//New map object with configuration properties
		var options = {
				symbol : currencySign,
				decimal : config.decimal,
				precision : config.precision
		};
		//If we don't want thousand separator just ignore property, default accounting don't use separators 
		if (config.thousand != "e") {
			options.thousand = config.thousand;
		}
		//Switch between formats
		options.format = (config.symbol == "R") ? "%v %s" : "%s %v";
		//Return populated options
		return options;
	};
})();

/**
 * <p>Included accounting.min.js light currency/number format library.</p>
 */
/*! 
 * accounting.min.js v0.3.2, copyright 2011 Joss Crowcroft, MIT license, http://josscrowcroft.github.com/accounting.js
 * @since 1.3.0
 */
(function(p,z){function q(a){return!!(""===a||a&&a.charCodeAt&&a.substr)}function m(a){return u?u(a):"[object Array]"===v.call(a)}function r(a){return"[object Object]"===v.call(a)}function s(a,b){var d,a=a||{},b=b||{};for(d in b)b.hasOwnProperty(d)&&null==a[d]&&(a[d]=b[d]);return a}function j(a,b,d){var c=[],e,h;if(!a)return c;if(w&&a.map===w)return a.map(b,d);for(e=0,h=a.length;e<h;e++)c[e]=b.call(d,a[e],e,a);return c}function n(a,b){a=Math.round(Math.abs(a));return isNaN(a)?b:a}function x(a){var b=c.settings.currency.format;"function"===typeof a&&(a=a());return q(a)&&a.match("%v")?{pos:a,neg:a.replace("-","").replace("%v","-%v"),zero:a}:!a||!a.pos||!a.pos.match("%v")?!q(b)?b:c.settings.currency.format={pos:b,neg:b.replace("%v","-%v"),zero:b}:a}var c={version:"0.3.2",settings:{currency:{symbol:"$",format:"%s%v",decimal:".",thousand:",",precision:2,grouping:3},number:{precision:0,grouping:3,thousand:",",decimal:"."}}},w=Array.prototype.map,u=Array.isArray,v=Object.prototype.toString,o=c.unformat=c.parse=function(a,b){if(m(a))return j(a,function(a){return o(a,b)});a=a||0;if("number"===typeof a)return a;var b=b||".",c=RegExp("[^0-9-"+b+"]",["g"]),c=parseFloat((""+a).replace(/\((.*)\)/,"-$1").replace(c,"").replace(b,"."));return!isNaN(c)?c:0},y=c.toFixed=function(a,b){var b=n(b,c.settings.number.precision),d=Math.pow(10,b);return(Math.round(c.unformat(a)*d)/d).toFixed(b)},t=c.formatNumber=function(a,b,d,i){if(m(a))return j(a,function(a){return t(a,b,d,i)});var a=o(a),e=s(r(b)?b:{precision:b,thousand:d,decimal:i},c.settings.number),h=n(e.precision),f=0>a?"-":"",g=parseInt(y(Math.abs(a||0),h),10)+"",l=3<g.length?g.length%3:0;return f+(l?g.substr(0,l)+e.thousand:"")+g.substr(l).replace(/(\d{3})(?=\d)/g,"$1"+e.thousand)+(h?e.decimal+y(Math.abs(a),h).split(".")[1]:"")},A=c.formatMoney=function(a,b,d,i,e,h){if(m(a))return j(a,function(a){return A(a,b,d,i,e,h)});var a=o(a),f=s(r(b)?b:{symbol:b,precision:d,thousand:i,decimal:e,format:h},c.settings.currency),g=x(f.format);return(0<a?g.pos:0>a?g.neg:g.zero).replace("%s",f.symbol).replace("%v",t(Math.abs(a),n(f.precision),f.thousand,f.decimal))};c.formatColumn=function(a,b,d,i,e,h){if(!a)return[];var f=s(r(b)?b:{symbol:b,precision:d,thousand:i,decimal:e,format:h},c.settings.currency),g=x(f.format),l=g.pos.indexOf("%s")<g.pos.indexOf("%v")?!0:!1,k=0,a=j(a,function(a){if(m(a))return c.formatColumn(a,f);a=o(a);a=(0<a?g.pos:0>a?g.neg:g.zero).replace("%s",f.symbol).replace("%v",t(Math.abs(a),n(f.precision),f.thousand,f.decimal));if(a.length>k)k=a.length;return a});return j(a,function(a){return q(a)&&a.length<k?l?a.replace(f.symbol,f.symbol+Array(k-a.length+1).join(" ")):Array(k-a.length+1).join(" ")+a:a})};if("undefined"!==typeof exports){if("undefined"!==typeof module&&module.exports)exports=module.exports=c;exports.accounting=c}else"function"===typeof define&&define.amd?define([],function(){return c}):(c.noConflict=function(a){return function(){p.accounting=a;c.noConflict=z;return c}}(p.accounting),p.accounting=c)})(this);