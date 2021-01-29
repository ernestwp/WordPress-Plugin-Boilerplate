<?php

namespace __plugin_namespace__;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Blocks
 * @package __plugin_namespace__
 */
class Blocks {

	/**
	 * Blocks constructor.
	 *
	 */
	public function __construct() {

		// Check if Gutenberg exists
		if ( function_exists( 'register_block_type' ) ) {

			// Register Blocks
			add_action( 'init', function () {
				require_once( dirname( __FILE__ ) . '/src/post-list/block.php' );
			} );

			// Enqueue Gutenberg block assets for both frontend + backend
			add_action( 'enqueue_block_assets', function () {
				wp_enqueue_style(
					'plugin-prefix-gutenberg-blocks',
					plugins_url( 'blocks/dist/blocks.style.build.css', dirname( __FILE__ ) ),
					[],
					Utilities::get_version()
				);
			} );

			// Enqueue Gutenberg block assets for backend editor
			add_action( 'enqueue_block_editor_assets', function () {
				wp_enqueue_script(
					'plugin-prefix-gutenberg-editor',
					plugins_url( 'blocks/dist/blocks.build.js', dirname( __FILE__ ) ),
					[ 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ],
					Utilities::get_version(),
					true
				);

				wp_enqueue_style(
					'plugin-prefix-gutenberg-editor',
					plugins_url( 'blocks/dist/blocks.editor.build.css', dirname( __FILE__ ) ),
					[ 'wp-edit-blocks' ],
					Utilities::get_version()
				);
			} );

			// Create custom block category
			add_filter( 'block_categories', function ( $categories, $post ) {
				return array_merge(
					$categories,
					array(
						array(
							'slug'  => 'custom-list-block',
							'title' => __( 'Post List', 'text-domain' ),
						),
					)
				);
			}, 10, 2 );
		}
	}
}
