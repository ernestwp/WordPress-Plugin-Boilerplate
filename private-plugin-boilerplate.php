<?php
/**
 * Plugin Name
 *
 * @package           __plugin_namespace__
 * @author            {plugin_author}
 * @copyright         {copyright_year} {plugin_author}
 * @license           {plugin_license}
 *
 * @wordpress-plugin
 * Plugin Name:       {plugin_name}
 * Plugin URI:        {plugin_uri}
 * Description:       {plugin_description}
 * Version:           {plugin_version}
 * Requires at least: {requires_at_least}
 * Requires PHP:      {requires_php}
 * Author:            {plugin_author}
 * Author URI:        {plugin_author_uri}
 * Text Domain:       {plugin_text_domain}
 * License:           {plugin_license}
 * License URI:       {license_uri}
 */

/*
 * Main plugin file --
 */

namespace __plugin_namespace__;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * This class initiates the plugin load sequence and sets general plugin variables
 *
 * @package __plugin_namespace__
 */
class Initialize_Plugin {

	/**
	 * The plugin name
	 *
	 * @since    {plugin_version}
	 * @access   public
	 * @var      string
	 */
	const PLUGIN_NAME = '{plugin_name}';

	/**
	 * The plugin name acronym
	 *
	 * @since    {plugin_version}
	 * @access   public
	 * @var      string
	 */
	const PLUGIN_PREFIX = 'plugin_prefix';

	/**
	 * Min PHP Version
	 *
	 * @since    {plugin_version}
	 * @access   public
	 * @var      string
	 */
	const MIN_PHP_VERSION = '{requires_php}';

	/**
	 * The plugin version number
	 *
	 * @since    {plugin_version}
	 * @access   public
	 * @var      string
	 */
	const PLUGIN_VERSION = '1.1';

	/**
	 * The full path and filename
	 *
	 * @since    {plugin_version}
	 * @access   public
	 * @var      string
	 */
	const MAIN_FILE = __FILE__;

	/**
	 * The instance of the class
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      Object
	 */
	private static $instance = null;

	/**
	 * Creates singleton instance of class
	 *
	 * Singleton is needed here the prevent the multiple class initializations
	 *
	 * @since {plugin_version}
	 * @return Initialize_Plugin $instance The Initialize_Plugin Class
	 *
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * class constructor
	 */
	private function __construct() {

		// Load Utilities
		$this->initialize_utilities();

		// Load Configuration
		$this->initialize_config();

		// Load the plugin files
		$this->boot_plugin();

	}

	/**
	 * Initialize Static singleton class that has shared function and variables that can be used anywhere in WP
	 *
	 * @since {plugin_version}
	 */
	private function initialize_utilities() {

		include_once( dirname( __FILE__ ) . '/src/utilities/utilities.php' );
		Utilities::set_date_time_format();

	}

	/**
	 * Initialize Static singleton class that configures all constants, utilities variables and handles activation/deactivation
	 *
	 * @since {plugin_version}
	 */
	private function initialize_config() {

		include_once( dirname( __FILE__ ) . '/src/utilities/class-config.php' );

		$config_instance = Config::get_instance();

		$config_instance->configure_plugin_before_boot(
			self::PLUGIN_NAME,
			self::PLUGIN_PREFIX,
			self::PLUGIN_VERSION,
			self::MAIN_FILE
		);

	}

	/**
	 * Initialize Static singleton class auto loads all the files needed for the plugin to work
	 *
	 * @since {plugin_version}
	 */
	private function boot_plugin() {

		// Only include Module_interface, do not initialize is ... interfaces cannot be initialized
		add_filter( 'Skip_class_initialization', array( $this, 'add_skipped_classes' ), 10, 1 );

		include_once( dirname( __FILE__ ) . '/src/utilities/class-autoloader.php' );
		Autoloader::get_instance();

		do_action( Utilities::get_prefix() . '_plugin_loaded' );

	}

	/**
	 * Add Classes that need to be included automatically but not initialized
	 *
	 * @param array $skipped_classes Collection of classes that are being skipped over for initialization (new Class)
	 *
	 * @return array
	 */
	public function add_skipped_classes( $skipped_classes ) {
		return $skipped_classes;
	}
}

// Let's run it
Initialize_Plugin::get_instance();