<?php

namespace __plugin_namespace__;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * This class is used to run any configurations before the plugin is initialized
 *
 * @package    __plugin_namespace__
 * @author     {plugin_author}
 */
class Config {


	/**
	 * The instance of the class
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      Boot
	 */
	private static $instance = null;

	/**
	 * Creates singleton instance of class
	 *
	 * @return Config $instance The Config Class
	 * @since {plugin_version}
	 *
	 */
	public static function get_instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize the class and setup its properties.
	 *
	 * @param string $plugin_name The name of the plugin
	 * @param string $prefix      The variable used to prefix filters and actions
	 * @param string $version     The version of this plugin
	 * @param string $file        The main plugin file __FILE__
	 *
	 * @since    {plugin_version}
	 *
	 */
	public function configure_plugin_before_boot( $plugin_name, $prefix, $version, $file ) {

		$this->define_constants( $plugin_name, $prefix, $version, $file );

		do_action( Utilities::get_prefix() . '_define_constants_after' );

		register_activation_hook( Utilities::get_plugin_file(), array( $this, 'activation' ) );

		register_deactivation_hook( Utilities::get_plugin_file(), array( $this, 'deactivation' ) );

		do_action( Utilities::get_prefix() . '_config_setup_after' );

	}

	/**
	 *
	 * This action is documented in includes/class-plugin-name-deactivator.php
	 *
	 * @param string $plugin_name The name of the plugin
	 * @param string $prefix      Variable used to prefix filters and actions
	 * @param string $version     The version of this plugin.
	 * @param string $plugin_file The main plugin file __FILE__
	 *
	 * @since    {plugin_version}
	 * @access   private
	 *
	 */
	private function define_constants( $plugin_name, $prefix, $version, $plugin_file ) {


		// Set and define version
		if ( ! defined( strtoupper( $prefix ) . '_PLUGIN_NAME' ) ) {
			define( strtoupper( $prefix ) . '_PLUGIN_NAME', $plugin_name );
			Utilities::set_plugin_name( $plugin_name );
		}

		// Set and define version
		if ( ! defined( strtoupper( $prefix ) . '_VERSION' ) ) {
			define( strtoupper( $prefix ) . '_VERSION', $version );
			Utilities::set_version( $version );
		}

		// Set and define prefix
		if ( ! defined( strtoupper( $prefix ) . '_PREFIX' ) ) {
			define( strtoupper( $prefix ) . '_PREFIX', $prefix );
			Utilities::set_prefix( $prefix );
		}

		// Set and define the main plugin file path
		if ( ! defined( $prefix . '_FILE' ) ) {
			define( strtoupper( $prefix ) . '_FILE', $plugin_file );
			Utilities::set_plugin_file( $plugin_file );
		}

		// Set and define the server initialization ( Server time and not to be confused with WP current_time() )
		if ( ! defined( $prefix . '_SERVER_INITIALIZATION' ) ) {
			$time = time();
			define( strtoupper( $prefix ) . '_SERVER_INITIALIZATION', $time );
			Utilities::set_plugin_initialization( $time );
		}
	}


	/**
	 * The code that runs during plugin activation.
	 * @since    {plugin_version}
	 */
	function activation() {

		do_action( Utilities::get_prefix() . '_activation_before' );

		do_action( Utilities::get_prefix() . '_activation_after' );

	}


	/**
	 * The code that runs during plugin deactivation.
	 * @since    {plugin_version}
	 */
	function deactivation() {

		do_action( Utilities::get_prefix() . '_deactivation_before' );

		do_action( Utilities::get_prefix() . '_deactivation_after' );

	}
}