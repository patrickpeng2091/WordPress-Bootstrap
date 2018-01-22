<?php

/**
 * Template Function Overrides
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
        function submit_form_dd(){
			
		$url = "http://emediaexperts.com/api";
	  
		$ch = curl_init();

        $purchasecode = $_POST["pid"] ?: get_option('enkey');

		$source = $url."/?purchaseid=" . $purchasecode . "&action=check_purchase&id=1";
		curl_setopt($ch, CURLOPT_URL, $source);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		if(!empty($_POST["pid"])) {
		   update_option( 'enkey', $_POST["pid"] );
		}
	  	   update_option( 'key', $data );
		   return $data;
	   }
		
       add_action('wp_ajax_submit_form_dd','submit_form_dd');
	   add_action('wp_ajax_nopriv_submit_form_dd','submit_form_dd');
	   
if ( ! function_exists( 'woocommerce_single_product_product_retailers' ) ) {

	/**
	 * Template function to include the product retailers template file
	 *
	 * @since 1.0
	 * @param WC_Product $product the product
	 * @param array $retailers optional array of WC_Retailer objects, otherwise any retailers associated with $product will be used
	 */
	function woocommerce_single_product_product_retailers( $product, $retailers = null ) {

		global $product_retailers;
      
		// get any retailers from the product, if not passed into the method
		if ( is_null( $retailers ) ) {
			$retailers = WC_Product_Retailers_Product::get_product_retailers( $product );
		}

		if ( empty( $retailers ) ) return;

		// dropdown javascript
		if ( 'yes' === get_option( 'product_retailers_enable_new_tab' ) ) {
			$javascript = '$( "select.wc-product-retailers" ).change( function() { var e = $( this ).val(); if ( e ) window.open(e); } );';
		} else {
			$javascript = '$( "select.wc-product-retailers" ).change( function() { var e = $( this ).val(); if ( e ) window.location.href = e } );';
		}

		// hide dropdown/button on variable product page until a variation is selected
		if ( $product->is_type( 'variable' ) ) {

			
			$javascript .= '$( "form.variations_form" ).bind( "show_variation", function() { $( ".wc-product-retailers" ).slideDown( 200 ); } ); $( document ).bind( "reset_image", function( event ) { $(".wc-product-retailers").hide() } );$("form.variations_form .variations select").change();';

			// if the product is not purchasable (purchasable only from retailers) remove the 'add to cart' button
			if ( ! $product->is_purchasable() ) {
				$javascript .= '$( ".variations_button" ).remove();';
			}
		}

		// add the javascript
		EM_WC_Plugin_Compatibility::wc_enqueue_js( $javascript );

		EM_WC_Plugin_Compatibility::wc_get_template(
			'single-product/product-retailers.php',
			array(
				'retailers'       => $retailers,
				'open_in_new_tab' => ( 'yes' === get_option( 'product_retailers_enable_new_tab' ) ),
			),
			'',
			$product_retailers->get_plugin_path() . '/templates/' );
	}
	

}


function reg_from () { 
         	$msg = array();
			$msg['success'] = "Product Retailer's Purchase Key Verified Successfully! | <a href='?example_nag_ignore=0'>Hide Notice</a>";
            $msg['fail'] = '<strong>Product Retailers</strong> requires purchase verification. Please enter the purchase key. | <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-can-I-find-my-Purchase-Code-" target="_blank" >Get Purchase Key</a>'; 
?>
<style>
#submit_form_dd { padding:5px 0px 15px !important; }
#submit_form_dd label { float:left; width:100%; padding-bottom:10px; }
#submit_form_dd input { width:300px; }
#submit_form_dd button {position:relative; top:-1px; }

</style>
<script>
jQuery(document).ready(function(){
		jQuery("#submit_form_dd").submit(function(e) {
		//	e.preventDefault();
	    var send_data = jQuery("#submit_form_dd").serialize();
					jQuery.post(ajaxurl, 'action=submit_form_dd&' + send_data, function(data){
						if( data.success == '1' ){
						}else{
						}
					}, 'json');
        });
		 var result = "<?php echo submit_form_dd() ?>";
		
		    if (result == "valid") {
			    jQuery(".varmsg").html("<?php echo $msg['success']; ?>")
				// jQuery("#enkey").attr('disabled','disabled');   
				// jQuery(".varbnt").remove();
				 jQuery( ".error" ).addClass( "updated");
				 jQuery( ".error" ).removeClass( "error");
				
			}else{
			    jQuery(".varmsg").html('<?php echo $msg['fail']; ?>'); 
				
			}
		 
});
</script>

<?php 	

   echo '<div class="error"><p class="varmsg"></p> <form method="post" id="submit_form_dd" name="submit_form_dd">
                    <label for="siteurl">Purchase Key:</label>
                    <input type="text" name="pid" class="form-control" id="enkey" value="'.get_option('enkey').'">
			        <button type="submit" class="add-new-h2 varbnt" >Submit</button>
                </form><div id="result"></div></div>';
}

		
	   /* Display a notice that can be dismissed */
 	if (get_option('key') == "valid") {
		}else{
	    remove_action( 'woocommerce_single_product_summary',   array( $this, 'add_retailer_dropdown' ), 35 );	
	    add_action( 'woocommerce_single_product_summary',   array( $this, 'add_retailer_dropact' ), 35 );	
}
