<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Retailers Admin Class - handles admin UX
 *
 * @since 1.0
 */
 class WC_Settings_Tab {
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_settings_product_retailers', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_settings_product_retailers', __CLASS__ . '::update_settings' );
        add_action( 'woocommerce_sections',__CLASS__ . '::get_sections');
    }

    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_product_retailers'] = __( 'Product Retailers', 'woocommerce-settings-product-retailers' );
        return $settings_tabs;
    }

    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }

    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }

      public function get_sections() {
        $sections = array(
          '' => __( 'Test Link 1', 'woocommerce' ),
          'testlink2' => __( 'Test Link 2', 'woocommerce' ),
        );

        return apply_filters( 'woocommerce_sections', $sections );
      }    

    public static function get_settings() {

        $settings = array(
			// section start
			
			
			array(
				'name' => __( 'Product Retailers', WC_Product_Retailers::TEXT_DOMAIN ),
				'type' => 'title',
				'desc' => '',
				'id' => 'product_retailer_options',
			),
			
		/*	array(
				'title'    => __( 'Envato Purchase Key', WC_Product_Retailers::TEXT_DOMAIN ),
				'desc_tip' => __( 'To make Product Retailers plugin fully functional, you must have to verify purchase key first!.', WC_Product_Retailers::TEXT_DOMAIN ),
				'id'       => 'enkey',
				'css'      => 'width:300px;',
				'default'  => __( '', WC_Product_Retailers::TEXT_DOMAIN ),
				'type'     => 'text',
			),*/
			
			
           array(
				'title'    => __( 'Display Style', WC_Product_Retailers::TEXT_DOMAIN ),
				'desc_tip' => __( 'You can set different display types.', WC_Product_Retailers::TEXT_DOMAIN ),
				'id'       => 'product_retailers_style',
				'css'      => 'width:200px;',
				'default'  => __( 'Table', WC_Product_Retailers::TEXT_DOMAIN ),
				'options' => array(
				    'popup' => __( 'Popup', 'woocommerce' ),
					'dropdown' => __( 'Dropdown', 'woocommerce' ),
					'button' => __( 'Button', 'woocommerce' ),
					'table' => __( 'Table', 'woocommerce' )
				),
				'type'     => 'select',
			),
			// product button text
			array(
				'title'    => __( 'Message to Viewers', WC_Product_Retailers::TEXT_DOMAIN ),
				'desc_tip' => __( 'This text will be shown on the dropdown linking to the external product, unless overridden at the product level.', WC_Product_Retailers::TEXT_DOMAIN ),
				'id'       => 'product_retailers_product_button_text',
				'css'      => 'width:200px;',
				'default'  => __( 'Purchase from Retailer', WC_Product_Retailers::TEXT_DOMAIN ),
				'type'     => 'text',
			),
	/*woocommerce_wp_select( array( 'id' => '_stock_status', 'wrapper_class' => 'hide_if_variable', 'label' => __( 'Stock status', 'woocommerce' ), 'options' => array(
					'instock' => __( 'In stock', 'woocommerce' ),
					'outofstock' => __( 'Out of stock', 'woocommerce' )
				), 'desc_tip' => true, 'description' => __( 'Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend.', 'woocommerce' ) ) );*/
			// catalog button text
			array(
				'title'    => __( 'Catalog Button Text', WC_Product_Retailers::TEXT_DOMAIN ),
				'desc_tip' => __( 'This text will be shown on the catalog page "Add to Cart" button for simple products that are sold through retailers only, unless overridden at the product level.', WC_Product_Retailers::TEXT_DOMAIN ),
				'id'       => 'product_retailers_catalog_button_text',
				'css'      => 'width:200px;',
				'default'  => __( 'Buy Now', WC_Product_Retailers::TEXT_DOMAIN ),
				'type'     => 'text',
			),
			// open in new tab
			array(
				'title'    => __( 'Open retailer links in a new tab', WC_Product_Retailers::TEXT_DOMAIN ),
				'desc'     => __( 'Enable this option to open links to other retailers in a new tab instead of the current one.', WC_Product_Retailers::TEXT_DOMAIN ),
				'id'       => 'product_retailers_enable_new_tab',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			
			array(
				'title'    => __( 'Enable Price Options', WC_Product_Retailers::TEXT_DOMAIN ),
				'desc'     => __( "Enable this option will show price column in table.", WC_Product_Retailers::TEXT_DOMAIN ),
				'id'       => 'product_retailers_enable_price',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			
			array(
				'title'    => __( 'Enable Location Options', WC_Product_Retailers::TEXT_DOMAIN ),
				'desc'     => __( "Enable this option will show location column in table.", WC_Product_Retailers::TEXT_DOMAIN ),
				'id'       => 'product_retailers_enable_location',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			
			array(
				'title'    => __( 'Enable Buying Options', WC_Product_Retailers::TEXT_DOMAIN ),
				'desc'     => __( "Enable this option will allow user to directly buy from retailer's site", WC_Product_Retailers::TEXT_DOMAIN ),
				'id'       => 'product_retailers_enable_buying',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Enable Name Options', WC_Product_Retailers::TEXT_DOMAIN ),
				'desc'     => __( "Enable this option will show name field in table.", WC_Product_Retailers::TEXT_DOMAIN ),
				'id'       => 'product_retailers_enable_name',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Enable About Retailer Page Link', WC_Product_Retailers::TEXT_DOMAIN ),
				'desc'     => __( "Enable this option will allow user to view about retailer's page", WC_Product_Retailers::TEXT_DOMAIN ),
				'id'       => 'product_retailers_enable_about',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			
			array(
				'title'    => __( 'Disable ADD TO CART Button', WC_Product_Retailers::TEXT_DOMAIN ),
				'desc'     => __( "You can disable add to cart button.", WC_Product_Retailers::TEXT_DOMAIN ),
				'id'       => 'product_retailers_disable_cart',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			
			array(
				'title'    => __( 'Enable Retailer Review', WC_Product_Retailers::TEXT_DOMAIN ),
				'desc'     => __( "You can disable add to cart button.", WC_Product_Retailers::TEXT_DOMAIN ),
				'id'       => 'product_retailers_enable_review',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			
				array(
				'title'    => __( 'Custom CSS', WC_Product_Retailers::TEXT_DOMAIN ),
				'desc_tip' => __( 'You can add custom css code to override styling', WC_Product_Retailers::TEXT_DOMAIN ),
				'id'       => 'product_retailers_custom_css',
				'css'      => 'width:300px;',
				'default'  => '',
				'type'     => 'textarea',
			),

			// section end
			array( 'type' => 'sectionend', 'id' => 'product_retailer_options' ),
		);

        return apply_filters( 'wc_settings_product_retailers_settings', $settings );
    }

}

WC_Settings_Tab::init();

 
class WC_Product_Retailers_Admin {


	/**
	 * Setup admin class
	 *
	 * @since 1.0
	 */
	public function __construct() {

		// load styles/scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'load_styles_scripts' ) );

		// load WC scripts on the edit retailers page
		add_filter( 'woocommerce_screen_ids', array( $this, 'load_wc_admin_scripts' ) );

		// add product tab
		add_action( 'woocommerce_product_write_panel_tabs', array( $this, 'add_product_tab' ), 11 );

		// add product tab data
		add_action( 'woocommerce_product_write_panels', array( $this, 'add_product_tab_options' ), 11 );

		// save product tab data
		add_action( 'woocommerce_process_product_meta_simple',   array( $this, 'save_product_tab_options' ) );
		add_action( 'woocommerce_process_product_meta_variable', array( $this, 'save_product_tab_options' ) );

		// add AJAX retailer search
		add_action( 'wp_ajax_product_retailers_search_retailers', array( $this, 'ajax_search_retailers' ) );
	}


	/**
	 * Load admin js/css
	 *
	 * @since 1.0
	 * @param string $hook_suffix
	 */
	public function load_styles_scripts( $hook_suffix ) {
		
		
		global $product_retailers, $post_type;

		if ( 'product_retailer' == $post_type && 'edit.php' == $hook_suffix ) {
			
			
			ob_start();
			?>
			// get rid of the date filter and also the filter button itself, unless there are other filters added
			$( 'select[name="m"]' ).remove();
			if ( ! $('#post-query-submit').siblings('select').size() ) $('#post-query-submit').remove();
			<?php
			$js = ob_get_clean();
			EM_WC_Plugin_Compatibility::wc_enqueue_js( $js );
		}

		// load admin css/js only on edit product/new product pages
		if ( 'product' == $post_type && ( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix ) ) {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
             global $woocommerce;
			// admin CSS
			wp_enqueue_style( 'wc-product-retailers-admin', $product_retailers->get_plugin_url() . '/assets/css/admin/wc-product-retailers-admin.min.css', array( 'woocommerce_admin_styles' ), WC_Product_Retailers::VERSION );

			// admin JS
			wp_enqueue_script( 'wc-product-retailers-admin', $product_retailers->get_plugin_url() . '/assets/js/admin/wc-product-retailers-admin' . $suffix . '.js', WC_Product_Retailers::VERSION );
			
		 	wp_register_script( 'ajax-chosen', $product_retailers->get_plugin_url() . '/assets/js/chosen/ajax-chosen.jquery'.$suffix.'.js', array('jquery', 'chosen'), $woocommerce->version );

	wp_register_script( 'chosen', $product_retailers->get_plugin_url() . '/assets/js/chosen/chosen.jquery'.$suffix.'.js', array('jquery'), $woocommerce->version );
	
	        wp_enqueue_script( 'ajax-chosen' );
    	    wp_enqueue_script( 'chosen' );
			
			wp_enqueue_script( 'jquery-ui-sortable' );

			// add script data
			$product_retailers_admin_params = array(
				'search_retailers_nonce' => wp_create_nonce( 'search_retailers' ),
			);
			wp_localize_script( 'wc-product-retailers-admin', 'product_retailers_admin_params', $product_retailers_admin_params );
		}

		// load WC CSS on add/edit retailer page
		if ( 'product_retailer' == $post_type ) {
			wp_enqueue_style( 'woocommerce_admin_styles', EM_WC_Plugin_Compatibility::WC()->plugin_url() . '/assets/css/admin.css' );
		}
	}


	/**
	 * Add settings/export screen ID to the list of pages for WC to load its CSS/JS on
	 *
	 * @since 1.0
	 * @param array $screen_ids
	 * @return array
	 */
	public function load_wc_admin_scripts( $screen_ids ) {

		$screen_ids[] = 'product_retailer';

		return $screen_ids;
	}


	/**
	 * Returns the global settings array for the plugin
	 *
	 * @since 1.0
	 * @return array the global settings
	 */



	/**
	 * Inject global settings into the Settings > Catalog/Products page(s), immediately after the 'Product Data' section
	 *
	 * @since 1.0
	 * @param array $settings associative array of WooCommerce settings
	 * @return array associative array of WooCommerce settings
	 */
	public function add_global_settings( $settings ) {

		if ( EM_WC_Plugin_Compatibility::is_wc_version_gte_2_1() ) {
			$setting_id = 'product_data_options';
		} else {
			$setting_id = 'product_review_options';
		}

		$updated_settings = array();

		foreach ( $settings as $setting ) {

			$updated_settings[] = $setting;

			if ( isset( $setting['id'] ) && $setting_id === $setting['id']
				 && isset( $setting['type'] ) && 'sectionend' === $setting['type'] ) {
				$updated_settings = array_merge( $updated_settings, self::get_global_settings() );
			}
		}

		return $updated_settings;
	}


	/**
	 * Add 'Retailers' tab to product data writepanel
	 *
	 * @since 1.0
	 */
	public function add_product_tab() {

		?><li class="wc-product-retailers-tab<?php echo EM_WC_Plugin_Compatibility::is_wc_version_gte_2_1() ? '' : '-2-0-compat'; ?> wc-product-retailers-options hide_if_external hide_if_grouped"><a href="#wc-product-retailers-data"><?php _e( 'Retailers', WC_Product_Retailers::TEXT_DOMAIN ); ?></a></li><?php
	}


	/**
	 * Add product retailers options to product writepanel
	 *
	 * @since 1.0
	 */
	public function add_product_tab_options() {
		global $product_retailers;

		?>
			<div id="wc-product-retailers-data" class="panel woocommerce_options_panel">
				<div class="options_group">
					<?php

					do_action( 'product_retailers_product_options_start' );

			/*		// retailer only purchase
					woocommerce_wp_checkbox(
						array(
							'id'          => '_product_retailers_retailer_only_purchase',
							'label'       => __( 'Retailer Only Purchase', WC_Product_Retailers::TEXT_DOMAIN ),
							'description' => __( 'Enable this to only allow purchase from the listed retailers. The add to cart button will be removed.', WC_Product_Retailers::TEXT_DOMAIN ),
						)
					);

					// show buttons
					woocommerce_wp_checkbox(
						array(
							'id'          => '_product_retailers_use_buttons',
							'label'       => __( 'Use Buttons', WC_Product_Retailers::TEXT_DOMAIN ),
							'description' => __( 'Enable this to use buttons rather than a dropdown for multiple retailers.', WC_Product_Retailers::TEXT_DOMAIN ),
						)
					);

					// product button text
					woocommerce_wp_text_input(
						array(
							'id'          => '_product_retailers_product_button_text',
							'label'       => __( 'Product Button Text', WC_Product_Retailers::TEXT_DOMAIN ),
							'description' => __( 'This text will be shown on the dropdown linking to the external product, or before the buttons if "Use Buttons" is enabled.', WC_Product_Retailers::TEXT_DOMAIN ),
							'desc_tip'    => true,
							'placeholder' => $product_retailers->get_product_button_text(),
						)
					);

					// product button text
					woocommerce_wp_text_input(
						array(
							'id'          => '_product_retailers_catalog_button_text',
							'label'       => __( 'Catalog Button Text', WC_Product_Retailers::TEXT_DOMAIN ),
							'description' => __( 'This text will be shown on the catalog page "Add to Cart" button for simple products that are sold through retailers only.', WC_Product_Retailers::TEXT_DOMAIN ),
							'desc_tip'    => true,
							'placeholder' => $product_retailers->get_catalog_button_text(),
						)
					);*/

					do_action( 'product_retailers_product_options_end' );
					?>
				</div>
				<div class="options_group">
					<?php $this->add_retailers_table(); ?>
				</div>
			</div>
		<?php
	}


	/**
	 * Add product retailers add/remove table
	 *
	 * @since 1.0
	 */
	private function add_retailers_table() {
		global $post;
		?>  <style>.wc-product-retailer-product-logo img {width:50px; height:50px;}</style>
        <?php echo '<div style="margin:10px;"> You can also use shortcode <code>[Product_Retailer id="'.$post->ID.'"]</code></div>' ;?>
			<table class="widefat wc-product-retailers">
				<thead>
				<tr>
					<th class="check-column"><input type="checkbox"></th>
					<th class="wc-product-retailer-name"><?php _e( 'Select All', WC_Product_Retailers::TEXT_DOMAIN ); ?></th>
                    <th class="wc-product-retailer-product-url"><?php _e( 'Product URL', WC_Product_Retailers::TEXT_DOMAIN ); ?></th>
                    <th class="wc-product-retailer-price"><?php _e( 'Price', WC_Product_Retailers::TEXT_DOMAIN ); ?></th>
                    <th class="wc-product-retailer-product-location"><?php _e( 'Location', WC_Product_Retailers::TEXT_DOMAIN ); ?></th>
                    <th class="wc-product-retailer-product-logo-title"><?php _e( 'Logo', WC_Product_Retailers::TEXT_DOMAIN ); ?></th>
					
					
                </tr>
				</thead>
				<tbody>
				<?php
				$retailers = get_post_meta( $post->ID, '_product_retailers', true );
				if ( ! empty( $retailers) ) :
					$index = 0;
					foreach ( $retailers as $retailer ) :

						// build the retailer object and override the URL as needed
						$_retailer = new WC_Retailer( $retailer['id'] );

						// product URL for retailer
						if ( isset( $retailer['product_url'] ) ) {
							$_retailer->set_url( $retailer['product_url'] );
						}

						// product price for retailer
						if ( isset( $retailer['product_price'] ) ) {
							$_retailer->set_price( $retailer['product_price'] );
						}
                         
						 if ( isset( $retailer['product_logo'] ) ) {
							$_retailer->set_logo( $retailer['product_logo'] );
						}
						
						if ( isset( $retailer['product_location'] ) ) {
							$_retailer->set_location( $retailer['product_location'] );
						}
						// if the retailer is not available (trashed) exclude it
						if ( ! $_retailer->is_available( true ) ) {
							continue;
						}

						?>
                      
						<tr class="wc-product-retailer" style="box-shadow: 1px 1px #ddd;">
							<td class="check-column">
								<input type="checkbox" name="select" />
								<input type="hidden" name="_product_retailer_id[<?php echo $index; ?>]" value="<?php echo esc_attr( $_retailer->get_id() ); ?>" />
							</td>
							<td class="wc-product-retailer_name"><?php echo esc_html( $_retailer->get_name() ); ?></td>
                            <td class="wc-product-retailer-product-url">
								<input type="text" data-post-id="<?php echo esc_attr( $_retailer->get_id() ); ?>" id="wc-product-retailer-product-url-<?php echo esc_attr( $_retailer->get_id() ); ?>" name="_product_retailer_product_url[<?php echo $index; ?>]" value="<?php echo esc_attr( $_retailer->get_url() ); ?>" />
							</td> 
                            <td class="wc-product-retailer-product-price">
								<input type="text" data-post-id="<?php echo esc_attr( $_retailer->get_id() ); ?>" id="wc-product-retailer-price-<?php echo esc_attr( $_retailer->get_id() ); ?>" name="_product_retailer_product_price[<?php echo $index; ?>]" value="<?php echo esc_attr( $_retailer->get_price() ); ?>" />
							</td>
                             <td class="wc-product-retailer-product-location"><?php echo esc_attr( $_retailer->get_location() ); ?>
								<?php /*?><input type="text" data-post-id="<?php echo esc_attr( $_retailer->get_id() ); ?>" id="wc-product-retailer-product-location-<?php echo esc_attr( $_retailer->get_id() ); ?>" name="_product_retailer_product_location[<?php echo $index; ?>]" value="<?php echo esc_attr( $_retailer->get_location() ); ?>" /><?php */?>
							</td>
                             <td class="wc-product-retailer-product-logo"><?php echo $_retailer->get_logo(); ?>
								<?php /*?><input type="text" data-post-id="<?php echo esc_attr( $_retailer->get_id() ); ?>" id="wc-product-retailer-product-logo-<?php echo esc_attr( $_retailer->get_id() ); ?>" name="_product_retailer_product_logo[<?php echo $index; ?>]" value="<?php echo esc_attr( $_retailer->get_logo() ); ?>" /><?php */?>
							</td>
						</tr>
                      <?php
						$index++;
					endforeach;
				endif;
				?>
				</tbody>
				<tfoot>
				<tr>
					<th colspan="8">
						<img class="help_tip" data-tip='<?php _e( 'Search for a retailer to add to this product. You may add multiple retailers by searching for them first.', WC_Product_Retailers::TEXT_DOMAIN ) ?>' src="<?php echo EM_WC_Plugin_Compatibility::WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />
						<select id="wc-product-retailers-retailer-search" name="product_retailers_retailer_search" class="ajax-chosen-select-retailers" multiple="multiple" data-placeholder="<?php printf( __( 'Search for a retailer to add%s', WC_Product_Retailers::TEXT_DOMAIN ), '&hellip;' ); ?>"></select>
						<button type="button" class="button button-primary wc-product-retailers-add-retailer"><?php _e( 'Add Retailer', WC_Product_Retailers::TEXT_DOMAIN ); ?></button>
						<button type="button" class="button button-secondary wc-product-retailers-delete-retailer"><?php _e( 'Delete Selected', WC_Product_Retailers::TEXT_DOMAIN ); ?></button>
					</th>
				</tr>
				</tfoot>
			</table>
		<?php
	}


	/**
	 * Save product retailers options at the product level
	 *
	 * @since 1.0
	 * @param int $post_id the ID of the product being saved
	 */
	public function save_product_tab_options( $post_id ) {

		// retailer only purchase?
		update_post_meta(
			$post_id,
			'_product_retailers_retailer_only_purchase',
			isset( $_POST['_product_retailers_retailer_only_purchase'] ) && 'yes' === $_POST['_product_retailers_retailer_only_purchase'] ? 'yes' : 'no'
		);

		// use buttons rather than a dropdown?
		update_post_meta(
			$post_id,
			'_product_retailers_use_buttons',
			isset( $_POST['_product_retailers_use_buttons'] ) && 'yes' === $_POST['_product_retailers_use_buttons'] ? 'yes' : 'no'
		);

		// product button text
		if ( isset( $_POST['_product_retailers_product_button_text'] ) ) {
			update_post_meta( $post_id, '_product_retailers_product_button_text', $_POST['_product_retailers_product_button_text'] );
		}

		// catalog button text
		if ( isset( $_POST['_product_retailers_catalog_button_text'] ) ) {
			update_post_meta( $post_id, '_product_retailers_catalog_button_text', $_POST['_product_retailers_catalog_button_text'] );
		}

		$retailers = array();

		// persist any retailers assigned to this product
		if ( ! empty( $_POST['_product_retailer_product_url'] ) && is_array( $_POST['_product_retailer_product_url'] ) ) {

            global $retailer_id, $retailer_price, $retailer_location, $retailer_logo, $retailer_product_url;
 
			foreach ( $_POST['_product_retailer_product_url'] as $index => $retailer_product_url ) {
               
				
				$retailer_id = $_POST['_product_retailer_id'][ $index ];
               
				$retailer_price = $_POST['_product_retailer_product_price'][ $index ];
				
				if ( ! empty( $_POST['_product_retailer_product_logo'] )) {
				      $retailer_logo = $_POST['_product_retailer_product_logo'][ $index ];
				}
				if ( ! empty( $_POST['_product_retailer_product_location'] )) {
				      $retailer_location = $_POST['_product_retailer_product_location'][ $index ];
				}
				// only save the product URL if it's unique to the product
				
				$retailers[] = array(
					'id'            => $retailer_id,
					'product_price' => $retailer_price,
					'product_location' => $retailer_location,
					'product_logo' => $retailer_logo,
					'product_url'   => $retailer_product_url !== get_post_meta( $retailer_id, '_product_retailer_default_url', true ) ? esc_url_raw( $retailer_product_url ) : '',
					
				);
			}
		}

		update_post_meta( $post_id, '_product_retailers', $retailers );

	}


	/**
	 * Processes the AJAX retailer search on the edit product page
	 *
	 * @since 1.0
	 */
	public function ajax_search_retailers() {

		// security check
		check_ajax_referer( 'search_retailers', 'security' );

		// set response as JSON
		header( 'Content-Type: application/json; charset=utf-8' );

		// get search term
		$term = (string) urldecode( stripslashes( strip_tags( $_GET['term'] ) ) );

		if ( empty( $term ) ) {
			die();
		}

		$args = array(
			'post_type'    => 'product_retailer',
			'post_status'  => 'publish',
			'nopaging'     => true,
		);

		if ( is_numeric( $term ) ) {

			//search by retailer ID
			$args['p'] = $term;

		} else {

			// search by retailer name
			$args['s'] = $term;

		}

		$posts = get_posts( $args );

		$retailers = array();

		// build the set of found retailers
		if ( ! empty( $posts ) ) {

			foreach ( $posts as $post ) {

				$retailers[] = array(
					'id'          => $post->ID,
					'name'        => $post->post_title,
					'product_url' => get_post_meta( $post->ID, '_product_retailer_default_url', true ),
					'product_logo' => get_post_meta( $post->ID, '_product_retailer_default_logo', true ),
					'product_location' => get_post_meta( $post->ID, '_product_retailer_default_location', true )
				);
			}
		}

		// json encode and return
		echo json_encode( $retailers );

		die;
	}


} // end \WC_Product_Retailers_Admin class
