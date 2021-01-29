<?php

namespace My_Testing;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class CustomPostTypeAndCustomTaxonomy
 *
 * This class adds a custom post with custom taxonomies
 *
 * @package     My_Testing
 * @since       0.1
 * @version     0.1
 *
 * TODO Localize strings
 *
 */
class Custom_Post_Type_And_Custom_Taxonomy {

	/**
	 * Make sure the first letter of the name is capitalized
	 * @var string
	 */
	private $singular_name = 'Certification';

	/**
	 * Make sure the first letter of the name is capitalized
	 * @var string
	 */
	private $plural_name = 'Certifications';

	/**
	 * Keep it simple. All lower case with NO special characters
	 * @var string
	 */
	private $slug = 'certification';
	private $meta_box_title = 'Certificate Custom Fields';
	private $meta_box_fields = array(
		'enrolled-users'       => array(
			'label'         => 'Enrolled Users(maybe add)',
			'type'          => 'text',
			'class'         => 'large-text', // wp classes: .small-text .regular-text .large-text or a custom class
			'default_value' => '',
			'readonly'      => false
		),
		'certification-period' => array(
			'label'         => 'Certification Period(days)',
			'type'          => 'text',
			'class'         => 'regular-text', // wp classes: .small-text .regular-text .large-text or a custom class
			'default_value' => '',
			'readonly'      => false
		),
		'product-association'  => array(
			'label'         => 'Product Association',
			'type'          => 'text',
			'class'         => 'regular-text', // wp classes: .small-text .regular-text .large-text or a custom class
			'default_value' => '',
			'readonly'      => true
		)
	);

	/**
	 * Class constructor
	 *
	 * @since 0.1
	 */
	function __construct() {
		// Post Type and Taxonomy creation should be called at 'init'
		add_action( 'init', array( $this, 'init' ) );
	}

	function init() {
		$this->create_custom_post_type();
		$this->create_custom_category();
		$this->create_custom_tag();
		$this->create_meta_box();
	}

	function create_custom_post_type() {

		$labels = array(
			'name'               => $this->singular_name,
			'singular_name'      => $this->singular_name,
			'add_new'            => 'Add ' . $this->singular_name,
			'all_items'          => 'All ' . $this->plural_name,
			'add_new_item'       => 'Add ' . $this->singular_name,
			'edit_item'          => 'Edit ' . $this->singular_name,
			'new_item'           => 'New ' . $this->singular_name,
			'view_item'          => 'View ' . $this->singular_name,
			'search_items'       => 'Search ' . strtolower( $this->plural_name ),
			'not_found'          => 'No ' . strtolower( $this->plural_name ) . ' found',
			'not_found_in_trash' => 'No ' . strtolower( $this->plural_name ) . ' found in trash',
			'parent_item_colon'  => 'Parent ' . strtolower( $this->singular_name ),
			'menu_name'          => $this->singular_name
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => false,
			'publicly_queryable'  => true,
			'query_var'           => true,
			'rewrite'             => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'author',
				//'trackbacks',
				'custom-fields',
				//'comments',
				'revisions',
				'page-attributes', // (menu order, hierarchical must be true to show Parent option)
				//'post-formats',
			),
			//'taxonomies'           => array( 'category', 'post_tag' ), // add default post categories and tags
			'menu_position'       => 5,
			'exclude_from_search' => true
		);

		register_post_type( 'certification', $args );

	}

	function create_custom_category() {

		register_taxonomy( $this->slug . '_category', // register custom taxonomy - category
			$this->slug,
			array(
				'hierarchical' => true,
				'labels'       => array(
					'name'          => $this->singular_name . ' Category',
					'singular_name' => $this->singular_name . ' Category'
				)
			)
		);

	}

	function create_custom_tag() {

		register_taxonomy( $this->slug . '_tag', // register custom taxonomy - tag
			$this->slug,
			array(
				'hierarchical' => false,
				'labels'       => array(
					'name'          => $this->singular_name . ' Tag',
					'singular_name' => $this->singular_name . ' Tag',
				)
			)
		);

	}

	function create_meta_box() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 2 );


	}

	function add_meta_boxes( $post_type ) {
		// Limit meta box to certain post types.
		$post_types = array( $this->slug );

		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				$this->slug . '_meta_box',
				$this->meta_box_title,
				array( $this, 'render_meta_box_content' ),
				$post_type,
				'advanced',
				'high'
			);
		}

	}

	function render_meta_box_content() {

		global $post;

		// Noncename needed to verify where the data originated
		echo '<input type="hidden" name="' . $this->slug . '_post_noncename" value="' . wp_create_nonce( $this->slug . '_post_noncename' ) . '" />';

		// Echo out the fields from the $meta_box_fields array
		$meta_box_fields = apply_filters( $this->slug . '_custom_meta_box', $this->meta_box_fields, $post );

		?>

		<table class="form-table">

			<?php foreach ( $meta_box_fields as $slug => $field ) {

				$value        = $field['default_value'];
				$stored_value = get_post_meta( $post->ID, $slug, true );
				if ( ! empty( $stored_value ) ) {
					$value = $stored_value;
				}

				$disabled = '';
				if ( true === $field['readonly'] ) {
					$disabled = 'disabled="disabled"';
				}
				?>
				<tr>
					<th>
						<label for="<?php echo $slug; ?>"><?php echo $field['label']; ?></label>
					</th>
					<td>
						<input <?php echo $disabled; ?> type="<?php echo $field['type']; ?>" id="<?php echo $slug; ?>"
														name="<?php echo $slug; ?>"
														class="<?php echo $field['class']; ?>"
														value="<?php echo $value; ?>">
					</td>
				</tr>
			<?php } ?>
		</table>
		<?php
	}

	function save_meta_boxes( $post_id, $post ) { // save the data


		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */


		if ( ! isset( $_POST[ $this->slug . '_post_noncename' ] ) ) { // Check if our nonce is set.
			return;
		}

		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( ! wp_verify_nonce( $_POST[ $this->slug . '_post_noncename' ], $this->slug . '_post_noncename' ) ) {
			return $post->ID;
		}

		// is the user allowed to edit the post or page?
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return $post->ID;
		}

		$meta_box_fields = apply_filters( $this->slug . '_custom_meta_box', $this->meta_box_fields, $post );


		// ok, we're authenticated: we need to find and save the data
		foreach ( $meta_box_fields as $slug => $field ) {
			if ( isset( $_POST[ $slug ] ) && false === $field['readonly'] ) {
				update_post_meta( $post_id, $slug, $_POST[ $slug ] );
			}
		}


	}
}
