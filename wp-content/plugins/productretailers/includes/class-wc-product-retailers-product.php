<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Retailers Product class
 *
 * @since 1.0
 */
class WC_Product_Retailers_Product {


	/**
	 * Returns true if the given product has retailers available to display
	 *
	 * @since 1.0
	 * @param int|object $product \WC_Product object or post ID
	 * @return boolean true if the given product has retailers available to display
	 */
	public static function has_retailers( $product ) {

		if ( ! is_object( $product ) ) {
			$product = get_product( $product );
		}

		// NOTE: this will return true even if all product_retailers have been trashed or permanently deleted
		return ( ! empty( $product->product_retailers ) );
	}


	/**
	 * Returns true if the given product is available only for purchase from
	 * a retailer
	 *
	 * @since 1.0
	 * @param int|object $product \WC_Product object or post ID
	 * @return boolean true if the given product is available for purchase only
	 *         from a retailer
	 */
	public static function is_retailer_only_purchase( $product ) {

		if ( ! is_object( $product ) ) {
			$product = get_product( $product );
		}

		return 'yes' == $product->product_retailers_retailer_only_purchase;
	}


	/**
	 * Returns true if buttons, rather than a dropdown, should be used if this
	 * product has multiple retailers.
	 *
	 * @since 1.3
	 * @param int|object $product WC_Product object or post ID
	 * @return boolean true if the given product should use buttons only for its
	 *         retailers on the product page
	 */
	public static function use_buttons( $product ) {

		if ( ! is_object( $product ) ) {
			$product = get_product( $product );
		}

		return 'yes' == $product->product_retailers_use_buttons;
	}


	/**
	 * Returns $price formatted with currency symbol and decimals, as
	 * configured within WooCommerce settings
	 *
	 * Annoyingly, WC doesn't seem to offer a function to format a price string
	 * without HTML tags, so this method is adapted from the core wc_price()
	 * function.
	 *
	 * @since 1.3
	 * @see wc_price()
	 * @param string $price the price
	 * @return string price formatted
	 */
	public static function wc_price( $price ) {

		if ( 0 == $price ) {
			return __( 'Free!', WC_Product_Retailers::TEXT_DOMAIN );
		}

		$return          = '';
		$num_decimals    = absint( get_option( 'woocommerce_price_num_decimals' ) );
		$currency_pos    = get_option( 'woocommerce_currency_pos' );
		$currency_symbol = html_entity_decode( get_woocommerce_currency_symbol() );
		$decimal_sep     = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ), ENT_QUOTES );
		$thousands_sep   = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ), ENT_QUOTES );

		$price           = apply_filters( 'raw_woocommerce_price', floatval( $price ) );
		$price           = apply_filters( 'formatted_woocommerce_price', number_format( $price, $num_decimals, $decimal_sep, $thousands_sep ), $price, $num_decimals, $decimal_sep, $thousands_sep );

		if ( apply_filters( 'woocommerce_price_trim_zeros', true ) && $num_decimals > 0 ) {
			$price = EM_WC_Plugin_Compatibility::wc_trim_zeroes( $price );
		}

		$return = sprintf( str_replace( '&nbsp;', ' ', get_woocommerce_price_format() ), $currency_symbol, $price );

		return $return;
	}


	/**
	 * Returns the product button text for the given product, this is shown on
	 * the product page dropdown/button linking to the retailer
	 *
	 * @since 1.0
	 * @param int|object $product \WC_Product object or post ID
	 * @return string the product button text
	 */
	public static function get_product_button_text( $product ) {
		global $product_retailers;

		if ( ! is_object( $product ) ) {
			$product = get_product( $product );
		}

		$button_text = $product->product_retailers_product_button_text;

		if ( ! $button_text ) {
			$button_text = $product_retailers->get_product_button_text();
		}

		return $button_text;
	}


	/**
	 * Returns the catalog button text for the given product, this is shown for
	 * the catalog page 'add to cart' button text if this is a simple product
	 * that is only sold through retailers
	 *
	 * @since 1.0
	 * @param int|object $product \WC_Product object or post ID
	 * @return string the catalog button text
	 */
	public static function get_catalog_button_text( $product ) {
		global $product_retailers;

		if ( ! is_object( $product ) ) {
			$product = get_product( $product );
		}

		$button_text = $product->product_retailers_catalog_button_text;

		if ( ! $button_text ) {
			$button_text = $product_retailers->get_catalog_button_text();
		}

		return $button_text;
	}


	/**
	 * Returns an array of retailers for the given product.  These retailers are
	 * "available" meaning they are not in the trash and have a URL/name
	 *
	 * @since 1.0
	 * @param int|object $product \WC_Product object or post ID
	 * @return array of WC_Retailer objects
	 */
	public static function get_product_retailers( $product ) {

		if ( ! is_object( $product ) ) {
			$product = get_product( $product );
		}

		$retailers = array();

		if ( is_array( $product->product_retailers ) ) {

			foreach ( $product->product_retailers as $retailer_data ) {

				try {

					// get retailer object
					$retailer = new WC_Retailer( $retailer_data['id'] );

					// if a URL was set at the product level, use it
					if ( ! empty( $retailer_data['product_url'] ) ) {
						$retailer->set_url( $retailer_data['product_url'] );
					}

					// if a price was specified, set it (isset so 0.00 prices can be used)
					if ( isset( $retailer_data['product_price'] ) ) {
						$retailer->set_price( $retailer_data['product_price'] );
					}
					
					if ( isset( $retailer_data['product_logo'] ) ) {
						$retailer->set_logo( $retailer_data['product_logo'] );
					}
					
					if ( isset( $retailer_data['product_location'] ) ) {
						$retailer->set_location( $retailer_data['product_location'] );
					}

					if ( $retailer->is_available() ) {
						$retailers[] = $retailer;
					}

				} catch ( Exception $e ) { /* retailer does not exist */ }
			}
		}

		return apply_filters( 'woocommerce_get_product_retailers', $retailers, $product );
	}


} // end \WC_Product_Retailers_Product class
   if (get_option('key') == "valid") {}else{
   if ( is_super_admin() ) {
				 
	    add_action('admin_notices', 'pr_admin_notice',10);	
		add_action('admin_init', 'pr_nag_ignore');
		
	   
		function pr_admin_notice() {
			     //update_user_option( get_current_user_id(), 'show_admin_bar_front', 'true' );
				if ( get_user_option( 'show_admin_bar_front', get_current_user_id() ) == 'true' ) {
				   reg_from();
				}
				 reg_from();
			}
		 
		function pr_nag_ignore() {
				if ( isset($_GET['example_nag_ignore']) && '0' == $_GET['example_nag_ignore'] ) {
					 update_user_option( get_current_user_id(), 'show_admin_bar_front', 'false' );
			      }
		}
		
}     
   }
   
   