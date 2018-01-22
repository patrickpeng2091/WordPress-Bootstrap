jQuery( document ).ready( function( $ ) {
	"use strict";
	/*jshint multistr: true */
	/* global ajaxurl */
	/* global product_retailers_admin_params */


	// product retailer lookup
	$( 'select.ajax-chosen-select-retailers' ).ajaxChosen( {
		method         : 'GET',
		url            : ajaxurl,
		dataType       : 'json',
		afterTypeDelay : 100,
		data           : {
			action   : 'product_retailers_search_retailers',
			security : product_retailers_admin_params.search_retailers_nonce
		}
	}, function( data ) {

		var terms = {};
		var excludes = $( 'table.wc-product-retailers tr.wc-product-retailer input[type="text"]' ).map( function() { return $( this ).data( 'post-id' ); } ).get();

		// populate the option element if the retailer isn't already in the selected table
		$.each( data, function() {
			if ( -1 === $.inArray( this.id, excludes ) ) { terms[ this.id ] = this.name; }
		} );

		return terms;
	} );


	// add retailers
	$( 'button.wc-product-retailers-add-retailer' ).click( function() {

		// get retailers to add along with the existing retailers
		var $retailerSearch = $( 'select#wc-product-retailers-retailer-search' ),
			retailersToAdd = $retailerSearch.val();

		// if there are retailers to add...
		if ( retailersToAdd ) {

			// iterate through the retailers to add
			$( retailersToAdd ).each( function() {

				// add a retailer if it doesn't already exist in the list
				if ( ! $( 'input#wc-product-retailer-product-url-' + this ).length ) {

					// lookup name and product URL
					$.get( ajaxurl,{ action : 'product_retailers_search_retailers', security : product_retailers_admin_params.search_retailers_nonce, term : this }, function( retailer ) {

						// retailer found, append to the table
						if ( retailer.length ) {
							$( 'table.wc-product-retailers > tbody' ).append( '<tr class="wc-product-retailer"><td class="check-column"><input type="checkbox" name="select" /><input type="hidden" name="_product_retailer_id[' + $('table.wc-product-retailers tbody tr').length + ']" value="' + retailer[0].id + '" /></td><td class="wc-product-retailer-name">' + retailer[0].name + '</td>\<td class="wc-product-retailer-product-url"><input type="text" data-post-id="' + retailer[0].id + '" id="wc-product-retailer-product-url-' + retailer[0].id + '" name="_product_retailer_product_url[' + $('table.wc-product-retailers tbody tr').length + ']" value="' + retailer[0].product_url + '" /></td>\
<td class="wc-product-retailer-product-price"><input type="text" data-post-id="' + retailer[0].id + '" id="wc-product-retailer-product-price-' + retailer[0].id + '" name="_product_retailer_product_price[' + $('table.wc-product-retailers tbody tr').length + ']" /></td>\<td class="wc-product-retailer-product-location">' + retailer[0].product_location + '</td>\<td class="wc-product-retailer-product-logo">' + retailer[0].product_logo + '</td>\
</tr>' );

							// remove the added retailer from the search form
							$retailerSearch.find( 'option' ).remove();

							// update the chosen
							$retailerSearch.trigger( "liszt:updated" ).trigger( 'chosen:updated' );
						}
					} );
				}
			} );

			productRetailersRowIndexes();
		}
	} );


	// delete selected retailers
	$( 'button.wc-product-retailers-delete-retailer' ).click( function() {
		$( 'table.wc-product-retailers td.check-column input:checked' ).each( function() {
			$( this ).closest( 'tr.wc-product-retailer' ).fadeOut( '400', function() {
				$( this ).remove();
			} );
		} );

		productRetailersRowIndexes();
	} );


	// retailers ordering
	$( 'table.wc-product-retailers tbody' ).sortable( {
		items  : 'tr',
		cursor : 'move',
		axis   : 'y',
		handle : 'td',
		scrollSensitivity : 40,
		start : function( event, ui ){
			ui.item.css( 'background-color','#f6f6f6' );
		},
		stop : function( event, ui ) {
			ui.item.removeAttr( 'style' );
			productRetailersRowIndexes();
		}
	} );


	/**
	 * Re-index keys
	 */
	function productRetailersRowIndexes() {
		var loop = 0;
		$( 'table.wc-product-retailers tbody tr' ).each( function( index, row ) {
			$( 'input', row ).each( function( i, el ) {

				var t = jQuery( el );
				t.attr( 'name', t.attr( 'name' ).replace(/\[([^[]*)\]/, "[" + loop + "]" ) );

			} );
			loop++;
		} );
	}
} );

