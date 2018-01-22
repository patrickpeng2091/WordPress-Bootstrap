<?php
/**
 * Plugin Name: Product Retailers WooCommerce 
 * Plugin URI: http://emediaexperts.com/demo/product-retailers
 * Description: Allow customers to purchase products from external retailers
 * Author: eMediaExperts Team
 * Author URI: http://emediaexperts.com
 * Version: 2.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Required functions
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

// WC active check
if ( ! is_woocommerce_active() ) {
	return;
}

// Required library class
if ( ! class_exists( 'EM_WC_Framework_Bootstrap' ) ) {
	require_once( 'lib/woocommerce/class-sv-wc-framework-bootstrap.php' );
}

EM_WC_Framework_Bootstrap::instance()->register_plugin( '2.0.2', __( 'WooCommerce Product Retailers', 'woocommerce-product-retailers' ), __FILE__, 'init_woocommerce_product_retailers' );

function init_woocommerce_product_retailers() {

class WC_Product_Retailers extends EM_WC_Plugin {


	/** plugin version number */
	const VERSION = '1.0';

	/** string the plugin id */
	const PLUGIN_ID = 'product_retailers';

	/** plugin text domain */
	const TEXT_DOMAIN = 'woocommerce-product-retailers';

	/** @var WC_Product_Retailers_List the admin retailers list screen */
	private $admin_retailers_list;

	/** @var WC_Product_Retailers_Edit the admin retailers edit screen */
	private $admin_retailers_edit;

	/** @var boolean set to try after the retailer dropdown is rendered on the product page */
	private $retailer_dropdown_rendered = false;

	/** @var \WC_Product_Retailers_Admin instance */
	public $admin;


	/**
	 * Initializes the plugin
	 *
	 * @since 1.0
	 * @see EM_WC_Plugin::__construct()
	*/
	public function __construct() {

		parent::__construct(
			self::PLUGIN_ID,
			self::VERSION,
			self::TEXT_DOMAIN
		);

		// include required files
		$this->includes();

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'init', array( $this, 'include_template_functions' ), 25 );

		// render frontend embedded styles
		add_action( 'wp_print_styles',                array( $this, 'render_embedded_styles' ), 1 );

		// control the loop add to cart buttons for the product retailer products
		add_filter( 'woocommerce_is_purchasable',     array( $this, 'product_is_purchasable' ), 10, 2 );

		add_action( 'woocommerce_init',               array( $this, 'woocommerce_init' ) );
		add_filter( 'woocommerce_product_is_visible', array( $this, 'product_variation_is_visible' ), 1, 2 );

		// add the product retailers dropdown on the single product page (next to the 'add to cart' button if available)
		//add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'add_retailer_dropdown' ) );
		add_action( 'woocommerce_single_product_summary',   array( $this, 'add_retailer_dropdown' ), 35 );
		
	}


	/**
	 * Initialize translation and taxonomy
	 *
	 * @since 1.0
	 */
	public function init() {

		WC_Product_Retailers_Taxonomy::initialize();
	}


	/**
	 * Setup after woocommerce is initialized
	 *
	 * @since 1.2
	 */
	public function woocommerce_init() {

		if ( EM_WC_Plugin_Compatibility::is_wc_version_gte_2_1() ) {
			add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'add_to_cart_text' ), 10, 2 );
		} else {
			add_filter( 'not_purchasable_text',           array( $this, 'add_to_cart_text' ) );
			add_filter( 'out_of_stock_add_to_cart_text',  array( $this, 'add_to_cart_text' ) );
		}
	}


	/**
	 * Include required files
	 *
	 * @since 1.0
	 * @see EM_WC_Plugin::includes()
	 */
	private function includes() {

		require_once( 'includes/class-wc-product-retailers-product.php' );
		require_once( 'includes/class-wc-product-retailers-taxonomy.php' );
		require_once( 'includes/class-wc-retailer.php' );

		if ( is_admin() ) {
			$this->admin_includes();
		}

	}


	/**
	 * Include required admin files
	 *
	 * @since 1.0
	 */
	private function admin_includes() {

		require_once( 'includes/admin/class-wc-product-retailers-admin.php' );
		$this->admin = new WC_Product_Retailers_Admin();

		require_once( 'includes/admin/class-wc-product-retailers-list.php' );
		$this->admin_retailers_list = new WC_Product_Retailers_List();

		require_once( 'includes/admin/class-wc-product-retailers-edit.php' );
		$this->admin_retailers_edit = new WC_Product_Retailers_Edit();
	}


	/**
	 * Function used to Init WooCommerce Product Retailers Template Functions
	 * This makes them pluggable by plugins and themes.
	 */
	public function include_template_functions() {
		require_once( 'includes/wc-product-retailers-template-functions.php' );
	}


	/**
	 * Handle localization, WPML compatible
	 *
	 * @since 1.0
	 * @see EM_WC_Plugin::load_translation()
	 */
	public function load_translation() {

		load_plugin_textdomain( 'woocommerce-product-retailers', false, dirname( plugin_basename( $this->get_file() ) ) . '/i18n/languages' );
	}


	/** Admin methods ******************************************************/

	public function get_settings_url( $_ = '' ) {

		if ( EM_WC_Plugin_Compatibility::is_wc_version_gte_2_1() ) {
			return admin_url( 'admin.php?page=wc-settings&tab=products' );
		} else {
			return admin_url( 'admin.php?page=woocommerce_settings&tab=catalog' );
		}

	}

	/** Frontend methods ******************************************************/

	public function render_embedded_styles() {
		global $post;

		if ( is_product() ) {
			$product = get_product( $post->ID );

			if ( WC_Product_Retailers_Product::has_retailers( $product ) ) {
				echo '<style type="text/css">.wc-product-retailers-wrap { padding:1em 0;clear:both; } .wc-product-retailers-wrap ul.wc-product-retailers li { margin-bottom:5px; }.wc-product-retailers > li {
    float: left; list-style: outside none none; margin-bottom: 5px; width: 100%; } .wc-product-retailers { margin: 0 !important; padding: 0; }</style>';
			}
		}
	}

	public function product_variation_is_visible( $visible, $product_id ) {

		$product = get_product( $product_id );

		if ( $product->is_type( 'variable' ) &&  WC_Product_Retailers_Product::is_retailer_only_purchase( $product ) ) {
			$visible = true;
		}

		return $visible;
	}


	/**
	 * Marks "retailer only" products as not purchasable
	 *
	 * @since 1.0
	 * @param boolean $purchasable whether the product is purchasable
	 * @param WC_Product $product the product
	 * @return boolean true if $product is purchasable, false otherwise
	 */
	public function product_is_purchasable( $purchasable, $product ) {

		if ( WC_Product_Retailers_Product::is_retailer_only_purchase( $product ) ) {
			$purchasable = false;
		}

		return $purchasable;
	}


	/**
	 * Modify the 'add to cart' text for simple product retailer products which
	 * are sold only through retailers to display the catalog button text.
	 * This is because the customer must select a retailer to purchase
	 *
	 * @since 1.0
	 * @param string $label the 'add to cart' label
	 * @param null|WC_Product $product WC product object in 2.1+, null in 2.0
	 * @return string the 'add to cart' label
	 */
	public function add_to_cart_text( $label, $product = null ) {

		if ( ! $product ) {
			// pre WC 2.1 support
			global $product;
		}

		if ( $product->is_type( 'simple' ) && WC_Product_Retailers_Product::is_retailer_only_purchase( $product ) && WC_Product_Retailers_Product::has_retailers( $product ) ) {
			$label = __( WC_Product_Retailers_Product::get_catalog_button_text( $product ), self::TEXT_DOMAIN );
		}

		return $label;
	}


	/**
	 * Display the product retailers drop down box
	 *
	 * @since 1.0
	 */
	public function add_retailer_dropdown() {
		global $product;
        
		// get any product retailers
		$retailers = WC_Product_Retailers_Product::get_product_retailers( $product );
  
       
		// only add dropdown if retailers have been assigned and it hasn't already been displayed
		if ( $this->retailer_dropdown_rendered || empty( $retailers ) ) {
			return;
		}

		$this->retailer_dropdown_rendered = true;

		woocommerce_single_product_product_retailers( $product, $retailers );
	}
	
	public function add_retailer_dropact() {
		echo '<div class="woocommerce-error"><p>Error: Invalid / Unverified Purchase Key.</p></div>';
	}

	/** Helper methods **/

	/**
	 * Returns the plugin name, localized
	 *
	 * @since 1.2
	 * @see EM_WC_Plugin::get_plugin_name()
	 * @return string the plugin name
	 */
	public function get_plugin_name() {
		return __( 'WooCommerce Product Retailers', self::TEXT_DOMAIN );
	}


	/**
	 * Returns __FILE__
	 *
	 * @since 1.2
	 * @see EM_WC_Plugin::get_file
	 * @return string the full path and filename of the plugin file
	 */
	protected function get_file() {
		return __FILE__;
	}


	/**
	 * Gets the global default Product Button text default
	 *
	 * @since 1.0
	 * @return string the default product button text
	 */
	public function get_product_button_text() {
		return get_option( 'product_retailers_product_button_text' );
	}


	/**
	 * Gets the global default Catalog Button text default
	 *
	 * @since 1.0
	 * @return string the default product button text
	 */
	public function get_catalog_button_text() {
		return get_option( 'product_retailers_catalog_button_text' );
	}


	/** Lifecycle methods ******************************************************/

	/**
	 * Run every time.  Used since the activation hook is not executed when updating a plugin
	 *
	 * @since 1.0
	 * @see EM_WC_Plugin::install
	 */
	protected function install() {

		$this->admin_includes();

		// install default settings
		foreach ( WC_Settings_Tab::get_settings() as $setting ) {

			if ( isset( $setting['default'] ) ) {
				update_option( $setting['id'], $setting['default'] );
			}
		}

	}


} // end \WC_Product_Retailers class


/**
 * The WC_Product_Retailers global object
 * @name $product_retailers
 * @global WC_Product_Retailers $GLOBALS['product_retailers']
 */
$GLOBALS['product_retailers'] = new WC_Product_Retailers();

// Unfortunate temporary function hack to compensate for a bug with the plugin
// framework, until we get around to updating all plugins
if ( ! function_exists( 'wc_trim_zeroes' ) ) {
	function wc_trim_zeroes( $price ) {
		return wc_trim_zeros( $price );
	}
}

} // init_woocommerce_product_retailers()


// Remove add to cart button 

$disable_cart = get_option( 'product_retailers_disable_cart');
if ($disable_cart == "yes") {
function remove_loop_button(){
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
}
add_action('init','remove_loop_button');
}
	

function product_retailer_shortcode($atts, $content = null) {
	$default = array(
	    'id' 	=> '',
	);
	extract(shortcode_atts($default, $atts));
    
	$args =  array('post_type'=>'product','post__in' => array($id));

    $the_query = new WP_Query( $args );

	
	// The Loop
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		
	         global $product;
			
			// get any product retailers
			$retailers = WC_Product_Retailers_Product::get_product_retailers( $product );
	      	woocommerce_single_product_product_retailers( $product, $retailers );
	}
	        wp_reset_postdata();
	}

  add_shortcode( 'Product_Retailer', 'product_retailer_shortcode' );

// Add Review Templete 
	
	function my_plugin_comment_template( $comment_template ) {
     global $post;
    /* if ( !( is_singular() && ( have_comments() || 'open' == $post->comment_status ) ) ) {
        return;
     }*/
     if($post->post_type == 'product_retailer'){ // assuming there is a post type called business
	  return dirname(__FILE__) . '/single-product-reviews.php';
	  
	 }
	 }


add_filter( "comments_template", "my_plugin_comment_template",0 );


 //wp_enqueue_style('woocommerced', plugins_url().'/woocommerce/assets/css/woocommerce.css');


   function get_retailer_rating($postID) {
			
		
			global $wpdb;

			$average_rating = '';
		    $count          = get_comments_number($postID);

			if ( $count > 0 ) {

				$ratings = $wpdb->get_var( $wpdb->prepare("
					SELECT SUM(meta_value) FROM $wpdb->commentmeta
					LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
					WHERE meta_key = 'rating'
					AND comment_post_ID = %d
					AND comment_approved = '1'
					AND meta_value > 0
				", $postID ) );

				$average_rating = number_format( $ratings / $count, 2 );
			}

			set_transient( 'wc_average_rating_' . $postID, $average_rating, YEAR_IN_SECONDS );
		
		return  $average_rating;
	}

	if (get_option('key') == "valid") {
				
		$api_url = 'http://emediaexperts.com/updates/';
	    $plugin_slug = basename(dirname(__FILE__));
		
		
		
		// Take over the update check
		add_filter('pre_set_site_transient_update_plugins', 'check_for_plugin_update');
		
		if(!function_exists('check_for_plugin_update')) {
			
			function check_for_plugin_update($checked_data) {
			global $api_url, $plugin_slug, $wp_version;
			
			//Comment out these two lines during testing.
			if (empty($checked_data->checked))
				return $checked_data;
			
			$args = array(
				'slug' => $plugin_slug,
				'version' => $checked_data->checked[$plugin_slug .'/'. $plugin_slug .'.php'],
			);
			$request_string = array(
					'body' => array(
						'action' => 'basic_check', 
						'request' => serialize($args),
						'api-key' => md5(get_bloginfo('url'))
					),
					'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
				);
			
			// Start checking for an update
			$raw_response = wp_remote_post($api_url, $request_string);
			
			if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
				$response = unserialize($raw_response['body']);
			
			if (is_object($response) && !empty($response)) // Feed the update data into WP updater
				$checked_data->response[$plugin_slug .'/'. $plugin_slug .'.php'] = $response;
			
			return $checked_data;
		}
		
		}
		// Take over the Plugin info screen
		add_filter('plugins_api', 'plugin_api_call', 10, 3);
		
		
		if(!function_exists('plugin_api_call')) {
		function plugin_api_call($def, $action, $args) {
			global $plugin_slug, $api_url;
			
			if ($args->slug != $plugin_slug)
				return false;
			
			// Get the current version
			$plugin_info = get_site_transient('update_plugins');
			$current_version = $plugin_info->checked[$plugin_slug .'/'. $plugin_slug .'.php'];
			$args->version = $current_version;
			
			$request_string = array(
					'body' => array(
						'action' => $action, 
						'request' => serialize($args),
						'api-key' => md5(get_bloginfo('url'))
					),
					'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
				);
			
			$request = wp_remote_post($api_url, $request_string);
			
			if (is_wp_error($request)) {
				$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
			} else {
				$res = unserialize($request['body']);
				
				if ($res === false)
					$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
			}
			
			return $res;
		}
    }
}



function register_pr_scripts() {
			
		wp_enqueue_script(
			'stacktable',
			plugins_url('assets/js/stacktable.js', __FILE__),
			array( 'jquery' )
		);
		
		
		wp_enqueue_script(
			'fancybox.pack',
			plugins_url('assets/js/jquery.fancybox.pack.js', __FILE__),
			array( 'jquery' )
		);
		
		wp_register_style('fancybox-style', plugins_url('assets/fancybox/jquery.fancybox.css', __FILE__));
		
		wp_enqueue_style( 'fancybox-style' );

	}

	
add_action( 'wp_enqueue_scripts', 'register_pr_scripts' );

