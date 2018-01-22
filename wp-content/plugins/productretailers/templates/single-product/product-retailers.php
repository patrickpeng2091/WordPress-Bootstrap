<?php

echo "<script type='text/javascript'>
	jQuery(document).ready(function() {
		jQuery('.various').fancybox({
		maxWidth	: 700,
		maxHeight	: 600,
		fitToView	: true,
		width		: '70%',
		height		: '70%',
		autoSize	: true,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
	});
</script>
";

echo '<button class="various button" href="#inline">Where to Buy</button>';
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

// hide the retailers selection for variable products
$style = '';
if ( $product->is_type( 'variable' ) ) {
	$style = 'display:none;';
}
$retailers_style = get_option( 'product_retailers_style' );
?>
<style>
button.various.button {
    text-transform: uppercase;
    margin-bottom: 26px;
}
.hide {display:none;}
</style>
<div id="inline" class="wc-product-retailers-wrap hide">
  <?php

if ( $retailers_style == "dropdown" ) :
	?>
  <select name="wc-product-retailers" class="wc-product-retailers" style="<?php echo $style; ?>">
    <option value=""><?php echo esc_html( WC_Product_Retailers_Product::get_product_button_text( $product ) ); ?></option>
    <?php foreach ( $retailers as $retailer ) : ?>
    <option value="<?php echo esc_attr( $retailer->get_url() ); ?>"><?php echo esc_html( $retailer->get_name() ); if ( $retailer->get_price() ) printf( ' - %s', wp_kses_post( EM_WC_Plugin_Compatibility::wc_price( ( $retailer->get_price() ) ) ) ); ?></option>
    <?php endforeach; ?>
  </select>
  <?php
elseif ( $retailers_style == "button" ) :
	?>
  <?php if ( trim( WC_Product_Retailers_Product::get_product_button_text( $product ) ) ) : ?>
  <p><?php echo esc_html( WC_Product_Retailers_Product::get_product_button_text( $product ) ); ?></p>
  <?php endif; ?>
  <ul class="wc-product-retailers" style="<?php echo $style; ?>">
    <?php foreach ( $retailers as $retailer ) : ?>
    <li><a href="<?php echo esc_attr( $retailer->get_url() ); ?>" <?php if ( $open_in_new_tab ) echo 'target="_blank"'; ?> rel="nofollow" class="wc-product-retailers button alt btn"><?php echo esc_html( apply_filters( 'product_retailers_button_label', $retailer->get_name() . ( $retailer->get_price() ? sprintf( ' - %s', wp_kses_post( WC_Product_Retailers_Product::wc_price( ( $retailer->get_price() ) ) ) ) : '' ), $retailer, $product ) ); ?></a></li>
    <?php endforeach; ?>
  </ul>
  <?php
else : ;
	?>
  <style>
  <?php echo $retailers_css = get_option( 'product_retailers_custom_css' ); ?>
 
.retailer-table { border-width: 1px !important; border-bottom: 0; border-right: 0; }
.retailer-table td, .retailer-table th { text-align: center; padding:0 20px }
.retailer-table th { padding: 5px; }
.retailer-table .button { float: none !important; }
.retailer-column img { padding: 8px 0; height: auto; max-width: 100px; border: none; }
.wc-product-retailers.button { text-transform: capitalize; padding: 5px 28px; }
.retailer-table thead th,  .retailer-table tfoot th { background-color: #ddd !important; color: #333 !important; }
.retailer-table { width: 100%; margin: 0 auto 1em; }
.retailer-table,  .retailer-table tr,  .retailer-table tbody td,  .retailer-table thead th,  .retailer-table tfoot th { border: 1px solid #ddd; }
.retailer-table thead th { font-size: 14px; color: white; text-align: center; }
    </style>
  <style>
	
	
	/* 
	Max width before this PARTICULAR table gets nasty
	This query will take effect for any screen smaller than 760px
	and also iPads specifically.
	*/
	@media 
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
	
		/* Force table to not be like tables anymore */
		table, thead, tbody, th, td, tr { 
			display: block; 
		}
		
		/* Hide table headers (but not display: none;, for accessibility) */
		thead tr { 
			position: absolute;
			top: -9999px;
			left: -9999px;
		}
		
		tr { margin-bottom:20px; }
		td { 
			/* Behave  like a "row" */
			border: none;
			position: relative;
			padding-left: 0%; 
		}
		
		td:before { 
			/* Now like a table header */
			position: absolute;
			/* Top/left values mimic padding */
			top: 6px;
			left: 6px;
			width: 10%; 
			padding-right: 10px; 
			white-space: nowrap;
		}
		.buy-column {padding:10px !important;}
		/*
		Label the data
		*/
		
	}
	
	/* Smartphones (portrait and landscape) ----------- */
	@media only screen
	and (min-device-width : 320px)
	and (max-device-width : 480px) {
		body { 
			padding: 0; 
			margin: 0; 
			width: 320px; }
		}
	
	/* iPads (portrait and landscape) ----------- */
	@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
		body { 
			width: 495px; 
		}
	}
	
	</style>
  
  <?php 
  
        $enable_buying = get_option( 'product_retailers_enable_buying');
        $enable_about = get_option( 'product_retailers_enable_about');
		$enable_price = get_option( 'product_retailers_enable_price');
		$enable_location = get_option( 'product_retailers_enable_location');
	    $enable_review = get_option( 'product_retailers_enable_review' );
		$enable_name = get_option( 'product_retailers_enable_name' );
		
		
   ?>
  <script>
  
  $('#myTable').stacktable();
  
  </script>

  <table data-role="table" data-mode="columntoggle" class="ui-responsive retailer-table" id="myTable">
    <thead>
      <tr>
        <th class="retailer-column"> <span class="column-label">Retailer</span> </th>
         <?php if ($enable_name == "yes") {?>
         <th class="details-column"><span class="column-label">Name</span> </th>
         <?php } ?>
         <?php if ($enable_review == "yes") {?>
         <th class="rating-column"><span class="column-label">Ratings</span> </th>
         <?php } ?>
         <?php if ($enable_location == "yes") {?>
        <th class="location-column"><span class="column-label">Location</span> </th>
         <?php } ?>
         <?php if ($enable_price == "yes") {?>
        <th class="price-column"><span class="column-label">Price</span> </th>
         <?php } ?>
        <?php if ($enable_buying == "yes") {?>
        <th class="buy-column"> <span class="column-label">Buying Options</span> </th>
        <?php } ?>
      </tr>
    </thead>
    
    <!-- OLP Hover Box -->
    
    <tbody class="offer-has-map offer-hidden-map">
      <?php if ( trim( WC_Product_Retailers_Product::get_product_button_text( $product ) ) ) : ?>
    <p><?php echo esc_html( WC_Product_Retailers_Product::get_product_button_text( $product ) ); ?></p>
    <?php endif; ?>
    <?php foreach ( $retailers as $retailer ) : ?>
      <tr>
      <td class="retailer-column"><div class="merchant-logo"><?php echo $retailer->get_logo() ?></div> <?php if ($enable_location == "yes") {?><small><a href="<?php echo get_permalink($retailer->get_id()); ?>">About Retailer</a></small><?php } ?></td>
       <?php if ($enable_name == "yes") {?>
       <td class="details-column"><strong><?php echo $retailer->get_name() ?></strong></td>
       <?php } ?>
       <?php if ($enable_review == "yes") {?>
      <td class="rating-column">
      <?php
		 $postID = $retailer->get_id();
		 $count   = get_comments_number($postID);
		 $average = get_retailer_rating($postID);
		if ( $count > 0 ) { ?>
			<div class="woocommerce-product-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
				<div class="star-rating" title="<?php printf( __( 'Rated %s out of 5', 'woocommerce' ), $average ); ?>">
					<span style="width:<?php echo ( ( $average / 5 ) * 100 ); ?>%">
						<strong itemprop="ratingValue" class="rating"><?php echo esc_html( $average ); ?></strong> <?php _e( 'out of 5', 'woocommerce' ); ?>
					</span>
				</div>
				<a href="<?php echo get_permalink($retailer->get_id()); ?>#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s review', '%s reviews', $count, 'woocommerce' ), '<span itemprop="ratingCount" class="count">' . $count . '</span>' ); ?>)</a>
			</div>

<?php }else{ echo 'No Review'; } ?>

      </td>
       <?php } ?>
       <?php if ($enable_location == "yes") {?>
      <td class="location-column"><?php echo $retailer->get_location() ?></td>
       <?php } ?>
       <?php if ($enable_price == "yes") {?>
      <td class="price-column"><?php echo esc_html( apply_filters( 'product_retailers_button_label',( $retailer->get_price() ? sprintf( '%s', wp_kses_post( WC_Product_Retailers_Product::wc_price( ( $retailer->get_price() ) ) ) ) : '' ), $retailer, $product ) ); ?></td>
       <?php } ?>
       <?php if ($enable_buying == "yes") {?>
      <td class="buy-column"><a href="<?php echo esc_attr( $retailer->get_url() ); ?>" <?php if ( $open_in_new_tab ) echo 'target="_blank"'; ?> rel="nofollow" class="wc-product-retailers button alt btn">
        <?php if ( trim( WC_Product_Retailers_Product::get_catalog_button_text( $product ) ) ) : ?>
        <?php echo esc_html( WC_Product_Retailers_Product::get_catalog_button_text( $product ) ); ?>
        <?php endif; ?>
        </a></td>
        <?php } ?>
      </tr>
    <?php endforeach; ?>
      </tbody>
    
  </table>
  <?php
endif;
?>
</div>
