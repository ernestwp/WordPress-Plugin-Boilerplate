<?php

namespace __plugin_namespace__;

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Module_Admin_Menu {

	/*
	 * Setting Page Title
	 */
	public $settings_page_slug;
	/*
	 * All the information about a module
	 */
	public $modules_info = array();

	/*
	 * All the information about a module
	 */
	public $modules_categorized = false;

	/**
	 * The Rest-API route
	 *
	 * The v2 means we are using version 2 of the wp rest api
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      string
	 */
	private $root_path = null;

	/**
	 * class constructor
	 */
	function __construct() {

		/*
		 * If WP DEBUG is not on do NOT return any php warning, notices, and/or fatal errors.
		 * Well If it is a fatal error then this return is FUBAR anyway...
		 * We do this because some badly configured servers will return notices and warnings switch get prepended or appended to the rest response.
		 */
		if ( defined( 'WP_DEBUG' ) ) {
			if ( false === WP_DEBUG ) {
				error_reporting( 0 );
			}
		}

		// Define the root path for the rest api
		$this->root_path = str_replace( [ '{', '}' ], '', Utilities::get_prefix() . '/v2/' );

		// Setup Theme Options Page Menu in Admin
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'register_options_menu_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		}

		//register api class
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );

	}

	/**
	 * Create Plugin options menu
	 */
	function register_options_menu_page() {

		$page_title = __( '{plugin_name}', '{plugin_text_domain}' );

		$capability = 'manage_options';

		$menu_title = $page_title;

		$this->settings_page_slug = Utilities::get_prefix() . Utilities::get_slug();

		$function = array( $this, 'options_menu_page_output' );

		$icon_url = 'dashicons-editor-justify';

		$position = 11; // 11 - Above Comments Menu Item

		add_menu_page( $page_title, $menu_title, $capability, $this->settings_page_slug, $function, $icon_url, $position );

	}

	function options_menu_page_output() {

		$this->setup_module_info();

		// All variables set at the class level can be used it the template file... ex. $this->modules_info
		require( Utilities::get_template( 'options-menu-page.php' ) );
	}

	/**
	 * @param $hook
	 */
	function scripts( $hook ) {

		if ( strpos( $hook, $this->settings_page_slug ) ) {

			// Setup group management JS with localized WP Rest API variables @see rest-api-end-points.php
			wp_register_script( Utilities::get_prefix() . '-admin-settings', Utilities::get_backend_js(), array(), Utilities::get_version(), true );

			// API data
			$api_setup = array(
				'root'          => esc_url_raw( rest_url() . $this->root_path ),
				'nonce'         => \wp_create_nonce( 'wp_rest' ),
				'plugin_prefix' => Utilities::get_prefix()
			);

			wp_localize_script( Utilities::get_prefix() . '-admin-settings', 'SettingsApiSetup', $api_setup );

			wp_enqueue_script( Utilities::get_prefix() . '-admin-settings' );

			wp_enqueue_style( Utilities::get_prefix() . '-admin-settings', Utilities::get_backend_css(), array(), Utilities::get_version() );
		}

	}

	function setup_module_info() {

		if ( empty( $this->modules_info ) ) {

			$initialized_classes = Utilities::get_all_class_instances();

			foreach ( $initialized_classes as $class_name => $class_instance ) {
				if ( is_subclass_of( $class_instance, __NAMESPACE__ . '\Module' ) ) {
					$this->modules_info[ $class_name ] = $class_instance;
				}
			}
		}
	}

	/**
	 * Rest API Custom Endpoints
	 *
	 * @since 1.0
	 */
	function register_routes() {

		register_rest_route( $this->root_path, '/switch/', array(
			'methods'             => 'POST',
			'callback'            => array( $this, 'set_switch' ),
			'permission_callback' => array( $this, 'set_switch_permissions' )
		) );

		register_rest_route( $this->root_path, '/save-settings/', array(
			'methods'             => 'POST',
			'callback'            => array( $this, 'save_settings' ),
			'permission_callback' => array( $this, 'save_settings_permissions' )
		) );
	}

	function set_switch() {

		// The rest response object
		$response = (object) [];

		// Default return message
		$response->message = __( 'There was a WordPress error. Please reload the page and trying again.', '{plugin_text_domain}' );
		$response->success = false;

		if ( is_array( $_POST ) ) {

			$checked = ( isset( $_POST['checked'] ) ) ? $_POST['checked'] : '';
			$class   = stripslashes( ( isset( $_POST['class'] ) ) ? $_POST['class'] : '' );

			if ( empty( $class )
				 || empty( $checked )
				 || ! in_array( $checked, [ 'true', 'false' ] )
				 || ! class_exists( $class )
			) {
				$response = new \WP_REST_Response( $response, 200 );

				return $response;
			}

			if ( 'true' === $checked ) {
				update_option( 'switch-' . $class, 'on' );
				$response->message = __( 'Module is active.', '{plugin_text_domain}' );
				$response->success = true;
			} elseif ( 'false' === $checked ) {
				$response->message = __( 'Module is inactive.', '{plugin_text_domain}' );
				$response->success = true;
				update_option( 'switch-' . $class, 'off' );
			}

			$response = new \WP_REST_Response( $response, 200 );

			return $response;


		}

		$response = new \WP_REST_Response( $response, 200 );

		return $response;
	}

	function save_settings() {

		// The rest response object
		$response = (object) [];

		// Default return message
		$response->message = __( 'There was a WordPress error. Please reload the page and trying again.', '{plugin_text_domain}' );
		$response->success = false;

		if ( is_array( $_POST ) ) {

			$class        = stripslashes( ( isset( $_POST['class'] ) ) ? $_POST['class'] : '' );
			$class_object = Utilities::get_class_instance( $class );
			$form_fields  = ( isset( $_POST['formFields'] ) ) ? $_POST['formFields'] : [];

			if ( empty( $class )
				 || empty( $form_fields )
				 || ! $class_object
			) {
				$response = new \WP_REST_Response( $response, 200 );

				return $response;
			}

			$class_settings = $class_object->get_settings();


			foreach ( $class_settings as $setting ) {
				if ( isset( $setting['type'] ) ) {
					if ( isset( $setting['name'] ) && isset( $form_fields[ $setting['name'] ] ) ) {
						update_option( $class . '>' . $setting['name'], $form_fields[ $setting['name'] ] );
					}
				}
			}

			$response->message = __( 'Module settings are saved.', '{plugin_text_domain}' );
			$response->success = true;

			$response = new \WP_REST_Response( $response, 200 );

			return $response;

		}

		$response = new \WP_REST_Response( $response, 200 );

		return $response;

	}

	/**
	 * This is our callback function that embeds our resource in a WP_REST_Response
	 */
	function set_switch_permissions() {

		$capability = apply_filters( 'set_module_switch', 'manage_options' );

		// Restrict endpoint to only users who have the edit_posts capability.
		if ( ! current_user_can( $capability ) ) {
			return new \WP_Error( 'rest_forbidden', esc_html__( 'You do not have the capability to switch modules on or off.', '{plugin_text_domain}' ), array( 'status' => 401 ) );
		}

		// This is a black-listing approach. You could alternatively do this via white-listing, by returning false here and changing the permissions check.
		return true;
	}

	/**
	 * This is our callback function that embeds our resource in a WP_REST_Response
	 */
	function save_settings_permissions() {

		$capability = apply_filters( 'save_module_settings', 'manage_options' );

		// Restrict endpoint to only users who have the edit_posts capability.
		if ( ! current_user_can( $capability ) ) {
			return new \WP_Error( 'rest_forbidden', esc_html__( 'You do not have the capability to save module settings.', '{plugin_text_domain}' ), array( 'status' => 401 ) );
		}

		// This is a black-listing approach. You could alternatively do this via white-listing, by returning false here and changing the permissions check.
		return true;
	}

}