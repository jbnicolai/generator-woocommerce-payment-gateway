<?php
/*
Plugin Name: <%= gatewayName %>
Plugin URI: <%= authorURL %><%= _.slugify( gatewayName ) %>/
Description: <%= gatewayDesc %>
Version: 0.0.0
Author: <%= authorName %> <<%= authorEmail %>>
Author URI: <%= authorURL %>

	Copyright: © <%= currentYear %> <%= authorName %> <<%= authorEmail %>>.
	License: GNU General Public License v2
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

add_action( 'plugins_loaded', '<%= _.camelize(gatewayName) %>_init', 0 );
function <%= _.camelize(gatewayName) %>_init() {

	if ( ! class_exists( '<%= _.classify(gatewayName) %>' ) ) return;

	/**
 	 * Localisation
	 */
	load_plugin_textdomain( '<%= _.slugify(gatewayName) %>', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	/**
 	 * Gateway class
 	 */
	class <%= _.classify(gatewayName) %> extends WC_Payment_Gateway {

		// Go wild in here
	}

	/**
 	* Add the Gateway to WooCommerce
 	**/
	add_filter( 'woocommerce_payment_gateways', 'woocommerce_add_gateway_<%= _.camelize(gatewayName) %>' );
	function woocommerce_add_gateway_<%= _.camelize(gatewayName) %>($methods) {
		$methods[] = '<%= _.classify(gatewayName) %>';
		return $methods;
	}
}
