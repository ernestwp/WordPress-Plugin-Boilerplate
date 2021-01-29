<?php

/**
 * Register List Posts
 * render it with a callback function
 */
register_block_type( 'plugin-slug/post-list', [
	'attributes'      => [
		'listClass' => [
			'type'    => 'string',
			'default' => '',
		],
		'listType'  => [
			'type'    => 'array',
			'default' => 'bullets',
		],
	],
	'render_callback' => 'render_post_list',
] );

function render_post_list( $attributes ) {

	$list_class = $attributes['listClass'];
	$list_type  = $attributes['listType'];

	$posts = get_posts( array(
		'numberposts' => 999
	) );

	$items = '';

	foreach ( $posts as $post ) {
		$items = sprintf( '<li>%s</li>', $post->post_title );
	}

	// Start output
	ob_start();
	if ( 'numbered' === $list_type ) {
		echo sprintf( '<ol class="%s">%s</ol>', $list_class, $items );
	} else {
		echo sprintf( '<ul class="%s">%s</ul>', $list_class, $items );
	}

	return ob_get_clean();
}
