<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Product Retailer
 *
 * @since 1.0
 */
class WC_Retailer {


	/** @var string the retailer name */
	private $name;

	/** @var string the retailer price (defined on a per-product basis) */
	private $price;
	
	private $location;
	
	private $logo;

	/** @var string the retailer url */
	private $url;

	/** @var object the post object */
	private $post;


	/**
	 * Construct and initialize a product retailer
	 *
	 * @since 1.0
	 * @param int|object retailer ID or post object
	 * @throws Exception if the retailer identified by $id doesn't exist
	 */
	public function __construct( $id ) {

		// load the post object if we don't already have it
		if ( is_object( $id ) ) {
			$post = $id;
		} else {
			$post = get_post( $id );
			if ( ! $post ) throw new Exception( "Retailer does not exist" );
		}

		$this->post = $post;

		$this->name = $post->post_title;

		// get the default url, if there is one
		$this->url = get_post_meta( $post->ID, '_product_retailer_default_url', true );
		$this->logo = get_the_post_thumbnail($post->ID, array( 100, 100) );
		$this->location = get_post_meta( $post->ID, '_product_retailer_default_location', true );
	}


	/**
	 * Returns true if this retailer is available for display on the frontend
	 *
	 * @since 1.0
	 * @param boolean $is_admin whether this check is from within the admin where
	 *        we don't care about the url
	 * @return boolean true if this retailer is available
	 */
	public function is_available( $is_admin = false ) {

		$url  = $this->get_url();
		$name = $this->get_name();

		return ( $is_admin || ! empty( $url ) ) && ! empty( $name ) && 'publish' == $this->post->post_status;
	}


	/**
	 * Returns the retailer id
	 *
	 * @since 1.0
	 * @return int retailer post id
	 */
	public function get_id() {
		return $this->post->ID;
	}


	/**
	 * Returns the retailer name
	 *
	 * @since 1.0
	 * @return string the retailer name
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Returns the price set for retailer (defined at the per-product level)
	 *
	 * @since 1.1
	 * @return string the retailer price
	 */
	 public function get_price() {
		return $this->price;
	}

    public function get_location() {
		return $this->location;
	}
	
	 public function get_logo() {
		return $this->logo;
	}
	
	/**
	 * Sets the retailer price
	 *
	 * @since 1.1
	 * @param string $price the price to set
	 */
	public function set_price( $price ) {
		$this->price = $price;
	}
	
	public function set_logo( $logo ) {
		$this->logo = $logo;
	}
	
	public function set_location( $location ) {
		$this->location = $location;
	}

	/**
	 * Returns the retailer url
	 *
	 * @since 1.0
	 * @return string the retailer url
	 */
	public function get_url() {

		// add http:// if missing
		if ( $this->url && null === parse_url( $this->url, PHP_URL_SCHEME ) ) {
			$this->url = 'http://' . $this->url;
		}

		return $this->url;
	}


	/**
	 * Sets the retailer url
	 *
	 * @since 1.0
	 * @param string $url the url to set
	 */
	public function set_url( $url ) {
		$this->url = $url;
	}


	/**
	 * Persist this retailer to the DB
	 *
	 * @since 1.0
	 */
	public function persist() {
		update_post_meta( $this->post->ID, '_product_retailer_default_url',  $this->get_url() );
		update_post_meta( $this->post->ID, '_product_retailer_default_logo',  $this->get_logo() );
		update_post_meta( $this->post->ID, '_product_retailer_default_location',  $this->get_location() );
	}


} // end \WC_Retailer class
