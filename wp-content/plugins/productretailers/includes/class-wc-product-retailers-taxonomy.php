<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Product Retailers Taxonomy
 *
 * @since 1.0
 */
class WC_Product_Retailers_Taxonomy {


	/**
	 * Initialize and register the Product Retailers taxonomies
	 *
	 * @since 1.0
	 */
	public static function initialize() {

		self::init_user_roles();

		self::init_taxonomy();
	}


	/**
	 * Init WooCommerce Product Retailers user roles
	 *
	 * @since 1.0
	 */
	private static function init_user_roles() {
		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) && ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		if ( is_object( $wp_roles ) ) {
			$wp_roles->add_cap( 'shop_manager',  'manage_woocommerce_product_retailers' );
			$wp_roles->add_cap( 'administrator', 'manage_woocommerce_product_retailers' );
		}
	}


	/**
	 * Init WooCommerce taxonomies
	 *
	 * @since 1.0
	 */
	private static function init_taxonomy() {

		if ( current_user_can( 'manage_woocommerce' ) ) $show_in_menu = 'woocommerce'; else $show_in_menu = true;

		register_post_type( 'product_retailer',
			array(
				'labels' => array(
						'name'               => __( 'Retailers', WC_Product_Retailers::TEXT_DOMAIN ),
						'singular_name'      => __( 'Retailer', WC_Product_Retailers::TEXT_DOMAIN ),
						'menu_name'          => _x( 'Retailers', 'Admin menu name', WC_Product_Retailers::TEXT_DOMAIN ),
						'add_new'            => __( 'Add Retailer', WC_Product_Retailers::TEXT_DOMAIN ),
						'add_new_item'       => __( 'Add New Retailer', WC_Product_Retailers::TEXT_DOMAIN ),
						'edit'               => __( 'Edit', WC_Product_Retailers::TEXT_DOMAIN ),
						'edit_item'          => __( 'Edit Retailer', WC_Product_Retailers::TEXT_DOMAIN ),
						'new_item'           => __( 'New Retailer', WC_Product_Retailers::TEXT_DOMAIN ),
						'view'               => __( 'View Retailers', WC_Product_Retailers::TEXT_DOMAIN ),
						'view_item'          => __( 'View Retailer', WC_Product_Retailers::TEXT_DOMAIN ),
						'search_items'       => __( 'Search Retailers', WC_Product_Retailers::TEXT_DOMAIN ),
						'not_found'          => __( 'No Retailers found', WC_Product_Retailers::TEXT_DOMAIN ),
						'not_found_in_trash' => __( 'No Retailers found in trash', WC_Product_Retailers::TEXT_DOMAIN ),
					),
				'description'     => __( 'This is where you can add new product retailers that you can add to products.', WC_Product_Retailers::TEXT_DOMAIN ),
				'public'          => true,
				'show_ui'         => true,
				'capability_type' => 'post',
				'capabilities' => array(
					'publish_posts'       => 'manage_woocommerce_product_retailers',
					'edit_posts'          => 'manage_woocommerce_product_retailers',
					'edit_others_posts'   => 'manage_woocommerce_product_retailers',
					'delete_posts'        => 'manage_woocommerce_product_retailers',
					'delete_others_posts' => 'manage_woocommerce_product_retailers',
					'read_private_posts'  => 'manage_woocommerce_product_retailers',
					'edit_post'           => 'manage_woocommerce_product_retailers',
					'delete_post'         => 'manage_woocommerce_product_retailers',
					'read_post'           => 'manage_woocommerce_product_retailers',
				),
				'publicly_queryable'  => true,
				'exclude_from_search' => true,
				'show_in_menu'        => $show_in_menu,
				'hierarchical'        => false,
				'rewrite'             => false,
				'query_var'           => false,
				'supports'            => array( 'title','thumbnail','editor','comments'),
				'show_in_nav_menus'   => false,
				
			)
		);
		
		/*register_taxonomy(
			"retailer-location", array("product_retailer"), array(
				"hierarchical" => false,
				"label" => "Retailer Locations", 
				"new_item_name" => "Add New Locations", 
				"singular_label" => "Retailer Location", 
				"rewrite" => true));
		register_taxonomy_for_object_type('retailer-location', 'product_retailer');*/
	}

}
