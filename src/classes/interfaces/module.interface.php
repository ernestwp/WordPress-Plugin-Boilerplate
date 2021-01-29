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
interface Module_i {
	function set_module_details();

	function dependants_exist();

	function run();
}