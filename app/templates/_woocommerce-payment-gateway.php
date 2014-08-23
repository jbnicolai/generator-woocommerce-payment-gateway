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
		/**
	     * Constructor for the gateway.
	     */
	    public function __construct() {
			$this->id                 = '<%= _.camelize(gatewayName) %>';
			$this->icon               = apply_filters('woocommerce_<%= _.camelize(gatewayName) %>_icon', '');
			$this->has_fields         = false;
			$this->method_title       = __( '<%= gatewayName %>', '<%= _.slugify(gatewayName) %>' );
			$this->method_description = __( 'This is the payment gateway description', '<%= _.slugify(gatewayName) %>' );

			// Load the settings.
			$this->init_form_fields();

	        // Define user set variables
			$this->title         = $this->get_option( 'title' );
			$this->description   = $this->get_option( 'description' );
			$this->example_field = $this->get_option( 'example_field' );

			// Actions
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	    	add_action( 'woocommerce_thankyou_<%= _.camelize(gatewayName) %>', array( $this, 'thankyou_page' ) );
	    }

		/**
	     * Create form fields for the payment gateway
	     *
	     * @return void
	     */
	    public function init_form_fields() {
	        $this->form_fields = array(
	            'enabled' => array(
	                'title' => __( 'Enable/Disable', '<%= _.slugify(gatewayName) %>' ),
	                'type' => 'checkbox',
	                'label' => __( 'Enable <%= gatewayName %>', '<%= _.slugify(gatewayName) %>' ),
	                'default' => 'no'
	            ),
	            'title' => array(
	                'title' => __( 'Title', '<%= _.slugify(gatewayName) %>' ),
	                'type' => 'text',
	                'description' => __( 'This controls the title which the user sees during checkout', '<%= _.slugify(gatewayName) %>' ),
	                'default' => __( '<%= gatewayName %>', '<%= _.slugify(gatewayName) %>' ),
	                'desc_tip'      => true,
	            ),
	            'description' => array(
	                'title' => __( 'Customer Message', '<%= _.slugify(gatewayName) %>' ),
	                'type' => 'textarea',
	                'default' => __( 'Description of the payment gateway', '<%= _.slugify(gatewayName) %>' )
	            ),
				'example_field' => array(
					'title' => __( 'Example field', '<%= _.slugify(gatewayName) %>' ),
					'type' => 'text',
					'default' => __( 'Example field description', '<%= _.slugify(gatewayName) %>' )
				),
	        );
	    }

	    /**
	     * Process the order payment status
	     *
	     * @param int $order_id
	     * @return array
	     */
	    public function process_payment( $order_id ) {
	        $order = new WC_Order( $order_id );

	        // Mark as on-hold (we're awaiting the cheque)
	        $order->update_status( 'on-hold', __( 'Awaiting payment', '<%= _.slugify(gatewayName) %>' ) );

	        // Reduce stock levels
	        $order->reduce_order_stock();

	        // Remove cart
	        WC()->cart->empty_cart();

	        // Return thankyou redirect
	        return array(
	            'result'    => 'success',
	            'redirect'  => $this->get_return_url( $order )
	        );
	    }

	    /**
	     * Output for the order received page.
	     *
	     * @return void
	     */
	    public function thankyou() {
	        if ( $description = $this->get_description() )
	            echo wpautop( wptexturize( wp_kses_post( $description ) ) );

	        echo '<h2>' . __( 'Our Details', '<%= _.slugify(gatewayName) %>' ) . '</h2>';

	        echo '<ul class="order_details <%= _.camelize(gatewayName) %>_details">';

	        $fields = apply_filters( 'woocommerce_<%= _.camelize(gatewayName) %>_fields', array(
	            'example_field'  => __( 'Example field', '<%= _.slugify(gatewayName) %>' )
	        ) );

	        foreach ( $fields as $key => $value ) {
	            if ( ! empty( $this->$key ) ) {
	                echo '<li class="' . esc_attr( $key ) . '">' . esc_attr( $value ) . ': <strong>' . wptexturize( $this->$key ) . '</strong></li>';
	            }
	        }

	        echo '</ul>';
	    }
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
