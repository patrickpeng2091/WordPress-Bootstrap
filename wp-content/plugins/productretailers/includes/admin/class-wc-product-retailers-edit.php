<?php

class WC_Product_Retailers_Edit {


	/**
	 * Initialize and setup the retailer add/edit screen
	 *
	 * @since 1.0
	 */
	public function  __construct() {

		add_action( 'admin_head', array( $this, 'highlight_retailers_menu' ) );

		add_filter( 'post_updated_messages', array( $this, 'retailers_updated_messages' ) );

		add_action( 'add_meta_boxes', array( $this, 'retailers_meta_boxes' ) );

		add_filter( 'enter_title_here', array( $this, 'enter_retailer_name_here' ), 1, 2 );

		add_action( 'save_post', array( $this, 'meta_boxes_save' ), 1, 2 );

		add_action( 'woocommerce_process_product_retailer_meta', array( $this, 'process_retailer_meta' ), 10, 2 );

		// Disable autosave for the product_retailer post type
		add_action( 'admin_footer', array( $this, 'disable_autosave' ) );
	}


	/**
	 * Highlight the correct top level admin menu item for the product retailers post type add screen
	 *
	 * @since 1.0
	 */
	public function highlight_retailers_menu() {

		global $parent_file, $submenu_file, $post_type;

		if ( isset( $post_type ) && 'product_retailer' == $post_type ) {
			$submenu_file = 'edit.php?post_type=' . $post_type;
			$parent_file  = 'woocommerce';
		}
	}


	/**
	 * Set the product updated messages so they're specific to the product retailers
	 *
	 * @since 1.0
	 */
	public function retailers_updated_messages( $messages ) {
		global $post;

		$messages['product_retailer'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Retailer updated.', WC_Product_Retailers::TEXT_DOMAIN ),
			2 => __( 'Custom field updated.', WC_Product_Retailers::TEXT_DOMAIN ),
			3 => __( 'Custom field deleted.', WC_Product_Retailers::TEXT_DOMAIN ),
			4 => __( 'Retailer updated.', WC_Product_Retailers::TEXT_DOMAIN),
			5 => isset( $_GET['revision'] ) ? sprintf( __( 'Retailer restored to revision from %s', WC_Product_Retailers::TEXT_DOMAIN ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __( 'Retailer updated.', WC_Product_Retailers::TEXT_DOMAIN ),
			7 => __( 'Retailer saved.', WC_Product_Retailers::TEXT_DOMAIN ),
			8 => __( 'Retailer submitted.', WC_Product_Retailers::TEXT_DOMAIN ),
			9 => sprintf( __( 'Retailer scheduled for: <strong>%1$s</strong>.', WC_Product_Retailers::TEXT_DOMAIN ),
			  date_i18n( __( 'M j, Y @ G:i', WC_Product_Retailers::TEXT_DOMAIN ), strtotime( $post->post_date ) ) ),
			10 => __( 'Retailer draft updated.', WC_Product_Retailers::TEXT_DOMAIN),
		);

		return $messages;
	}


	/**
	 * Set a more appropriate placeholder text for the New Retailer title field
	 *
	 * @since 1.0
	 * @param string $text "Enter Title Here" string
	 * @param object $post post object
	 *
	 * @return string "Retailer Name" when the post type is product_retailer
	 */
	public function enter_retailer_name_here( $text, $post ) {

		if ( 'product_retailer' == $post->post_type ) {
			return __( 'Retailer Name', WC_Product_Retailers::TEXT_DOMAIN );
		}

		return $text;
	}


	/**
	 * Add and remove meta boxes from the Retailer edit page
	 *
	 * @since 1.0
	 */
	public function retailers_meta_boxes() {

		// Retailer Info box
		add_meta_box(
			'woocommerce-product-retailer-info',
			__( 'Retailer URL', WC_Product_Retailers::TEXT_DOMAIN ),
			array( $this, 'retailer_info_meta_box' ),
			'product_retailer',
			'normal',
			'high'
		);
		
		add_meta_box(
			'woocommerce-product-retailer-location',
			__( 'Retailer Location', WC_Product_Retailers::TEXT_DOMAIN ),
			array( $this, 'retailer_location_meta_box' ),
			'product_retailer',
			'normal',
			'high'
		);
        
		add_meta_box('postimagediv', __('Retailer Logo'),'post_thumbnail_meta_box','product_retailer', 'side', 'low');
		// remove unnecessary meta boxes
		//remove_meta_box( 'woothemes-settings', 'product_retailer', 'normal' );
		//remove_meta_box( 'commentstatusdiv',   'product_retailer', 'normal' );
		//remove_meta_box( 'slugdiv',            'product_retailer', 'normal' );
	}


	/**
	 * Product Retailer info meta box
	 *
	 * Displays the meta box
	 *
	 * @since 1.0
	 */
	public function retailer_info_meta_box( $post ) {

		wp_nonce_field( 'woocommerce_save_data', 'woocommerce_meta_nonce' );

		?>
		<?php /*?><style type="text/css">
			#misc-publishing-actions { display:none; }
			#edit-slug-box { display:none }
			#minor-publishing-actions { display:none; }
		</style><?php s*/?>
		<div id="product_retailer_options" class="panel woocommerce_options_panel">
			<div class="options_group">
				<?php if ( 'auto-draft' == $post->post_status ) : /* Automatically publish the post if the admin hits 'enter' in a form field */ ?>
					<input type="hidden" name="publish" value="Publish" />
				<?php endif; ?>
				<?php
					woocommerce_wp_text_input( array(
						'id'          => '_product_retailer_default_url',
						'label'       => __( 'Enter Details', WC_Product_Retailers::TEXT_DOMAIN ),
						'default'     => '',
						'description' => __( 'The default URL for the retailer, ie: http://www.example.com  This URL will be used unless overridden by a product.', WC_Product_Retailers::TEXT_DOMAIN ),
						'desc_tip'    => true,
					) );
				?>
            </div>
		</div>
		<?php
	}

    /**
	 * Product Retailer info meta box
	 *
	 * Displays the meta box
	 *
	 * @since 1.0
	 */
	public function retailer_location_meta_box( $post ) {

		wp_nonce_field( 'woocommerce_save_data', 'woocommerce_meta_nonce' );

		?>
	<?php /*?>	<style type="text/css">
			#misc-publishing-actions { display:none; }
			#edit-slug-box { display:none }
			#minor-publishing-actions { display:none; }
		</style><?php */?>
		<div id="product_retailer_options" class="panel woocommerce_options_panel">
			<div class="options_group">
				<?php if ( 'auto-draft' == $post->post_status ) : /* Automatically publish the post if the admin hits 'enter' in a form field */ ?>
					<input type="hidden" name="publish" value="Publish" />
				<?php endif; ?>
				<?php
					woocommerce_wp_text_input( array(
						'id'          => '_product_retailer_default_location',
						'label'       => __( 'Enter Details', WC_Product_Retailers::TEXT_DOMAIN ),
						'default'     => '',
						'description' => __( 'The default URL for the retailer, ie: http://www.example.com  This URL will be used unless overridden by a product.', WC_Product_Retailers::TEXT_DOMAIN ),
						'desc_tip'    => true,
					) );
				?>
			</div>
		</div>
		<?php
	}


	/**
	 * Runs when a post is saved and does an action which the write panel save scripts can hook into.
	 *
	 * @since 1.0
	 * @param int $post_id post identifier
	 * @param object $post post object
	 */
	public function meta_boxes_save( $post_id, $post ) {
		if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( is_int( wp_is_post_revision( $post ) ) ) return;
		if ( is_int( wp_is_post_autosave( $post ) ) ) return;
		if ( empty($_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( $_POST['woocommerce_meta_nonce'], 'woocommerce_save_data' ) ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;
		if ( 'product_retailer' != $post->post_type ) return;

		do_action( 'woocommerce_process_product_retailer_meta', $post_id, $post );
	}


	/**
	 * Retailer Data Save
	 *
	 * Function for processing and storing all product retailer data.
	 *
	 * @since 1.0
	 * @param int $post_id the product retailer post id
	 * @param object $post the product retailer post object
	 */
	public function process_retailer_meta( $post_id, $post ) {

		$retailer = new WC_Retailer( $post );
		$retailer->set_url( $_POST['_product_retailer_default_url'] ? $_POST['_product_retailer_default_url'] : '' );
		$retailer->set_location( $_POST['_product_retailer_default_location'] ? $_POST['_product_retailer_default_location'] : '' );
		$retailer->persist();

	}


	/**
	 * Disable autosave for the product_retailer post type
	 *
	 * @since 1.0
	 */
	public function disable_autosave() {
		global $typenow;

		if ( 'product_retailer' == $typenow ) {
			wp_dequeue_script( 'autosave' );
		}
	}

}
