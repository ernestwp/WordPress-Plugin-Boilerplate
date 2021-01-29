<?php

namespace __plugin_namespace__;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Sample_Module_Block
 *
 * Example of a settings module class that can be used to auto create settings and on/off switch.
 *
 * @see         src/boot.php
 *
 * A module is comprised of a title, description, link, settings, icon, and on/off switch.
 *
 * Changes that need to be made:
 * @see         get_module_details()
 * 1. Update class name and file name. File name must match class name for auto loader to find and include the file dynamically.
 * @see         src/boot.php -> spl_autoload_register( array( __CLASS__, 'auto_loader' ) );
 *    The class name 'should' match the post_slug of it's associated knowledge base article but this is not always possible.
 *  - ex. Sample_Class(class name) => sample-class.php(file name) => /knowledge-base/sample-class/(post_slug)
 *  - class name is camel cased with _ between
 *  - file name is lower cased with - between
 * 2. Update class's detail knowledge base article link.
 *  - @link https://www.example.com/knowledge-base/sample-class
 * 3. Update class's detail title
 * 4. Update class's module short description
 * 5. Update class's settings
 * 6. Update class's dependencies
 * 7. Update class's icon
 *
 * @package     __plugin_namespace__
 * @since       {plugin_version}
 *
 */
class Sample_Module_Block extends Module {

	/**
	 * Class constructor
	 *
	 * The construct is setup so that you can auto run code when the switch is active or run background code
	 * when the switch is in-active
	 *
	 * @since {plugin_version}
	 */
	public function __construct() {
		parent::__construct();
	}

	/*
	 * Initialize actions, filters, and/or custom functions
	 *
	 * This function run after settings have been loading and dependencies have been checked
	 *
	 * Most, if not all, functions should be run after plugins have been loaded. This will give access to modify and/or
	 * override functions for any external plugin or theme. We can also check if a plugin or theme exists before
	 * executing any action, filters, and/or extending classes from it.
	 *
	 * @since {plugin_version}
	 */
	function run() {
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "blocks" . DIRECTORY_SEPARATOR . "blocks.php";
		new blocks();
	}

	/*
	 * Checks if the class is dependant on another variable, function, plugin and/or theme
	 *
	 * If the dependency does not exists then the on/off switch on the module is replace with a message.
	 *
	 * @since {plugin_version}
	 *
	 * @return boolean || string
	 */
	function dependants_exist() {

		/*
		 * When checking for dependencies you should first check if the plugin or theme has a 'loaded hook' like gravity
		 * form below. If they do not offer an action to hook. Look for the first obvious constant,
		 * global function or global variable.
		 */

		//ex. Checks if gravity forms plugin exists
//		if ( ! has_action( 'gform_loaded' ) ) {
//			$this->dependants_exist = 'Plugin: Gravity Forms';
//		}

		// Return true dependency is available
		$this->dependants_exist = true;

	}

	/**
	 * Detailed description of module
	 *
	 * This information is only loaded in the admin settings page to create a module which includes an on/off switch
	 * and settings modal pop up that populates module options in the WP DB. The details are retrieve by creating a
	 * reflection class(http://php.net/manual/en/class.reflectionclass.php). The class does not need to be initialized
	 * to get the details.
	 * @return array $class_details
	 * @since {plugin_version}
	 *
	 * @see   __plugin_namespace__/AdminMenu::get_class_details()
	 *
	 */
	function set_module_details() {

		/*
		 * Title of the module.
		 * Required
		 *
		 * @type string
		 */
		$this->title = esc_html__( 'Gutenburg block', '{plugin_text_domain}' );

		/*
		 * Link to class's associated knowledge base article
		 * Not required
		 *
		 * @type string
		 */
		$this->link = 'http://www.example.com/gutenburg-block';

		/*
		 * Description should be adapted from the post_content in the knowledge base article. Exclude any technical
		 * documentation including: shortcodes, attributes, use cases, images, and/or steps. (approx. 25-200 characters)
		 */
		$this->description = esc_html__( 'Create a post list gutenburg block that can be an anchor or a button.', '{plugin_text_domain}' );

		/*
		 * Settings define the inputs that are added to the settings modal pop.
		 *
		 * @type object
		 */
		$this->settings = (object) [];
	}
}


