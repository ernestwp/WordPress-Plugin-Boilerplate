import './sidebar.js';

const {__}                = wp.i18n;
const {registerBlockType} = wp.blocks;


registerBlockType( 'plugin-slug/post-list', {
	title: __( 'List Posts', 'text-domain' ),

	description: __( 'Lists all posts.', 'text-domain' ),

	icon: 'editor-ul',

	category: 'custom-list-block',

	keywords: [
		__( 'list', 'text-domain' ),
		__( 'Post', 'text-domain' ),
	],

	supports: {
		html: false
	},

	attributes: {
		listClass: {
			type: 'string',
			default: '',
		},
		listType: {
			type: 'array',
			default: 'anchor'
		}
	},

	edit( {className, attributes, setAttributes} ){
		return (
			<div className={className}>
				{__( 'List Posts', 'text-domain' )}
			</div>
		);
	},

	save( {className, attributes} ){
		// We're going to render this block using PHP
		// Return null
		return null;
	},
} );
