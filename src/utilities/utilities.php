<?php

/**
 * break this up into multiple files
 *
 */

namespace __plugin_namespace__;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * This class stores helper functions that can be used statically in all of WP after plugins loaded hook
 *
 * Use the Utilites::get_% function to retrieve the variable. The following is a list of calls
 *
 * @package    __plugin_namespace__
 * @author     {plugin_author}
 */
class Utilities {

	/**
	 * The name of the plugin
	 *
	 * @use      get_plugin_name()
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      string
	 */
	private static $plugin_name;

	/**
	 * The prefix of this plugin that is set in the config class
	 *
	 * @use      get_version()
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      string
	 */
	private static $prefix;

	/**
	 * The slug of this plugin that is set in the config class
	 *
	 * @use      get_version()
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      string
	 */
	private static $slug;

	/**
	 * The plugins version number
	 *
	 * @use      get_version()
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      string
	 */
	private static $version;

	/**
	 * The main plugin file path
	 *
	 * @use      get_plugin_file()
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      string
	 */
	private static $plugin_file;

	/**
	 * The references to autoloaded class instances
	 *
	 * @use      get_autoloaded_class_instance()
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      array
	 */
	private static $class_instances = array();

	/**
	 * The plugin date and time format
	 *
	 * @use      get_date_time_format()
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      bool
	 */
	private static $date_time_format;

	/**
	 * The plugin date format
	 *
	 * @use      get_date_format()
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      bool
	 */
	private static $date_format;

	/**
	 * The plugin time format
	 *
	 * @use      get_time_format()
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      bool
	 */
	private static $time_format;

	/**
	 * The server time when the plugin was initialized
	 *
	 * @use      get_time_format()
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      bool
	 */
	private static $plugin_initialization;

	/**
	 * Set the name of the plugin
	 *
	 * @param string $plugin_name The name of the plugin
	 *
	 * @return string
	 * @since    {plugin_version}
	 *
	 */
	public static function set_plugin_name( $plugin_name ) {
		if ( null === self::$prefix ) {
			self::$plugin_name = $plugin_name;
		}

		return self::$plugin_name;
	}

	/**
	 * Get the name of the plugin
	 *
	 * @return string
	 * @since    {plugin_version}
	 *
	 */
	public static function get_plugin_name() {
		return self::$plugin_name;
	}

	/**
	 * Set the prefix for the plugin
	 *
	 * @param string $prefix Variable used to prefix filters and actions
	 *
	 * @return string
	 * @since    {plugin_version}
	 *
	 */
	public static function set_prefix( $prefix ) {
		if ( null === self::$prefix ) {
			self::$prefix = $prefix;
		}

		return self::$prefix;
	}

	/**
	 * Get the prefix for the plugin
	 *
	 * @return string
	 * @since    {plugin_version}
	 *
	 */
	public static function get_prefix() {
		return self::$prefix;
	}

	/**
	 * Set the slug for the plugin
	 *
	 * @param string $slug Variable used to create custom menus and post/taxonomies/...
	 *
	 * @return string
	 * @since    {plugin_version}
	 *
	 */
	public static function set_slug( $slug ) {
		if ( null === self::$slug ) {
			self::$slug = $slug;
		}

		return self::$slug;
	}

	/**
	 * Get the slug for the plugin
	 *
	 * @return string
	 * @since    {plugin_version}
	 *
	 */
	public static function get_slug() {
		return self::$slug;
	}

	/**
	 * Set the version for the plugin
	 *
	 * @param string $version Variable used to prefix filters and actions
	 *
	 * @return string
	 * @since    {plugin_version}
	 *
	 */
	public static function set_version( $version ) {
		if ( null === self::$version ) {
			if( false !== strpos($version,'plugin_version')){
				self::$version = '1.0';
			}else{
				self::$version = $version;
			}
		}

		return self::$version;
	}

	/**
	 * Get the version for the plugin
	 *
	 * @return string
	 * @since    {plugin_version}
	 *
	 */
	public static function get_version() {
		return self::$version;
	}


	/**
	 * Set the main plugin file path
	 *
	 * @param string $plugin_file The main plugin file path
	 *
	 * @return string
	 * @since    {plugin_version}
	 *
	 */
	public static function set_plugin_file( $plugin_file ) {
		if ( null === self::$plugin_file ) {
			self::$plugin_file = $plugin_file;
		}

		return self::$plugin_file;
	}

	/**
	 * Get the version for the plugin
	 *
	 * @return string
	 * @since    {plugin_version}
	 *
	 */
	public static function get_plugin_file() {
		return self::$plugin_file;
	}

	/**
	 * Set the main plugin file path
	 *
	 * @param string $class_name     The name of the class instance
	 * @param object $class_instance The reference to the class instance
	 *
	 * @since    {plugin_version}
	 *
	 */
	public static function set_class_instance( $class_name, $class_instance ) {

		self::$class_instances[ $class_name ] = $class_instance;

	}

	/**
	 * Get all class instances
	 *
	 * @return array
	 * @since    {plugin_version}
	 *
	 */
	public static function get_all_class_instances() {
		return self::$class_instances;
	}

	/**
	 * Get a specific class instance
	 *
	 * @param string $class_name The name of the class instance
	 *
	 * @return object | bool
	 * @since    {plugin_version}
	 *
	 */
	public static function get_class_instance( $class_name ) {
		if ( isset( self::$class_instances[ $class_name ] ) ) {
			return self::$class_instances[ $class_name ];
		} else {
			return false;
		}
	}

	/**
	 * Set the default date and time format
	 *
	 * @param string $date      Date format
	 * @param string $time      Time format
	 * @param string $separator The separator between the date and time format
	 *
	 * @return bool
	 * @since    {plugin_version}
	 *
	 */
	public static function set_date_time_format( $date = 'F j, Y', $time = ' g:i a', $separator = ' ' ) {

		$date      = apply_filters( self::$prefix . '_date_time_format', $date );
		$time      = apply_filters( self::$prefix . '_date_time_format', $time );
		$separator = apply_filters( self::$prefix . '_date_time_format', $separator );

		if ( null === self::$date_time_format ) {
			self::$date_time_format = $date . $separator . $time;
		}

		if ( null === self::$date_format ) {
			self::$date_format = $date;
		}

		if ( null === self::$time_format ) {
			self::$time_format = $time;
		}

		return self::$date_time_format;
	}

	/**
	 * Get the date and time format for the plugin
	 *
	 * @return string
	 * @since    {plugin_version}
	 *
	 */
	public static function get_date_time_format() {
		return self::$date_time_format;
	}

	/**
	 * Get the date format for the plugin
	 *
	 * @return string
	 * @since    {plugin_version}
	 *
	 */
	public static function get_date_format() {
		return self::$date_time_format;
	}

	/**
	 * Get the time format for the plugin
	 *
	 * @return string
	 * @since    {plugin_version}
	 *
	 */
	public static function get_time_format() {
		return self::$date_time_format;
	}

	/**
	 * Set the server time when the plugin was initialized
	 *
	 * @param int $time Timestamp
	 *
	 * @return int
	 * @since    {plugin_version}
	 *
	 */
	public static function set_plugin_initialization( $time ) {

		if ( null === self::$plugin_initialization ) {
			self::$plugin_initialization = $time;
		}

		return self::$plugin_initialization;
	}

	/**
	 * Get the server time when the plugin was initialized
	 *
	 * @return int Timestamp
	 * @since    {plugin_version}
	 *
	 */
	public static function get_plugin_initialization() {
		return self::$plugin_initialization;
	}

	/**
	 * Returns the full url for the complied/minified JS file
	 * @return string $asset_url
	 * @since    0.1
	 *
	 */
	public static function get_frontend_js() {
		return plugins_url( 'src/assets/dist/frontend/bundle.min.js', self::$plugin_file );
	}

	/**
	 * Returns the full url for the complied/minified JS file
	 *
	 * @return string $asset_url
	 * @since    0.1
	 *
	 */
	public static function get_backend_js() {
		return plugins_url( 'src/assets/dist/backend/bundle.min.js', self::$plugin_file );
	}

	/**
	 * Returns the full url for the complied/minified CSS file
	 * @return string $asset_url
	 * @since    0.1
	 *
	 */
	public static function get_frontend_css() {
		return plugins_url( 'src/assets/dist/frontend/bundle.min.css', self::$plugin_file );
	}

	/**
	 * Returns the full url for the complied/minified CSS file
	 *
	 * @return string $asset_url
	 * @since    0.1
	 *
	 */
	public static function get_backend_css() {
		return plugins_url( 'src/assets/dist/backend/bundle.min.css', self::$plugin_file );
	}

	/**
	 * Returns the full url for the passed media file
	 *
	 * @param string $file_name
	 *
	 * @return string $asset_url
	 * @since    {plugin_version}
	 *
	 */
	public static function get_media( $file_name ) {
		return plugins_url( 'src/assets/dist/media/' . $file_name, self::$plugin_file );
	}

	/**
	 * Returns the full server path for the src directory
	 * @since    0.1
	 *
	 */
	public static function get_src_dir() {
		return dirname( self::$plugin_file ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
	}

	/**
	 * Returns the full server path for the passed template file
	 *
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_template( $file_name ) {

		$templates_directory = self::get_src_dir() . 'templates' . DIRECTORY_SEPARATOR;

		/**
		 * Filters the director path to the template file
		 *
		 * This can be used for template overrides by modifying the path to go to a directory in the theme or another plugin.
		 *
		 * @param string $templates_directory Path to the plugins template folder
		 * @param string $file_name           The file name of the template file
		 *
		 * @since {plugin_version}
		 *
		 */
		$templates_directory = apply_filters( Utilities::get_prefix() . '_template_path', $templates_directory, $file_name );

		$asset_path = $templates_directory . $file_name;

		return $asset_path;
	}

	/**
	 * Returns the full server path for the passed include file
	 *
	 * @param string $file_name
	 *
	 * @return string
	 */
	public static function get_include( $file_name ) {

		$includes_directory = self::get_src_dir() .  'includes' . DIRECTORY_SEPARATOR;

		/**
		 * Filters the director path to the include file
		 *
		 * This can be used for include overrides by modifying the path to go to a directory in the theme or another plugin.
		 *
		 * @param string $includes_directory Path to the plugins include folder
		 * @param string $file_name          The file name of the include file
		 *
		 * @since {plugin_version}
		 *
		 */
		$includes_directory = apply_filters( Utilities::get_prefix() . '_includes_path_to', $includes_directory, $file_name );

		$asset_path = $includes_directory . $file_name;

		return $asset_path;
	}

	/**
	 * !!! ALPHA FUNCTION - NEEDS TESTING/BENCHMARKING
	 *
	 * Get User data with meta keys' value
	 *
	 * In some cases we need to loop a lot of users' data. If we need 1000 user with there user meta values we would
	 * normal run WP User Query, then loop the user and run get_user_meta() on each iteration which will return the
	 * specified user meta and also collect/store ALL the user meta. In case above, WP will run 1 query for the user loop
	 * and 1000 user meta queries; 1001 queries will run. WP will also store all the data collected in memory, if each
	 * user has 100 metas stores then 1000 x 100 metas is 100 000 values.
	 *
	 * With this function if we run the same scenrio as above, 2 quieries will run and only the amount of data points
	 * that are specifically needed. 1000 users
	 *
	 * Todo Maybe add optional transient
	 * Todo Benchmarking needs
	 *
	 * Only Returns this first meta_key value. Does not support multiple meta_values per single key.
	 *
	 * @param array $exact_meta_keys
	 * @param array $fuzzy_meta_keys
	 * @param array $include_user_ids
	 *
	 * @return array
	 */
	function get_users_with_meta( $exact_meta_keys = array(), $fuzzy_meta_keys = array(), $include_user_ids = array() ) {

		global $wpdb;

		// Collect all possible meta_key values
		$keys = $wpdb->get_col( "SELECT distinct meta_key FROM $wpdb->usermeta" );

		//then prepare the meta keys query as fields which we'll join to the user table fields
		$meta_columns = '';
		foreach ( $keys as $key ) {

			// Collect exact matches
			if ( ! empty( $exact_meta_keys ) ) {
				if ( in_array( $key, $exact_meta_keys ) ) {
					$meta_columns .= " MAX(CASE WHEN um1.meta_key = '$key' THEN um1.meta_value ELSE NULL END) AS '$key', \n";
					continue;
				}
			}

			// Collect fuzzy matches ... ex. "example" would match "example_947"
			// ToDo allow for SQL "LIKE" syntax ... ex "example%947"
			// ToDo allow for regex
			if ( ! empty( $fuzzy_meta_keys ) ) {
				foreach ( $fuzzy_meta_keys as $fuzzy_key ) {
					if ( false !== strpos( $key, $fuzzy_key ) ) {
						$meta_columns .= " MAX(CASE WHEN um1.meta_key = '$key' THEN um1.meta_value ELSE NULL END) AS '$key', \n";
					}
				}

			}


		}

		//then write the main query with all of the regular fields and use a simple left join on user users.ID and usermeta.user_id
		$query = "
SELECT  
    u.ID,
    u.user_login,
    u.user_pass,
    u.user_nicename,
    u.user_email,
    u.user_url,
    u.user_registered,
    u.user_activation_key,
    u.user_status,
    u.display_name,
    " . rtrim( $meta_columns, ", \n" ) . " 
FROM 
    $wpdb->users u
LEFT JOIN 
    $wpdb->usermeta um1 ON (um1.user_id = u.ID)    
GROUP BY 
    u.ID";

		$users = $wpdb->get_results( $query, ARRAY_A );

		return array(
			'query'   => $query,
			'results' => $users
		);


	}

	/**
	 * Create and store logs @ wp-content/{plugin_folder_name}/{$file_name}.log
	 *
	 * @param string $trace_message The message logged
	 * @param string $trace_heading The heading of the current trace
	 * @param bool   $var_dump      var_dump() the trace
	 * @param string $file_name     The file name of the log file
	 *
	 * @return bool $error_log Was the log successfully created
	 * @since    {plugin_version}
	 *
	 */
	public static function trace( $trace_message = '', $trace_heading = 'Tracing', $var_dump = false, $file_name = 'utilities-log' ) {

		$timestamp = date( self::get_date_time_format() );

		$current_page_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$trace = "\n[ $timestamp ] $trace_heading : $current_page_link \n";

		ob_start();
		var_dump( $trace_message );
		$trace_message = ob_get_clean();

		$file = WP_CONTENT_DIR . '/' . $file_name . '.log';

		$error_log = error_log( $trace . $trace_message, 3, $file );

		if ( true === $var_dump ) {
			echo '<pre>';
			echo $trace . $trace_message;
			echo '</pre>';

		}

		return $error_log;
	}

}