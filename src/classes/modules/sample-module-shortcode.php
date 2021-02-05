<?php

namespace __plugin_namespace__;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Sample_Module
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
 * @package     My_Testing
 * @since       0.1
 *
 */
class Sample_Module_Shortcode extends Module {

	/**
	 * Class constructor
	 *
	 * The construct is setup so that you can auto run code when the switch is active or run background code
	 * when the switch is in-active
	 *
	 * @since 0.1
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
	 * @since 0.1
	 */
	function run() {
		// Render a list  of healthy foods
		add_shortcode( 'dump_settings', array( $this, 'healthy_food_callback' ) );
	}

	/**
	 * Render a list of healthy with reference to an external food list class
	 *
	 * @return string
	 * @since 0.1
	 *
	 */
	function healthy_food_callback() {

		$settings = $this->get_settings();

		foreach ( $settings as &$setting ) {
			if ( isset( $setting['type'] ) ) {
				$setting['value'] = get_option( __CLASS__ . '>' . $setting['name'], '' );
			}
		}

		ob_start();

		return ob_get_clean();
	}

	/*
	 * Checks if the class is dependant on another variable, function, plugin and/or theme
	 *
	 * If the dependency does not exists then the on/off switch on the module is replace with a message.
	 *
	 * @since 0.1
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
	 *
	 * @since 0.1     *
	 * @see   My_Testing/AdminMenu::get_class_details()
	 *
	 */
	function set_module_details() {

		/*
		 * Title of the module.
		 * Required
		 *
		 * @type string
		 */
		$this->title = esc_html__( 'Dump module settings', '{plugin_text_domain}' );

		/*
		 * Link to class's associated knowledge base article
		 * Not required
		 *
		 * @type string
		 */
		$this->link = 'http://www.example.com/sample-module';

		/*
		 * Description should be adapted from the post_content in the knowledge base article. Exclude any technical
		 * documentation including: shortcodes, attributes, use cases, images, and/or steps. (approx. 25-200 characters)
		 */
		$this->description = esc_html__( 'Creates a [dump_settings] shortcode that dumps all the settings with saved values.', '{plugin_text_domain}' );

		/*
		 * Settings define the inputs that are added to the settings modal pop.
		 *
		 * @type object
		 */
		$this->settings = (object) [
			[
				'type'  => 'text',
				'label' => esc_html__( 'The most healthy food.', '{plugin_text_domain}' ),
				'name'  => 'most-healthy-food-text'
			],

			[
				'type'  => 'helper',
				'label' => esc_html__( 'This food is what 92% of someone\'s diet should consist of.', '{plugin_text_domain}' ),
				'name'  => 'healthy-food-label-helper'
			],

			[
				'type'  => 'checkbox',
				'label' => esc_html__( 'AutoStart', '{plugin_text_domain}' ),
				'name'  => 'auto-start-diet'
			],
			[
				'type'    => 'select',
				'label'   => esc_html__( 'Default Diet', '{plugin_text_domain}' ),
				'name'    => 'default-diet',
				'options' => [
					'carnist'       => 'Carnist',
					'vegan'         => 'Vegan',
					'paleo'         => 'Paleo',
					'fruititarnian' => 'Fruititarnian',
					'sad'           => 'Standard American Diet (SAD)'
				]
			]
		];
	}
}


