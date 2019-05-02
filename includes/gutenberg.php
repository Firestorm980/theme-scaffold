<?php
/**
 * Gutenberg setup, site hooks and filters.
 *
 * @package TenUpScaffold\Gutenberg
 */

namespace TenUpScaffold\Gutenberg;

const COMMON_BLOCKS = [
	'core/paragraph',
	'core/image',
	'core/heading',
	'core/gallery',
	'core/list',
	'core/quote',
	'core/audio',
	'core/cover',
	'core/file',
	'core/video',
];

const FORMATTING = [
	'core/code',
	'core/freeform',
	'core/html',
	'core/preformatted',
	'core/pullquote',
	'core/table',
	'core/verse',
];

const LAYOUT_ELEMENTS = [
	'core/button',
	'core/columns',
	'core/column',
	'core/media-text',
	'core/more',
	'core/nextpage',
	'core/separator',
	'core/spacer',
];

const WIDGETS = [
	'core/shortcode',
	'core/archives',
	'core/categories',
	'core/latest-comments',
	'core/latest-posts',
];

const EMBEDS = [
	'core/embed',
	'core-embed/twitter',
	'core-embed/youtube',
	'core-embed/facebook',
	'core-embed/instagram',
	'core-embed/wordpress',
	'core-embed/soundcloud',
	'core-embed/spotify',
	'core-embed/flickr',
	'core-embed/vimeo',
	'core-embed/animoto',
	'core-embed/cloudup',
	'core-embed/collegehumor',
	'core-embed/dailymotion',
	'core-embed/funnyordie',
	'core-embed/hulu',
	'core-embed/imgur',
	'core-embed/issuu',
	'core-embed/kickstarter',
	'core-embed/meetup-com',
	'core-embed/mixcloud',
	'core-embed/photobucket',
	'core-embed/polldaddy',
	'core-embed/reddit',
	'core-embed/reverbnation',
	'core-embed/screencast',
	'core-embed/scribd',
	'core-embed/slideshare',
	'core-embed/smugmug',
	'core-embed/speaker',
	'core-embed/speaker-deck',
	'core-embed/ted',
	'core-embed/tumblr',
	'core-embed/videopress',
	'core-embed/wordpress-tv',
];

/**
 * Set up theme defaults and register supported WordPress features.
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Filter blocks directly
	add_filter( 'allowed_block_types', $n( 'theme_allowed_block_types' ), 10, 2 );

	// Filter blocks by category
	add_filter( 'block_categories', $n( 'theme_allowed_block_categories' ), 10, 2 );

	// JS
	add_action( 'enqueue_block_editor_assets', $n( 'theme_enqueue_block_editor_assets' ) );
}

/**
 * Enqueue JS alternatives
 *
 * @return void
 */
function theme_enqueue_block_editor_assets() {
	wp_enqueue_script(
		'admin',
		TENUP_SCAFFOLD_TEMPLATE_URL . '/dist/js/admin.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		TENUP_SCAFFOLD_VERSION,
		true
	);
}

/**
 * Allow specific blocks
 *
 * @param  array   $allowed_block_types Currently allowed types.
 * @param  WP_Post $post                Post object.
 * @return array                        Array of allowed types.
 */
function theme_allowed_block_types( $allowed_block_types, $post ) {

	$post_type = $post->post_type;

	switch ( $post_type ) {
		case 'project':
			$blocks = array_merge(
				COMMON_BLOCKS
			);

			$blocks_to_remove = [
				'core/audio',
				'core/cover',
				'core/file',
				'core/video',
			];

			$allowed_blocks = array_diff( $blocks, $blocks_to_remove );

			return $allowed_blocks;
		default:
			return true;
	}
}

/**
 * Allow specific block categories
 *
 * @param  array   $categories Default categories.
 * @param  WP_Post $post       Post object.
 * @return array               Array of allowed categories.
 */
function theme_allowed_block_categories( $categories, $post ) {

	$post_type = $post->post_type;

	switch ( $post_type ) {
		case 'group':
			$categories_to_remove = [
				'formatting',
				'embed',
				'reusable',
			];

			foreach ( $categories as $key => $value ) {
				if ( in_array( $value['slug'], $categories_to_remove, true ) ) {
					unset( $categories[ $key ] );
				}
			}

			return $categories;
		default:
			return $categories;
	}
}
