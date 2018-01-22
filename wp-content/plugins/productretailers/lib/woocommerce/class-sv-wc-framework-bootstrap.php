<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'EM_WC_Framework_Bootstrap' ) ) :

class EM_WC_Framework_Bootstrap {


	/** @var EM_WC_Framework_Bootstrap The single instance of the class */
	protected static $instance = null;

	/** @var array registered framework plugins */
	protected $registered_plugins = array();

	/** @var array of incompatible frameworked plugins */
	protected $incompatible_plugins = array();


	/**
	 * Hidden constructor
	 *
	 * @since 2.0
	 */
	private function __construct() {

		// load framework plugins once all plugins are loaded
		add_action( 'plugins_loaded', array( $this, 'load_framework_plugins' ) );
	}


	/**
	 * Instantiate the class singleton
	 *
	 * @since 2.0
	 * @return EM_WC_Framework_Bootstrap singleton instance
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Register a frameworked plugin
	 *
	 * @since 2.0
	 * @param string $version the framework version
	 * @param string $plugin_name the plugin name
	 * @param string $path the plugin path
	 * @param callable $callback function to initialize the plugin
	 * @param array $args optional plugin arguments.  Possible arguments: 'is_payment_gateway', 'backwards_compatible'
	 */
	public function register_plugin( $version, $plugin_name, $path, $callback, $args = array() ) {
		$this->registered_plugins[] = array( 'version' => $version, 'plugin_name' => $plugin_name, 'path' => $path, 'callback' => $callback, 'args' => $args );
	}


	/**
	 * Loads all registered framework plugins, first initializing the plugin
	 * framework by loading the highest versioned one.
	 *
	 * @since 2.0
	 */
	public function load_framework_plugins() {

		// first sort the registered plugins by framework version
		usort( $this->registered_plugins, array( $this, 'compare_frameworks' ) );

		$loaded_framework = null;

		foreach ( $this->registered_plugins as $plugin ) {

			// load the first found (highest versioned) plugin framework class
			if ( ! class_exists( 'EM_WC_Plugin' ) ) {
				require_once( $this->get_plugin_path( $plugin['path'] ) . '/lib/woocommerce/class-sv-wc-plugin.php' );
				$loaded_framework = $plugin;
			}

			// if the loaded version of the framework has a backwards compatibility requirement
			//  which is not met by the current plugin add an admin notice and move on without
			//  loading the plugin
			if ( isset( $loaded_framework['args']['backwards_compatible'] ) &&
				$loaded_framework['args']['backwards_compatible'] &&
				version_compare( $loaded_framework['args']['backwards_compatible'], $plugin['version'], '>' ) ) {

				if ( is_admin() && ! defined( 'DOING_AJAX' ) && ! has_action( 'admin_notices', array( $this, 'render_update_plugin_notice' ) ) ) {
					// render any admin notices
					add_action( 'admin_notices', array( $this, 'render_update_plugin_notice' ) );
				}

				$this->incompatible_plugins[] = $plugin;

				// on to the next plugin
				continue;
			}

			// load the first found (highest versioned) payment gateway framework class, as needed
			if ( isset( $plugin['args']['is_payment_gateway'] ) && ! class_exists( 'EM_WC_Payment_Gateway' ) ) {
				require_once( $this->get_plugin_path( $plugin['path'] ) . '/lib/emediaexperts/woocommerce/payment-gateway/class-sv-wc-payment-gateway-plugin.php' );
			}

			// initialize the plugin
			$plugin['callback']();

		}

		// frameworked plugins can hook onto this action rather than 'plugins_loaded'/'woocommerce_loaded' when need be
		do_action( 'sv_wc_framework_plugins_loaded' );
	}


	/** Admin methods ******************************************************/


	/**
	 * Render a notice to update any plugins with incompatible framework
	 * versions
	 *
	 * @since 2.0
	 */
	public function render_update_plugin_notice() {

		$plugin_names = array();

		foreach ( $this->incompatible_plugins as $plugin ) {
			$plugin_names[] = $plugin['plugin_name'];
		}

		if ( ! empty( $plugin_names ) ) {

			// no localization
			echo sprintf(
				'<div class="error"><p>%s</p><ul><li>%s</ul></ul></div>',
				count( $plugin_names ) > 1 ? 'The following plugins must be updated in order to function properly:' : 'The following plugin must be updated in order to function properly:',
				implode( '</li><li>', $plugin_names ) );
		}
	}


	/** Helper methods ******************************************************/


	/**
	 * Compare the two framework versions.  Returns -1 if $a is less than $b, 0 if
	 * they're equal, and 1 if $a is greater than $b
	 *
	 * @since 2.0
	 * @param array $a first registered plugin to compare
	 * @param array $b second registered plugin to compare
	 * @return int -1 if $a is less than $b, 0 if they're equal, and 1 if $a is greater than $b
	 */
	public function compare_frameworks( $a, $b ) {
		// compare versions without the operator argument, so we get a -1, 0 or 1 result
		return version_compare( $b['version'], $a['version'] );
	}


	/**
	 * Returns the plugin path for the given $file
	 *
	 * @since 2.0
	 * @param string $file the file
	 * @return string plugin path
	 */
	public function get_plugin_path( $file ) {
		return untrailingslashit( plugin_dir_path( $file ) );
	}

}


// instantiate the class
EM_WC_Framework_Bootstrap::instance();

endif;
