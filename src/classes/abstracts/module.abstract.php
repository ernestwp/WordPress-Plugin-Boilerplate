<?php

namespace __plugin_namespace__;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract Module Class
 *
 * Extended by each module using the same pattern.
 *
 * @version  {plugin_version}
 * @package  __plugin_namespace__
 */
abstract class Module implements Module_i {

	/**
	 * Title of the module
	 *
	 * @since {plugin_version}
	 * @var null | string
	 */
	public $title = null;

	/**
	 * Link to class's associated knowledge base article
	 *
	 * @since {plugin_version}
	 * @var null | string
	 */
	public $link = null;

	/**
	 * Description of the module
	 *
	 * @since {plugin_version}
	 * @var null | string
	 */
	public $description = null;

	/**
	 * If the there are dependencies, do they exist.
	 *
	 * @since 0.1
	 * @var null | bool
	 */
	public $dependants_exist = null;

	/**
	 * Stores if settings switch in wp admin is active or in-active
	 *
	 * @since {plugin_version}
	 * @var null | bool
	 */
	public $is_active = null;

	/**
	 * Module settings based on user saved settings
	 *
	 * @since {plugin_version}
	 * @var null | object
	 */
	public $settings = null;


	/**
	 * Default constructor.
	 */
	public function __construct() {

		// Is the module set to active on the admin settings page
		$this->set_active_or_not();

		// Get and set the settings defined on the admin settings page
		$this->set_module_details();

		if ( $this->is_active() ) {

			// Run all go that needs to run automatically
			// 99% of the time we want to load hooks/filters/function after all plugins have been loaded
			add_action( 'plugins_loaded', array( $this, 'run' ) );
		}
	}

	public function set_active_or_not() {

		// The the class name that is extending this abstract class
		$module_child_class_name = get_class( $this );
		$module                  = get_option( 'switch-' . $module_child_class_name, 'off' );
		$this->is_active         = ( 'on' === $module ) ? true : false;
	}

	public function is_active() {
		return $this->is_active;
	}

	public function get_settings() {
		return $this->settings;
	}

	abstract function set_module_details();

	abstract function dependants_exist();

	abstract function run();
}