<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Product Retailers Admin List Screen
 *
 * @since 1.0
 */
class WC_Product_Retailers_List {

	/**
	 * @var string used to store the meta sql 'where' clause for modification
	 * during search to include post meta
	 */
	private $meta_sql_where_clause;

	/**
	 * Initialize and setup the admin retailer list screen
	 *
	 * @since 1.0
	 */
	public function  __construct() {

		add_filter( 'bulk_actions-edit-product_retailer', array( $this, 'retailers_bulk_actions' ) );

		add_filter( 'views_edit-product_retailer', array( $this, 'retailers_views' ) );

		add_filter( 'manage_edit-product_retailer_columns', array( $this, 'retailers_column_headers' ) );

		add_action( 'manage_product_retailer_posts_custom_column', array( $this, 'retailers_column_content' ) );

		// add _product_retailer_default_url into the post search meta query
		add_filter( 'parse_query',  array( $this, 'retailers_search_meta_fields' ) );

		// pull the _product_retailer_default_url clause out of the post search meta query 'where' clause
		add_filter( 'get_meta_sql', array( $this, 'get_meta_sql' ), 10, 6 );

		// add the _product_retailer_default_url clause
		add_filter( 'posts_search', array( $this, 'retailers_search' ), 10, 2 );
	}


	/**
	 * Adds the _product_retailer_default_url meta field into the search query
	 *
	 * @since 1.0
	 * @param WP_Query $wp_query the query object
	 * @return WP_Query the query object
	 */
	public function retailers_search_meta_fields( $wp_query ) {
		global $pagenow, $wpdb;

		if ( 'edit.php' != $pagenow || empty( $wp_query->query_vars['s'] ) || 'product_retailer' != $wp_query->query_vars['post_type'] ) {
			return $wp_query;
		}

		$wp_query->query_vars['meta_query'][] =
			array(
				'key'     => '_product_retailer_default_url',
				'value'   => $wp_query->query_vars['s'],
				'compare' => 'LIKE',
			);

		return $wp_query;
	}


	/**
	 * Removes the _product_retailer_default_url 'where' clause, while leaving
	 * it in the 'from' clause.  This allows us to include the meta table in
	 * the search query, while not limiting ourselves to the matching rows.
	 * We'll inject the 'where' clause that we remove into the query search
	 * clause with an 'OR' so we can search over the union of post name, content
	 * and meta.
	 *
	 * @since 1.0
	 * @param array $clauses the meta clauses 'join' and 'where'
	 * @param array $meta_query the meta_query
	 * @param string $type the type, ie 'post'
	 * @param string $primary_table the primary table, ie 'wp_posts'
	 * @param string $primary_id_column the primary id column, ie 'ID'
	 * @param WP_Query $wp_query the current wp query object
	 * @return array the meta clauses 'join' and 'where'
	 */
	public function get_meta_sql( $clauses, $meta_query, $type, $primary_table, $primary_id_column, $wp_query ) {

		global $pagenow;

		// if searching for product retailers
		if ( 'edit.php' != $pagenow || empty( $wp_query->query_vars['s'] ) || 'product_retailer' != $wp_query->query_vars['post_type'] ) {
			return $clauses;
		}

		// initialize the where clause variable (important)
		$this->meta_sql_where_clause = null;

		// determine the relation
		if ( isset( $meta_query['relation'] ) && 'OR' == strtoupper( $meta_query['relation'] ) ) {
			$relation = 'OR';
		} else {
			$relation = 'AND';
		}

		// get the individual meta queries
		$clauses_where = explode( "\n", $clauses['where'] );

		$new_clauses_where = array();

		foreach ( $clauses_where as $index => $clause ) {

			if ( false !== strpos( $clause, "'_product_retailer_default_url'" ) ) {
				// found the clause we're looking for

				// this was the first clause, pull the leading ' AND ('
				if ( 0 == $index ) {
					$clause = substr( $clause, 6 );
				}

				// this was the last clause
				if ( $index == count( $clauses_where ) - 1 ) {
					// trim off the trailing ')'
					$clause = rtrim( $clause );
					$clause = substr( $clause, 0, -1 );

					// fix the new last clause, if there is one, by adding the required ' )'
					if ( count( $new_clauses_where ) > 1 ) {
						$new_clauses_where[ count( $new_clauses_where ) - 1 ] .= ' )';
					}
				}

				// pull of the leading AND/OR if needed
				if ( 0 === strpos( $clause, $relation ) ) {
					$clause = substr( $clause, strlen( $relation ) );
				}

				$this->meta_sql_where_clause = $clause;

			} else {

				// some other clause we don't care about
				if ( 0 == count( $new_clauses_where ) && $this->meta_sql_where_clause ) {
					// promote this clause to the new first clause if we removed the first clause
					$clause = ' AND ( ' . $clause;
				}
				$new_clauses_where[] = $clause;
			}

		}

		// create the new set of where clauses
		$clauses['where'] = implode( "\n ", $new_clauses_where );

		return $clauses;
	}


	/**
	 * Modify the query search clause to include our product retailers meta
	 * where clause if we have one
	 *
	 * @since 1.0
	 * @param string $search_clause the SQL search clause
	 * @param QP_Query $wp_query the query object
	 * @return string the SQL search clause
	 */
	public function retailers_search( $search_clause, $wp_query ) {

		global $pagenow, $wpdb;

		// if searching for product retailers
		if ( 'edit.php' != $pagenow || empty( $wp_query->query_vars['s'] ) || 'product_retailer' != $wp_query->query_vars['post_type'] ) {
			return $search_clause;
		}

		if ( ! empty( $wp_query->meta_query->queries ) ) {

			// gather the required clause from the meta query
			$wp_query->meta_query->get_sql( 'post', $wpdb->posts, 'ID', $wp_query );

			if ( $search_clause && $this->meta_sql_where_clause ) {

				// trim any trailing whitespace and then two closing paren
				$search_clause = rtrim( $search_clause );
				$search_clause = substr( $search_clause, 0, -2 );
				$search_clause .= ' OR ' . $this->meta_sql_where_clause . ')) ';

			}
		}

		return $search_clause;
	}


	/**
	 * Remove the bulk edit action for product retailers, it really isn't useful
	 *
	 * @since 1.0
	 * @param array $actions associative array of action identifier to name
	 *
	 * @return array associative array of action identifier to name
	 */
	public function retailers_bulk_actions( $actions ) {

		unset( $actions['edit'] );

		return $actions;
	}


	/**
	 * Modify the 'views' links, ie All (3) | Publish (1) | Draft (1) | Private (2) | Trash (3)
	 * shown above the retailers list table, to hide the publish/private/draft states,
	 * which are not important and confusing for retailer objects.
	 *
	 * @since 1.0
	 * @param array $views associative-array of view state name to link
	 *
	 * @return array associative array of view state name to link
	 */
	public function retailers_views( $views ) {

		// these views are not important distinctions for product retailers
		unset( $views['publish'], $views['private'], $views['draft'] );

		return $views;
	}


	/**
	 * Columns for view Retailers page
	 *
	 * @since 1.0
	 * @param array $columns associative-array of column identifier to header names
	 *
	 * @return array associative-array of column identifier to header names for the retailers page
	 */
	public function retailers_column_headers( $columns ){

		$columns = array();

		$columns['cb']          = '<input type="checkbox" />';
		$columns['name']        = __( 'Name', WC_Product_Retailers::TEXT_DOMAIN );
		$columns['default_url'] = __( 'Default URL', WC_Product_Retailers::TEXT_DOMAIN );

		return $columns;
	}


	/**
	 * Custom Column values for Retailers page
	 *
	 * @since 1.0
	 * @param string $column column identifier
	 */
	function retailers_column_content( $column ) {
		global $post;

		$retailer = new WC_Retailer( $post );

		switch ( $column ) {

			case 'name':
				$edit_link = get_edit_post_link( $post->ID );
				$title = _draft_or_post_title();

				$post_type_object = get_post_type_object( $post->post_type );
				$can_edit_post = current_user_can( $post_type_object->cap->edit_post, $post->ID );

				echo '<strong><a class="row-title" href="' . $edit_link . '">' . $title . '</a>';

				echo '</strong>';

				// Get actions
				$actions = array();

				$actions['id'] = 'ID: ' . $post->ID;

				if ( current_user_can( $post_type_object->cap->delete_post, $post->ID ) ) {
					if ( 'trash' == $post->post_status ) {
						$actions['untrash'] = "<a title='" . esc_attr( __( 'Restore this item from the Trash', WC_Product_Retailers::TEXT_DOMAIN ) ) . "' href='" . wp_nonce_url( admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=untrash', $post->ID ) ), 'untrash-' . $post->post_type . '_' . $post->ID ) . "'>" . __( 'Restore', WC_Product_Retailers::TEXT_DOMAIN ) . "</a>";
					} elseif ( EMPTY_TRASH_DAYS ) {
						$actions['trash'] = "<a class='submitdelete' title='" . esc_attr( __( 'Move this item to the Trash', WC_Product_Retailers::TEXT_DOMAIN ) ) . "' href='" . get_delete_post_link( $post->ID ) . "'>" . __( 'Trash', WC_Product_Retailers::TEXT_DOMAIN ) . "</a>";
					}
					if ( 'trash' == $post->post_status || ! EMPTY_TRASH_DAYS ) {
						$actions['delete'] = "<a class='submitdelete' title='" . esc_attr( __( 'Delete this item permanently', WC_Product_Retailers::TEXT_DOMAIN ) ) . "' href='" . get_delete_post_link( $post->ID, '', true ) . "'>" . __( 'Delete Permanently', WC_Product_Retailers::TEXT_DOMAIN ) . "</a>";
					}
				}

				$actions = apply_filters( 'post_row_actions', $actions, $post );

				echo '<div class="row-actions">';

				$i = 0;
				$action_count = count( $actions );

				foreach ( $actions as $action => $link ) {
					( $action_count - 1 == $i ) ? $sep = '' : $sep = ' | ';
					echo '<span class="' . $action . '">' . $link . $sep . '</span>';
					$i++;
				}
				echo '</div>';
			break;

			case "default_url":
				echo $retailer->get_url();
			break;
		}
	}

}
