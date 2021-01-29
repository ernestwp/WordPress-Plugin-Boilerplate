<?php

namespace __plugin_namespace__;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * This class autoloads all files withing the classes directory
 *
 * @package __plugin_namespace__
 * @author  {plugin_author}
 */
class Autoloader {

	/**
	 * The instance of the class
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      Boot
	 */
	private static $instance = null;

	/**
	 * The directories that are auto loaded and initialized
	 *
	 * @since    {plugin_version}
	 * @access   private
	 * @var      array
	 */
	private static $auto_loaded_directories = null;

	/**
	 * class constructor
	 */
	private function __construct() {

		// We need to check if spl auto loading is available when activating plugin
		// Plugin will not activate if SPL extension is not enabled by throwing error
		if ( ! extension_loaded( 'SPL' ) ) {
			trigger_error( esc_html__( 'Please contact your hosting company to update to php version 5.3+ and enable spl extensions.', '{plugin_text_domain}' ), E_USER_ERROR );
		}

		// initialize interface
		require_once( Utilities::get_src_dir() . 'classes' . DIRECTORY_SEPARATOR . 'interfaces' . DIRECTORY_SEPARATOR . 'module.interface.php' );
		// initialize abstract
		require_once( Utilities::get_src_dir() . 'classes' . DIRECTORY_SEPARATOR . 'abstracts' . DIRECTORY_SEPARATOR . 'module.abstract.php' );

		spl_autoload_register( array( $this, 'require_class_files' ) );

		// Initialize all classes in given directories
		$this->auto_initialize_classes();

	}


	/**
	 * Creates singleton instance of Autoloader class and defines which directories are auto loaded
	 *
	 * @param array $auto_loaded_directories
	 *
	 * @return Boot
	 * @since {plugin_version}
	 *
	 */
	public static function get_instance(
		$auto_loaded_directories = [
			'classes' . DIRECTORY_SEPARATOR . 'modules',
			'extensions'
		]
	) {

		if ( null === self::$instance ) {

			// Define directories were the auto loader looks for files and initializes them
			self::$auto_loaded_directories = $auto_loaded_directories;

			// Lets boot up!
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * SPL Auto Loader functions
	 *
	 * @param string $class Any
	 *
	 * @since {plugin_version}
	 *
	 */
	private function require_class_files( $class ) {


		// Remove Class's namespace eg: my_namespace/MyClassName to MyClassName
		$class = str_replace( __NAMESPACE__, '', $class );
		$class = str_replace( '\\', '', $class );

		// Replace _ with - eg. eg: My_Class_Name to My-Class-Name
		$class_to_filename = str_replace( '_', '-', $class );

		// Create file name that will be loaded from the classes directory eg: My-Class-Name to my-class-name.php
		$file_name = strtolower( $class_to_filename ) . '.php';

		// Check each directory
		foreach ( self::$auto_loaded_directories as $directory ) {

			$file_path = Utilities::get_src_dir() . $directory . DIRECTORY_SEPARATOR . $file_name;

			// Does a standalone file exist
			if ( file_exists( $file_path ) ) {

				// File found, require it
				require_once( $file_path );

				// You can cannot have duplicate files names. Once the first file is found, the loop ends.
				return;
			}

			$file_path = Utilities::get_src_dir() . $directory . DIRECTORY_SEPARATOR . $class_to_filename . DIRECTORY_SEPARATOR . $file_name;

			// Does a directory with file exist
			if ( file_exists( $file_path ) ) {

				// File found, require it
				require_once( $file_path );

				// You can cannot have duplicate files names. Once the first file is found, the loop ends.
				return;
			}
		}

	}

	/**
	 * Looks through all defined directories and modifies file name to create new class instance.
	 *
	 * @since {plugin_version}
	 *
	 */
	private function auto_initialize_classes() {

		// Check each directory
		foreach ( self::$auto_loaded_directories as $auto_loaded_directory ) {

			// Get all files in directory
			$directory_contents = scandir( Utilities::get_src_dir() . $auto_loaded_directory );

			// remove parent directory, sub directory, and silence is golden index.php if they exists
			$directory_contents = array_diff( $directory_contents, array( '..', '.', 'index.php' ) );

			// Loop through all files in directory to create class names from file name
			foreach ( $directory_contents as $content ) {

				// Check if it is a file or directory
				if ( false === strpos( $content, '.php' ) ) {
					// Its a directory with a module file
					$file = $content . '.php';
				} else {
					// Its a standalone module file
					$file = $content;
				}

				// Remove file extension my-class-name.php to my-class-name
				$file_name = str_replace( '.php', '', $file );

				// Split file name on - eg: my-class-name to array( 'my', 'class', 'name')
				$class_to_filename = explode( '-', $file_name );

				// Make the first letter of each word in array upper case - eg array( 'my', 'class', 'name') to array( 'My', 'Class', 'Name')
				$class_to_filename = array_map( function ( $word ) {
					return ucfirst( $word );
				}, $class_to_filename );

				// Implode array into class name - eg. array( 'My', 'Class', 'Name') to MyClassName
				$class_name = implode( '_', $class_to_filename );

				$class = __NAMESPACE__ . '\\' . $class_name;

				// We way want to include some class with the autoloader but not initialize them
				$skip_classes = apply_filters( 'Skip_class_initialization', array(), $auto_loaded_directory, $content, $class, $class_name );
				if ( in_array( $class_name, $skip_classes ) ) {
					continue;
				}

				//$path     = Utilities::get_src_dir() . $directory . DIRECTORY_SEPARATOR . $file;
				//$contents = file_get_contents( $path );
				//var_dump( $contents );

				// On plugin activation,
				// 1. collect all comments from every file loaded
				// 2. collect all add_shortcode, apply_filters, and do_actions


				//$some_param    = array();
				//$another_param = '';

				// ex
				/*
				 * The first line is the title
				 *
				 * The next line is the description and can be mulitple lines and even html
				 * entities. <br> The '@see the_hook' must be present to make the connection.
				 *
				 * @see the_hook
				 * @since version 1.0
				 * @access plugin | module | general  // not everyone needs to se all filters.... maybe we can categories them depending on if its a module and depend file, core plugin architecture file, and not making have to @access tag
				 * @param array $some_param Then the description at the end
				 * @param string $another_param Then the description at the end
				 */
				//do_action( 'the_hook', array( $this, 'the_hook_function' ), $some_param, $another_param );

				//regex101
				// regex mulitline comments:  ^\s\/\*\*?[^!][.\s\t\S\n\r]*?\*\/    <<-- tested first
				// regex multiline comments: (?<!\/)\/\*((?:(?!\*\/).|\s)*)\*\/    <<-- found another https://regex101.com/r/nW6hU2/1
				// regex add_shortcode line functions: ^.*\badd_shortcode\b.*$
				// regex add_shortcode line functions: ^.*\bdo_action\b.*$
				// regex add_shortcode line functions: ^.*\bapply_filters\b.*$
				Utilities::set_class_instance( $class, new $class );
			}
		}
	}


	/**
	 * Make clone magic method private, so nobody can clone instance.
	 *
	 * @since {plugin_version}
	 */
	private function __clone() {
	}

	/**
	 * Make sleep magic method private, so nobody can serialize instance.
	 *
	 * @since {plugin_version}
	 */
	private function __sleep() {
	}

	/**
	 * Make wakeup magic method private, so nobody can unserialize instance.
	 *
	 * @since {plugin_version}
	 */
	private function __wakeup() {

	}

}





